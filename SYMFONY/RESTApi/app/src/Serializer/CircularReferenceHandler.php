<?php

namespace App\Serializer;

use App\Entity\Note;
use App\Entity\Tasks;
use App\Entity\TodoLists;
use Symfony\Component\Routing\RouterInterface;

class CircularReferenceHandler
{
    /**
     * @var $routerInterface
     */
    private $routerInterface;

    public function __construct(RouterInterface $routerInterface)
    {
        $this->routerInterface = $routerInterface;
    }

    public function __invoke($object)
    {
        switch ($object) {
            case $object instanceof TodoLists:
                return $this->routerInterface->generate('getListById', ['id' => $object->getId()]);
            case $object instanceof Tasks:
                return $this->routerInterface->generate('getTask', ['id' => $object->getId()]);
            case $object instanceof Note:
                return $this->routerInterface->generate('getNote', ['id' => $object->getId()]);
        }
        return $object->getId();
    }
}
