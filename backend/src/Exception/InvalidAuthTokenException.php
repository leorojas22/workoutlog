<?php
namespace App\Exception;

class InvalidAuthTokenException extends AppException
{
    protected $message = "Invalid authentication token provided.";
}
