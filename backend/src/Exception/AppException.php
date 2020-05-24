<?php
namespace App\Exception;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class AppException extends Exception
{
    public function getResponseCode()
    {
        return Response::HTTP_BAD_REQUEST;
    }
}
