<?php
namespace App\EventListener;

use App\Exception\AppException;
use App\Exception\MultipleErrorException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();

        $multipleErrors = [];
        $message      = $exception instanceof AppException ? $exception->getMessage() : "An error occurred while processing your request.";
        $responseCode = $exception instanceof AppException ? $exception->getResponseCode() : Response::HTTP_BAD_REQUEST;

        if($exception instanceof MultipleErrorException)
        {
            $multipleErrors = $exception->getMessages();
            $message = "Please correct the following errors.";
        }
        elseif($exception instanceof NotFoundHttpException)
        {
            $message = "Not found.";
            $responseCode = Response::HTTP_NOT_FOUND;
        }


        $responseArray = [
            'result'  => false,
            'message' => $message,
            'errors'  => $multipleErrors,
            'env' => getenv("APP_ENV")
        ];


        if(getenv("APP_ENV") === "dev")
        {
            $responseArray['debug'] = $exception->getMessage();
        }

        $response = new JsonResponse($responseArray, $responseCode);

        $event->setResponse($response);
    }
}
