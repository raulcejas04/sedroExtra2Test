<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use GuzzleHttp;

/**
 * Sumario controller.
 */
class KeycloakFullApiController extends AbstractController
{
	private $client;
	protected $container;
	
	//public function __construct(ContainerInterface $container){
    public function __construct(){
		//$this->container = $container;
		$this->client = new GuzzleHttp\Client();
		
	}

	public function updateUsuario( $username, $modif )
	{
		// Testing created user: http://localhost:8180/auth/realms/Testkeycloak/account
		// Testing http trafic sudo tcpflow -i any -C port 8180 (https://www.it-swarm-es.com/es/linux/cual-es-la-forma-mas-facil-de-detectar-tcp-datos-de-trafico-en-linux/957498336/ )
		// example creating user https://www.appsdeveloperblog.com/keycloak-rest-api-create-a-new-user/
	
	
		$user=$this->getUserByUsername( $username );
		$token = $this->getTokenAdmin();
		$base_uri_keycloak = $this->getParameter('keycloak-server-url');
		$uri = $base_uri_keycloak.'/admin/realms/{realm}/users/{user_id}';
		$realm=$this->getParameter('keycloak_realm');
		$uri = str_replace("{realm}", $realm, $uri);
		$uri = str_replace("{user_id}", $user[0]->id, $uri);


		$params = [
				'headers' => [
				'Content-Type' => 'application/json',
				'Authorization' => "Bearer ".$token->access_token],
				'debug'=>true,
				'json' => $modif
		        ];
		        
	//dd($params);
		
		$res = $this->client->put($uri, $params);

		
		$data = json_decode($res->getBody());
		dd($data);		
		return $data;
	}
	
	/**
	 * POST /realms/master/protocol/openid-connect/token
	 */
	public function getTokenAdmin(){
		$base_uri_keycloak = $this->getParameter('keycloak-server-url');
		$uri = $base_uri_keycloak.'/realms/master/protocol/openid-connect/token';
		$parametros = [
				'form_params' => [
						'username' => $this->getParameter('keycloak_admin_username'),
						'password' => $this->getParameter('keycloak_admin_password'),
						'grant_type' => $this->getParameter('keycloak_admin_grant_type'),
						'client_id' => $this->getParameter('keycloak_admin_client_id'),
						'client_secret'=> $this->getParameter('Keycloak_admin_client_secret'),
				]
		];
		//dd($parametros);
		$res = $this->client->post($uri, $parametros);
		$data = json_decode($res->getBody());
		return $data;
	}
	
	/**
	 * POST /admin/realms/{realm}/users/{id}/logout
	 */
	public function logout($usuario_id){
		$token = $this->get('keycloak_api')->getTokenAdmin();
		$base_uri_keycloak = $this->getParameter('keycloak-server-url');
		$realm=$this->getParameter('keycloak-realm');
		$uri = $base_uri_keycloak.'/admin/realms/{realm}/users/{id}/logout';
		$uri = str_replace("{realm}", $realm, $uri);
		$uri = str_replace("{id}", $usuario_id, $uri);
		
		$params = ['headers' => ['Authorization' => "Bearer ".$token->access_token]];
		
		$res = $this->client->post($uri, $params);
		$data = json_decode($res->getBody());
	}
	
	/**
	 * List one user in the realm by username
	 * GET /admin/realms/{realm}/users
	 */
	public function getUserByUsername( $username ){
		$token = $this->getTokenAdmin();
		
		$auth_url = $this->getParameter('keycloak-server-url');
		$uri = $auth_url . "/admin/realms/{realm}/users";
		$realm=$this->getParameter('keycloak-realm');
		$uri = str_replace("{realm}", $realm, $uri);
		$uri = $uri."?username=".$username;
		
		$params = ['headers' => ['Authorization' => "Bearer ".$token->access_token]
		];
		//echo $uri."<br>";
		$res = $this->client->get($uri, $params);
		$data = json_decode($res->getBody());
		
		return $data;
	}

	/**
	 * List one user in the realm by username and realm
	 * GET /admin/realms/{realm}/users
	 */
	public function getUserByUsernameAndRealm( $username, $realm){
		$token = $this->getTokenAdmin();

		$auth_url = $this->getParameter('keycloak-server-url');
		$uri = $auth_url . "/admin/realms/{realm}/users";
		//$realm=$this->getParameter('keycloak-realm');

		$uri = str_replace("{realm}", $realm, $uri);
		$uri = $uri."?username=".$username;
		
		$params = ['headers' => ['Authorization' => "Bearer ".$token->access_token]
		];
		//echo $uri."<br>";
		$res = $this->client->get($uri, $params);
		$data = json_decode($res->getBody());
		return new JsonResponse($data[0]);
	}

	public function getUserByIdAndRealm($id, $realm){
		$token = $this->getTokenAdmin();
	
		$auth_url = $this->getParameter('keycloak-server-url');
		$uri = $auth_url . "/admin/realms/{realm}/users/{id}";		
		$uri = str_replace("{realm}", $realm, $uri);
		$uri = str_replace("{id}", $id, $uri);
	
		$params = ['headers' => ['Authorization' => "Bearer ".$token->access_token]];
	
		$res = $this->client->get($uri, $params);
		$data = json_decode($res->getBody());
	
		return $data;
	}

	/**
	 * GET /admin/realms/{realm}/users/{id}
	 */
	public function getUserById($id){
		$token = $this->getTokenAdmin();
	
		$auth_url = $this->getParameter('keycloak-server-url');
		$uri = $auth_url . "/admin/realms/{realm}/users/{id}";
		$realm=$this->getParameter('keycloak-realm');
		$uri = str_replace("{realm}", $realm, $uri);
		$uri = str_replace("{id}", $id, $uri);
	
		$params = ['headers' => ['Authorization' => "Bearer ".$token->access_token]];
	
		$res = $this->client->get($uri, $params);
		$data = json_decode($res->getBody());
	
		return $data;
	}

	public function resetPasswordUser( $id, $realm, $password )
	{
		$token = $this->getTokenAdmin();
		
		$base_uri_keycloak = $this->getParameter('keycloak-server-url');
		$uri = $base_uri_keycloak.'/admin/realms/{realm}/users/{user_id}';
		$uri = str_replace("{realm}", $realm, $uri);
		$uri = str_replace("{user_id}", $id, $uri);

		$params = [
			'headers' => [
			'Content-Type' => 'application/json',
			'Authorization' => "Bearer ".$token->access_token],
			'debug'=>true,
			'json' => [					
				'credentials' => 
						[ 0 => [
							'type'=>'password',
							'value'=>$password,
							'temporary'=>true
						]
					]
				]
		];
		
		$res = $this->client->put($uri, $params);
		$statusCode = $res->getStatusCode();
		$usuario = $this->getUserByIdAndRealm($id, $realm);
		//$data = json_decode($res->getBody());
		$data = array('statusCode'=>$statusCode, 'usuario'=>$usuario);
		return new JsonResponse($data);
	}

	public function changeUserPassword( $id, $realm, $password )
	{
		$token = $this->getTokenAdmin();
		
		$base_uri_keycloak = $this->getParameter('keycloak-server-url');
		$uri = $base_uri_keycloak.'/admin/realms/{realm}/users/{user_id}';
		$uri = str_replace("{realm}", $realm, $uri);
		$uri = str_replace("{user_id}", $id, $uri);

		$params = [
			'headers' => [
			'Content-Type' => 'application/json',
			'Authorization' => "Bearer ".$token->access_token],
			'debug'=>true,
			'json' => [					
				'credentials' => 
						[ 0 => [
							'type'=>'password',
							'value'=>$password,
							'temporary'=>false
						]
					]
				]
		];
		
		$res = $this->client->put($uri, $params);
		
		$data = json_decode($res->getStatusCode());
		return new JsonResponse($data);
	}
}