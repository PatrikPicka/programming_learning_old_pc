<?php

namespace App\Controller;

use App\Entity\Tasks;
use App\Entity\Note;
use App\Repository\TasksRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\HttpFoundation\Response;




class TaskController extends AbstractFOSRestController
{
    /**
     * @var tasksRepository
     */
    private $tasksRepository;
    /**
     * @var entityManager
     */
    private $entityManager;

    public function __construct(TasksRepository $tasksRepository, EntityManagerInterface $entityManager)
    {
        $this->tasksRepository = $tasksRepository;
        $this->entityManager = $entityManager;
    }


    /**
     * @Rest\Get("/tasks/{id}", name="getTask")
     * @param int $id
     */
    public function getTask(Tasks $task)
    {
        return $this->view($task, Response::HTTP_OK);
    }

    /**
     * @Rest\Delete("/tasks/{id}", name="DeleteTask")
     * @param int $id
     */
    public function delTask(Tasks $task)
    {
        if ($task) {
            $this->entityManager->remove($task);
            $this->entityManager->flush();


            return $this->view(null, Response::HTTP_NO_CONTENT);
        }
        return $this->view(["message" => "Something went wrong."], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @Rest\Patch("/tasks/{id}", name="TaskStatus")
     * @param int $id
     */
    public function TaskStatus(Tasks $task)
    {
        if ($task) {
            $task->setIsComplete(!$task->getIsComplete());
            $this->entityManager->persist($task);
            $this->entityManager->flush();


            return $this->view($task->getIsComplete(), Response::HTTP_OK);
        }
        return $this->view(["message" => "Something went wrong."], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @Rest\Get("/tasks/{id}/notes", name="GetNotes")
     * @param int $id
     */
    public function getNotes(Tasks $task)
    {
        if ($task) {
            $notes = $task->getNotes();
            return $this->view($notes, Response::HTTP_OK);
        }
        return $this->view(["message" => "Something went wrong when getting notes."], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @Rest\Post("/tasks/{id}/notes", name="PostNote")
     * @Rest\RequestParam(name="note", description="This is the not for the task", nullable=false)
     * @param Paramfetcher $paramFetcher
     * @param int $id
     */
    public function postNote(ParamFetcher $paramFetcher, Tasks $task)
    {
        $noteText = $paramFetcher->get("note");
        if ($task && $noteText !== "") {
            $note = new Note();

            $note->setNote($noteText);
            $note->setTask($task);

            $task->addNote($note);

            $this->entityManager->persist($note);
            $this->entityManager->flush();
            return $this->view($note, Response::HTTP_CREATED);
        }
        return $this->view(["message" => "There was an problem creating your note."], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
