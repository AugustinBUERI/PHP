<?php

namespace App\Security;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class Authenticator extends AbstractGuardAuthenticator
{
    private $passwordEncoder;
    private $entityManager;
    private $urlGenerator;
    private $csrfTokenManager;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->entityManager = $entityManager;

       
    }


    public function supports(Request $request)
    {
        return 'user_login' === $request->attributes->get('_route')
        && $request->isMethod('POST');
    }

    public function getCredentials(Request $request)
    {
        // todo
        return $credentials = [
            'login' => $request->request->get('login'),
            'password' => $request->request->get('password'),
            'csrf_token' => $request->request->get('_csrf_token'),
        ];
        $request->getSession()->set(
            Security::LAST_USERNAME,
            $credentials['login']
        );
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        // todo
       
        return $this->passwordEncoder->isPasswordValid($user, $credentials['password']);
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        // todo
        return $this->entityManager->getRepository(User::class)->findOneBy(["login"=> $credentials['login']]);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey)
    {
        
        //var_dump($request);   
        return new JsonResponse([
            'message' => 401 ,$exception->getMessageKey()
        ], 201);
         
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        // todo
        return new JsonResponse([
            'message' => 401 ,$exception->getMessageKey()
        ], 401);
    }

    public function supportsRememberMe()
    {
        // todo
        return false;
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        // todo
        $data = [
            // you might translate this message
            'message' => 'An authentication is required'
        ];
        return new JsonResponse($data, Response::AUTHORIZED);
    }
}
