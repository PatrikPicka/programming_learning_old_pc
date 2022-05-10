<?php

namespace App\Controller;

use App\Entity\Note;
use App\Repository\NoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;


class NoteController extends AbstractFOSRestController
{
    /**
     * @var NoteRepository
     */
    private $noteRepository;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    public function __construct(NoteRepository $noteRepository, EntityManagerInterface $entityManager)
    {
        $this->noteRepository = $noteRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Rest\Get("/note/{id}", name="getNote")
     * @param int $id
     */
    public function getNote(Note $note)
    {
        return $this->view($note, Response::HTTP_OK);
    }
    /**
     * @Rest\Delete("/note/{id}", name="DeleteNote")
     * @param int $id
     */
    public function deleteNote(Note $note)
    {
        $this->entityManager->remove($note);
        $this->entityManager->flush();

        return $this->view(null, Response::HTTP_NO_CONTENT);
    }
}
