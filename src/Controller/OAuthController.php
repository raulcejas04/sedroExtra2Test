<?php

namespace App\Controller;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Client\Provider\KeycloakClient;
use App\Security\KeycloakAuthenticator;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;

use GuzzleHttp;

class OAuthController extends AbstractController
{
    /**
     * @Route("/oauth/login", name="oauth_login")
     */
    public function index(ClientRegistry $clientRegistry): RedirectResponse
    {
        /** @var KeycloakClient $client */
        //$client = $clientRegistry->getClient('keycloak');
        $client = $clientRegistry->getClient('keycloak');
        return $client->redirect();
    }

    public function logout2() : void
    {
       throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall');
    }

    /**
     * @Route("/oauth/logout", name="oauth_logout", methods={"GET"})
     */
    public function logout( Request $request,  Security $security ): RedirectResponse //void
    {
        $session = new Session();
        $refreshToken=$session->get('refreshToken' );
        $token=$session->get('token' );
        
        $keycloak_url=$this->getParameter('keycloak_url');
        $keycloak_clientid=$this->getParameter('keycloak_clientid');
        $keycloak_secret=$this->getParameter('keycloak_secret');
        $keycloak_realm=$this->getParameter('keycloak_realm');

	if( isset($token) and isset($refreshToken))
	{      
        $uri=$keycloak_url."/realms/".$keycloak_realm."/protocol/openid-connect/logout";
	$params = ['headers' => ['Authorization' => "Bearer ".$token],
					'form_params' => [
						'client_id' => $keycloak_clientid,
						'client_secret' => $keycloak_secret,
						'refresh_token' => $refreshToken
				]];    

	$client = new GuzzleHttp\Client();
	$res = $client->post($uri, $params);
	$data = json_decode($res->getBody());
	}
	
	//$user = $security->getUser();
	//dd($user);
        
        //https://stackoverflow.com/questions/61087025/symfony-5-0-5-custom-logout-multiple-logout-manually
        $this->get('security.token_storage')->setToken(null);
        $this->get('session')->invalidate();
        
        return $this->redirectToRoute('dashboard');
    }


    /**
     * @Route("/oauth/callback", name="oauth_check")
     */
    public function check()
    {
    }
}
