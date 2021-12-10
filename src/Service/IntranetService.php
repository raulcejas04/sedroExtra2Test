<?php

namespace App\Service;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use GuzzleHttp;

class IntranetService
{

    private $client;
    private $parameterBag;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->client = new GuzzleHttp\Client();
        $this->parameterBag = $parameterBag;
    }

    public function postUser($username, $password, $email, $firstname, $lastname)
    {
        $base_uri = $this->parameterBag->get('intranet_app_url');
        $uri = $base_uri . '/api/add/user';
        $params = [
            'json' => [
                'username' => $username,
                'email' => $email,
                'firstname' => $firstname,
                'lastname' => $lastname,
                'password' => $password
            ]
        ];

        $res = $this->client->post($uri, $params);

        return $res;
    }

    public function postUserGroup($user, $groups)
    {
        $base_uri = $this->parameterBag->get('intranet_app_url');
        $uri = $base_uri . '/api/add/group/user';
        $params = [
            'json' => [
                'username' => $user->getUsername(),
                'groups' => $groups
            ]
        ];

        $res = $this->client->post($uri, $params);
        
        return $res;
    }

    public function getUserByUsername($username)
    {
        $base_uri = $this->parameterBag->get('intranet_app_url');
        $uri = $base_uri . '/api/get/user/username';
        $params = [
            'json' => [
                'username' => $username,
                'realm' => $this->parameterBag->get('keycloak_realm')
            ]
        ];

        $res = $this->client->get($uri, $params);
        return json_decode($res->getBody());
    }

    public function getUserByEmail($email)
    {
        $base_uri = $this->parameterBag->get('intranet_app_url');
        $uri = $base_uri . '/api/get/user/email';
        $params = [
            'json' => [
                'email' => $email,
                'realm' => $this->parameterBag->get('keycloak_realm')
            ]
        ];

        $res = $this->client->get($uri, $params);
        return json_decode($res->getBody());
    }

    public function getGroup($groupName)
    {
        $base_uri = $this->parameterBag->get('intranet_app_url');
        $uri = $base_uri . '/api/get/group';
        $params = [
            'json' => [
                'group' => $groupName,
                'realm' => $this->parameterBag->get('keycloak_realm')
            ]
        ];

        $res = $this->client->get($uri, $params);
        return json_decode($res->getBody());
    }

    public function getRole($roleName)
    {
        $base_uri = $this->parameterBag->get('intranet_app_url');
        $uri = $base_uri . '/api/get/role';
        $params = [
            'json' => [
                'role' => $roleName,
                'realm' => $this->parameterBag->get('keycloak_realm')
            ]
        ];

        $res = $this->client->get($uri, $params);
        return json_decode($res->getBody());
    }

}
