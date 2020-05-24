<?php

namespace App\Security;

use App\Helper\AuthToken;
use App\Helper\JwtHelper;
use App\Repository\UserRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

class JwtAuthenticator extends AbstractGuardAuthenticator
{
    private $userRepository;
    private $user;
    private $logger;

    public function __construct(UserRepository $userRepository, LoggerInterface $logger)
    {
        $this->userRepository = $userRepository;
        $this->logger = $logger;
    }

    public function supports(Request $request)
    {
        return $request->cookies->get("jwt") ? true : false;
    }

    public function getCredentials(Request $request)
    {

        // Read the JWT and try to get the user's id
        $jwtToken  = $request->cookies->get("jwt");

        $verified = JwtHelper::verifyJWT($jwtToken);

        if($verified)
        {
            return $verified;
        }

        throw new CustomUserMessageAuthenticationException("Access Denied - 1");
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $startTime = microtime(true);
        $this->logger->info("Start get user");
        $endTime = microtime(true);
        $this->logger->info("End get user: " . ($endTime-$startTime));
        $this->logger->info($credentials);
        $user = $this->userRepository->findOneBy(['id' => $credentials]);
        $endTime = microtime(true);
        $this->logger->info("End get user: " . ($endTime-$startTime));

        if(!$user)
        {
            throw new CustomUserMessageAuthenticationException("Access Denied - 2");
        }


        $this->user = $user;
        return $this->user;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        // Credentials are checked in getCredentials
        return true;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $token = AuthToken::generate();
        return new JsonResponse([
            'result'  => false,
            'message' => $exception->getMessageKey(),
            'token' => $token
        ], Response::HTTP_UNAUTHORIZED);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        // Allow request to go through
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        $token = AuthToken::generate();
        return new JsonResponse([
            'result'  => false,
            'message' => 'Access Denied!!!!',
            'token' => $token
        ], Response::HTTP_UNAUTHORIZED);
    }

    public function supportsRememberMe()
    {
        return false;
    }
}
