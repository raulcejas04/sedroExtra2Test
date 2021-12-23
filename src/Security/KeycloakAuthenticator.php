<?php

namespace App\Security;

use App\Entity\Realm;
use App\Entity\User;
use App\Entity\UserGrupo;
use App\Service\IntranetService;
use KnpU\OAuth2ClientBundle\Security\Authenticator\SocialAuthenticator;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class KeycloakAuthenticator
 */
class KeycloakAuthenticator extends SocialAuthenticator
{

    private $clientRegistry;
    private $em;
    private $router;
    private $intranetService;
    private $parameterBag;

    public function __construct(ClientRegistry $clientRegistry, EntityManagerInterface $em, RouterInterface $router, IntranetService $service, ParameterBagInterface $pb)
    {
        $this->clientRegistry = $clientRegistry;
        $this->em = $em;
        $this->router = $router;
        $this->intranetService = $service;
        $this->parameterBag = $pb;
    }

    public function start(Request $request, \Symfony\Component\Security\Core\Exception\AuthenticationException $authException = null)
    {
        return new RedirectResponse(
            '/oauth/login', // might be the site, where users choose their oauth provider
            Response::HTTP_TEMPORARY_REDIRECT
        );
    }

    public function supports(Request $request)
    {
        return $request->attributes->get('_route') === 'oauth_check';
    }

    public function getCredentials(Request $request)
    {
        $credentials = $this->fetchAccessToken($this->getKeycloakClient());
        $refreshToken = $credentials->getRefreshToken();
        $token = $credentials->getToken();
        $session = new Session();
        $session->set('refreshToken', $refreshToken);
        $session->set('token', $token);

        return $credentials;
    }

    public function getUser($credentials, \Symfony\Component\Security\Core\User\UserProviderInterface $userProvider)
    {
        //$client = $this->clientRegistry->getClient('keycloak');
        //$client = $this->getKeycloakClient();

        $token = $credentials->getToken(); //esto anda, pero no me deja sacar el refreshtoken
        $expires = $credentials->getExpires();
        //dd(date('Y-m-d H:i:s',$expires));

        $keycloakUser = $this->getKeycloakClient()->fetchUserFromToken($credentials);
        $data = $keycloakUser->toArray();
        //dd($keycloakUser);
        //dd($keycloakUser->toArray()['preferred_username']);
        //existing user ?
        $user = $this
            ->em
            ->getRepository(User::class)
            ->findOneBy(['KeycloakId' => $keycloakUser->getId()]);

        if ($user) {
            if (array_key_exists("roles", $data)) {
                $user->setRoles($data["roles"]);
            }
        } else {
            return null;
        }

        $realm = $this->em->getRepository(Realm::class)->findOneBy(["realm"=>$this->parameterBag->get('keycloak_realm')]);

        if(!$realm){
            return null;
        }

        /** GRUPO Y ROLES * */
        if (array_key_exists("groups", $data)) {
            $userGroups = $this->em->getRepository(\App\Entity\UserGrupo::class)->findBy([
                "usuario" => $user
            ]);

            foreach ($userGroups as $grupoUsuario) {
                $this->em->remove($grupoUsuario);
                $this->em->flush();
            }
            foreach ($data["groups"] as $group) {
                $res = $this->intranetService->getGroup($group, $this->parameterBag->get('keycloak_realm'));
                $existingGroup = $this->em->getRepository(\App\Entity\Grupo::class)->findOneBy(["KeycloakGroupId" => $res->id]);
                $g = $existingGroup ? $existingGroup : new \App\Entity\Grupo();
                $g->setRealm($realm);
                $g->setKeycloakGroupId($res->id);
                $g->setNombre($res->name);

                $userGroup = new UserGrupo();
                $userGroup->setUsuario($user);
                $userGroup->setGrupo($g);
                $g->addGroupUser($userGroup);

                $this->em->persist($g);
                $this->em->flush();

            }
        }

        if (array_key_exists("roles", $data)) {
            $userRoles = $this->em->getRepository(\App\Entity\UserRole::class)->findBy([
                "Usuario" => $user
            ]);
            foreach ($userRoles as $userRole) {
                $this->em->remove($userRole);
                $this->em->flush();
            }

            foreach ($data["roles"] as $role) {
                //Se remueve el prefijo para acertar la búsqueda, debe ser igual al que está en el mapper del cliente en keycloak
                $res = $this->intranetService->getRole(str_replace("ROLE_", "", $role));
                $existingRole = $this->em->getRepository(\App\Entity\Role::class)->findOneBy(["keycloakRoleId" => $res->id]);
                $r = $existingRole ? $existingRole : new \App\Entity\Role();
                $r->setRealm($realm);
                $r->setKeycloakRoleId($res->id);
                $r->setCode("ROLE_" . $res->name);
                //TODO: Traer la descripción del role (puede sacarse de atributos, pero quiero ver si hay una forma directa)
                $r->setName($res->name);

                $userRole = new \App\Entity\UserRole();
                $userRole->setRole($r);
                $userRole->setUsuario($user);
                $r->addUserRole($userRole);

                $this->em->persist($r);
                $this->em->flush();
            }
        }
        /** FIN GRUPO Y ROLES * */
        return $user;
    }

    public function onAuthenticationFailure(Request $request, \Symfony\Component\Security\Core\Exception\AuthenticationException $exception)
    {
        return new RedirectResponse(
            $this->router->generate('account_failure')
        );
    }

    public function onAuthenticationSuccess(Request $request, \Symfony\Component\Security\Core\Authentication\Token\TokenInterface $token, string $providerKey)
    {
        //dd($token);
        // change "app_homepage" to some route in your app
        $targetUrl = $this->router->generate('dashboard');

        return new RedirectResponse($targetUrl);
    }

    /**
     * @return \KnpU\OAuth2ClientBundle\Client\Provider\KeycloakClient
     */
    private function getKeycloakClient()
    {
        return $this->clientRegistry->getClient('keycloak');
    }
}
