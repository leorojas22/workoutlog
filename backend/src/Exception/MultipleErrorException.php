<?php
namespace App\Exception;

use Exception;

class MultipleErrorException extends AppException
{
    public function __construct($message = null, $code = 0, Exception $previous = null)
    {
        parent::__construct(json_encode($message), $code, $previous);
    }

    public function getMessages()
    {
        return json_decode($this->getMessage());
    }
}
