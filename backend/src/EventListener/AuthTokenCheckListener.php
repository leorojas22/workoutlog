<?php
namespace App\EventListener;

use App\Exception\InvalidAuthTokenException;
use App\Helper\AuthToken;
use Symfony\Component\HttpKernel\Event\ControllerArgumentsEvent;

class AuthTokenCheckListener
{
    public function onKernelControllerArguments(ControllerArgumentsEvent $event)
    {
        $request = $event->getRequest();
        AuthToken::verifyRequest($request);
    }
}
