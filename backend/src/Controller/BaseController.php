<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

abstract class BaseController extends AbstractController
{
    protected function api(array $responseData = [], $responseCode = Response::HTTP_OK)
    {
        return $this->json($responseData, $responseCode, [], [
            'groups' => 'api'
        ]);
    }
}
