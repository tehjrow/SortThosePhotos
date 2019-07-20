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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class SettingsController
 * @package App\Controller
 *
 * Handle settings resource
 */
class SettingsController extends AbstractController
{
    /**
     * @Route("/settings", name="settings")
     *
     * @IsGranted("ROLE_USER")
     *
     * Return settings and settings view
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
     * @Route("/authorize/shootproof", name="auth_shootproof")
     *
     * @IsGranted("ROLE_USER")
     *
     * Redirect to ShootProof authorization page
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

        return $this->redirect($_ENV['BASE_SP_AUTH_URL'] . '/new' . '?' . $params);
    }

    /**
     * @Route("/authorize/shootproof/response", name="auth_shootproof_respose")
     *
     * @IsGranted("ROLE_USER")
     *
     * Gets response code from ShootProof authorization, requests access token and stores it
     */
    public function spIntegrationResponse(Request $request)
    {
        // TODO break this function up
        $code = $request->query->get('code');
        $userId = $this->getUser()->getId();


        // TODO Need to check this and be sure it's the same that was sent
        $state = $request->query->get('state');

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

        $_curl = curl_init();
        curl_setopt($_curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($_curl, CURLOPT_URL, $_ENV['BASE_SP_AUTH_URL'] . '/token' . '?' . $params);
        curl_setopt($_curl, CURLOPT_POST, 1);
        curl_setopt($_curl, CURLOPT_POSTFIELDS, '');
        $_response = json_decode(curl_exec($_curl));

        $spIntegrationCredentials->setAccessToken($_response->access_token);
        $spIntegrationCredentials->setRefreshToken($_response->refresh_token);
        $spIntegrationCredentials->setExpiresIn($_response->expires_in);
        $spIntegrationCredentials->setTokenType($_response->token_type);
        $spIntegrationCredentials->setScope($_response->scope);
        $spIntegrationCredentials->setStat($_response->stat);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($spIntegrationCredentials);
        $entityManager->flush();

        return $this->redirectToRoute('settings');
    }
}
