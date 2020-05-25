<?php
namespace App\Controller;

use App\Service\RestApiService;
use Doctrine\ORM\EntityManagerInterface;
use Gedmo\SoftDeleteable\Filter\SoftDeleteableFilter;

class BaseRestController extends BaseController
{
    protected $restService;
    protected $apiSerializeGroup = "api";
    protected $em;
    protected $softDeleteablefilter;

    public function __construct(RestApiService $restApiService, EntityManagerInterface $em)
    {
        $this->restService = $restApiService;
        $this->restService->setSerializeGroup($this->apiSerializeGroup);
        $this->em = $em;

        /**
         * @var SoftDeleteableFilter $softDeleteablefilter
         */
        $this->softDeleteablefilter = $this->em->getFilters()->enable('softdeleteable');

        // If an exercise is deleted, we should be able to still see it when looking at past workouts
        // Disabled when managing exercises specifically
        $this->softDeleteablefilter->disableForEntity('App\Entity\Exercise');
    }
}

