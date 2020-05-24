<?php

namespace App\Security;

use App\Helper\AuthToken;
use App\Helper\JwtHelper;
use App\Exception\AppException;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

class LoginAuthenticator extends AbstractGuardAuthenticator
{

    private $userRepository;
    private $user;
    private $passwordEncoder;

    public function __construct(UserRepository $userRepository, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->userRepository  = $userRepository;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function supports(Request $request)
    {
        return $request->attributes->get("_route") === "app_login" && $request->isMethod("POST");
    }

    public function getCredentials(Request $request)
    {
        // Before logging in, check to make sure there's a valid auth token
        AuthToken::verifyRequest($request);

        $postedData = json_decode($request->getContent(), true);
        return [
            'email' => $postedData['email'] ?? "",
            'password' => $postedData['password'] ?? ""
        ];
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $this->user = $this->userRepository->findOneBy(['email' => $credentials['email']]);
        if(!$this->user)
        {
            throw new AppException("Access Denied!?");
        }

        return $this->user;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return $this->passwordEncoder->isPasswordValid($user, $credentials['password']);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new JsonResponse([
            'result' => false,
            'message' => $exception->getMessageKey()
        ], Response::HTTP_UNAUTHORIZED);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        // Generate auth token
        JwtHelper::applyJWTForUser($this->user);

        // Return response with the auth token
        return new JsonResponse([
            'id' => $this->user->getId(),
            'email' => $this->user->getEmail()
        ]);
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new JsonResponse([
            'result' => false,
            'message' => 'Access Denied?'
        ], Response::HTTP_UNAUTHORIZED);
    }

    public function supportsRememberMe()
    {
        return false;
    }
}
