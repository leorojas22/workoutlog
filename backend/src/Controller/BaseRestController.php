<?php
namespace App\Controller;

use App\Service\RestApiService;

class BaseRestController extends BaseController
{
    protected $restService;
    protected $apiSerializeGroup = "api";

    public function __construct(RestApiService $restApiService)
    {
        $this->restService = $restApiService;
        $this->restService->setSerializeGroup($this->apiSerializeGroup);
    }
}

