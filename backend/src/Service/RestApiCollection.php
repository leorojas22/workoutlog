<?php
namespace App\Service;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class RestApiCollection
{
    private $repo;
    private $limit = 15;
    private $offset = 0;
    private $totalResults = null;
    private $criteria = [];
    private $orderBy = null;
    private $lastCollection = null;

    public function __construct(ServiceEntityRepository $collectionRepo)
    {
        $this->repo = $collectionRepo;
    }

    /**
     * @param array $criteria
     * @param array $orderBy
     * @return array|null
     */
    public function getCollection(array $criteria = [], array $orderBy = null): ?array
    {
        $this->criteria = $criteria;
        $this->orderBy = $orderBy;
        $this->totalResults = null;
        $this->lastCollection = $this->repo->findBy($criteria, $orderBy, $this->getLimit(), $this->getOffset());

        return $this->lastCollection;
    }

    public function getLastCollection()
    {
        if($this->lastCollection !== null)
        {
            return $this->lastCollection;
        }

        return $this->getCollection($this->getCriteria(), $this->getOrderBy());
    }

    /**
     * @param mixed $collection
     * @return RestApiCollection
     */
    public function setLastCollection($collection): RestApiCollection
    {
        $this->lastCollection = $collection;
        return $this;
    }

    /**
     * @param array $orderBy
     * @return RestApiCollection
     */
    public function setOrderBy(array $orderBy = null): RestApiCollection
    {
        $this->orderBy = $orderBy;
        return $this;
    }

    /**
     * @return array|null
     */
    public function getOrderBy(): ?array
    {
        return $this->orderBy;
    }

    /**
     * @param array $criteria
     * @return RestApiCollection
     */
    public function setCriteria(array $criteria): RestApiCollection
    {
        $this->criteria = $criteria;
        return $this;
    }

    /**
     * @return array|null
     */
    public function getCriteria(): ?array
    {
        return $this->criteria;
    }

    /**
     * @param integer $limit
     * @return RestApiCollection
     */
    public function setLimit(int $limit): RestApiCollection
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * @return integer|null
     */
    public function getLimit(): ?int
    {
        return $this->limit ?: null;
    }

    /**
     * @param integer $offset
     * @return RestApiCollection
     */
    public function setOffset(int $offset): RestApiCollection
    {
        $this->offset = $offset;
        return $this;
    }

    /**
     * @return integer|null
     */
    public function getOffset(): ?int
    {
        return $this->offset ?: null;
    }

    /**
     * @return integer
     */
    public function getTotalResults(): int
    {
        if($this->totalResults !== null)
        {
            return $this->totalResults;
        }

        $this->totalResults = (int) $this->repo->count($this->getCriteria());
        return $this->totalResults;
    }

    /**
     * @param integer $totalResults
     * @return RestApiCollection
     */
    public function setTotalResults(int $totalResults): RestApiCollection
    {
        $this->totalResults = $totalResults;
        return $this;
    }

    /**
     * @return array
     */
    public function getResponseArray(): array
    {
        $collection   = $this->getLastCollection();
        $totalResults = $this->getTotalResults();

        return [
            'pagination' => [
                'totalResults' => $totalResults,
                'pageCount'    => $this->getPageCount(),
                'currentPage'  => $this->getCurrentPage(),
                'nextPage'     => $this->getNextPage(),
            ],
            'collection' => $collection
        ];
    }

    /**
     * Gets the page count based on the limit and total results
     *
     * @return integer
     */
    public function getPageCount(): int
    {
        $limit = (int) $this->getLimit();
        return $limit > 0 ? ceil($this->getTotalResults() / $limit) : 1;
    }

    /**
     * Gets the current page number based off the limit and offset
     *
     * @return integer
     */
    public function getCurrentPage(): int
    {
        $limit  = (int) $this->getLimit();
        $offset = (int) $this->getOffset();

        return $limit > 0 ? round($offset / $limit) + 1 : 1;
    }

    /**
     * Sets the offset based on the page number provided
     *
     * @param integer $pageNumber
     * @return RestApiCollection
     */
    public function setPage(int $pageNumber): RestApiCollection
    {
        if($pageNumber < 1)
        {
            // Don't do anything if the page number is less than 1
            return $this;
        }

        // Figure out the offset based on the page number given

        $offset = ($pageNumber - 1) * (int) $this->getLimit();

        $this->setOffset($offset);
        return $this;
    }

    /**
     * Gets the next page number.  If there are no more pages to show, returns null
     *
     * @return integer|null
     */
    public function getNextPage(): ?int
    {
        if(!$this->getLimit())
        {
            return null;
        }

        $totalResults = $this->getTotalResults();
        $currentPage  = $this->getCurrentPage();
        $totalShownSoFar = $currentPage * $this->getLimit();
        return $totalResults > $totalShownSoFar ? ($currentPage + 1) : null;
    }



}
