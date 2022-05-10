<?php

namespace App\Controller;

use App\Entity\TodoLists;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;

class PreferenceController extends AbstractFOSRestController
{

    private $entityManager;
    public function __construct(EntityManagerInterface $entityManagerInterface)
    {
        $this->entityManager = $entityManagerInterface;
    }

    /**
     * @Rest\Get("/preferences/{id}", name="GetPreferences")
     */
    public function getPreferences(TodoLists $list)
    {
        return $this->view($list->getPreferences(), Response::HTTP_OK);
    }

    /**
     * @Rest\Patch("/preferences/{id}/sort", name="SortPreferences")
     * @Rest\RequestParam(name="sortValue", description="Value will be used to sort the list", nullable=false)
     */
    public function sortPreferences(ParamFetcher $paramFetcher, TodoLists $list)
    {
        $sortVal = $paramFetcher->get("sortValue");

        if ($sortVal) {
            $list->getPreferences()->setSortValue($sortVal);
            $this->entityManager->persist($list);
            $this->entityManager->flush();
            return $this->view(null, Response::HTTP_NO_CONTENT);
        }
        $data['code'] = Response::HTTP_CONFLICT;
        $data['message'] = "The sortValue cannot be null";

        return $this->view($data, Response::HTTP_CONFLICT);
    }


    /**
     * @Rest\Patch("/preferences/{id}/filter", name="FilterPreferences")
     * @Rest\RequestParam(name="filterValue", description="The filter value")
     */
    public function filterPreferences(ParamFetcher $paramFetcher, TodoLists $list)
    {
        $filterValue = $paramFetcher->get('filterValue');
        if ($filterValue) {
            $list->getPreferences()->setFilterValue($filterValue);
            $this->entityManager->persist($list);
            $this->entityManager->flush();

            return $this->view(null, Response::HTTP_NO_CONTENT);
        }
        $data['code'] = Response::HTTP_CONFLICT;
        $data['message'] = "The filterValue cannot be null";

        return $this->view($data, Response::HTTP_CONFLICT);
    }
}
