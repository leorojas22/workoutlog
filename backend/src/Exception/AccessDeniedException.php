<?php
namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;

class AccessDeniedException extends AppException
{
    protected $message = "Access Denied.";

    public function getResponseCode()
    {
        return Response::HTTP_UNAUTHORIZED;
    }
}
