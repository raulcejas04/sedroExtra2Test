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
        $client = new GuzzleHttp\Client();

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

        $res =$client->post($uri, $params);

        return $res;
    }
}
