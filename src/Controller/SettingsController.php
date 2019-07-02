<?php

namespace App\Controller;

use App\Entity\SpAppCredentials;
use App\Entity\SpIntegrationCredentials;
use App\Models\ShootProof\spStudio;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;

class SettingsController extends AbstractController
{
    /**
     * @Route("/settings", name="settings")
     *
     * @IsGranted("ROLE_USER")
     */
    public function index()
    {
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
     */
    public function enableSpIntegration()
    {
        $spAppCredentials = $this->
            getDoctrine()->
            getRepository(SpAppCredentials::class)->
            findOneBy([
                'id' => 1
            ]);
        
        $data = array (
            'response_type' => $spAppCredentials->getResponseType(),
            'client_id' => $spAppCredentials->getClientId(),
            'redirect_uri' =>$spAppCredentials->getRedirectUri(),
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
     */
    public function spIntegrationResponse(Request $request)
    {
        $code = $request->query->get('code');
        $state = $request->query->get('state');

        return $this->json([
            'code' => $code,
            'state' => $state
        ]);
    }
}
