<?php

/**
 * SortThosePhotos
 *
 * A tool to help high volume photographers sort their photos
 */

namespace App\Controller;

use App\Entity\ShootProof\SpAppCredentials;
use App\Entity\ShootProof\SpIntegrationCredentials;
use App\Models\ShootProof\spStudio;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;

/**
 * Handle settings resource
 *
 * Class SettingsController
 * @package App\Controller
 */
class SettingsController extends AbstractController
{
    /**
     * Return settings and settings view
     *
     * @Route("/settings", name="settings")
     *
     * @IsGranted("ROLE_USER")
     */
    public function index()
    {
        // TODO create settings viewmodel
        $spIntegrationsCredentials = $this->
            getDoctrine()->
            getRepository(SpIntegrationCredentials::class)->
            findOneBy([
                'userId' => $this->getUser()->getId()
            ]);

        $spStudio = new spStudio($spIntegrationsCredentials->getAccessToken());

        $isKeyValid = $spStudio->isKeyValid();

        return $this->render('settings/index.html.twig', [
            'isKeyValid' => $isKeyValid,
        ]);
    }

    /**
     * Redirect to ShootProof authorization page
     *
     * @Route("/authorize/shootproof", name="auth_shootproof")
     *
     * @IsGranted("ROLE_USER")
     */
    public function enableSpIntegration()
    {
        $spAppCredentials = $this->
            getDoctrine()->
            getRepository(SpAppCredentials::class)->
            findOneBy([
                'id' => 1
            ]);

        if (is_null($spAppCredentials))
        {
            return new Response("No ShootProof App credentials found on server, please contact the admin.", 500);
        }

        // Build ShootProof authorization query
        $data = array (
            'response_type' => $spAppCredentials->getResponseType(),
            'client_id' => $spAppCredentials->getClientId(),
            'redirect_uri' => urlencode($spAppCredentials->getRedirectUri()),
            'scope' => $spAppCredentials->getScope(),
            'state' => $spAppCredentials->getState()
        );

        $params = '';
        foreach($data as $key=>$value)
            $params .= $key.'='.$value.'&';

        $params = trim($params, '&');

        // Send user to ShootProof for authorization
        return $this->redirect($_ENV['BASE_SP_AUTH_URL'] . '/new' . '?' . $params);
    }

    /**
     * Gets response code from ShootProof authorization, requests access token and stores it
     *
     * @Route("/authorize/shootproof/response", name="auth_shootproof_respose")
     *
     * @IsGranted("ROLE_USER")
     */
    public function spIntegrationResponse(EntityManagerInterface $em, Request $request)
    {
        // Get code and state from request
        $code = $request->query->get('code');
        // TODO Need to check this and be sure it's the same that was sent
        $state = $request->query->get('state');
        $userId = $this->getUser()->getId();

        $spAppCredentials = $this->
            getDoctrine()->
            getRepository(SpAppCredentials::class)->
            findOneBy([
                'id' => 1
            ]);

        $spIntegrationCredentials = $this->
            getDoctrine()->
            getRepository(SpIntegrationCredentials::class)->
            findOneBy([
                'userId' => $userId
            ]);

        // TODO Need to move the access_token request to another class
        // Build access token request
        $data = array(
            'grant_type' => 'authorization_code',
            'client_id' => $spAppCredentials->getClientId(),
            'code' => $code,
            'redirect_uri' => urlencode($spAppCredentials->getRedirectUri()),
            'scope' => $spAppCredentials->getScope()
        );

        $params = '';
        foreach($data as $key=>$value)
            $params .= $key.'='.$value.'&';

        $params = trim($params, '&');

        // Request tokens
        $_curl = curl_init();
        curl_setopt($_curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($_curl, CURLOPT_URL, $_ENV['BASE_SP_AUTH_URL'] . '/token' . '?' . $params);
        curl_setopt($_curl, CURLOPT_POST, 1);
        curl_setopt($_curl, CURLOPT_POSTFIELDS, '');
        $_response = json_decode(curl_exec($_curl));

        // Store token response in database
        $spIntegrationCredentials->setAccessToken($_response->access_token);
        $spIntegrationCredentials->setRefreshToken($_response->refresh_token);
        $spIntegrationCredentials->setExpiresIn($_response->expires_in);
        $spIntegrationCredentials->setTokenType($_response->token_type);
        $spIntegrationCredentials->setScope($_response->scope);
        $spIntegrationCredentials->setStat($_response->stat);

        $em->persist($spIntegrationCredentials);
        $em->flush();

        return $this->redirectToRoute('settings');
    }
}
