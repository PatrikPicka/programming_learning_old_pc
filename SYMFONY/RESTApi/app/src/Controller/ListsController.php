<?php

namespace App\Controller;

use App\Entity\Preference;
use App\Entity\Tasks;
use App\Entity\TodoLists;
use App\Repository\TodoListsRepository;
use App\Repository\TasksRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\UserRepository;


class ListsController extends AbstractFOSRestController
{
    /**
     * @var todoListRepository
     */
    private $todoListsRepository;
    /**
     * @var entityManager
     */
    private $entityManager;

    private $userRepository;

    public function __construct(TodoListsRepository $TodoListsRepository, EntityManagerInterface $entityManagerInterface, UserRepository $userRepository)
    {
        $this->todoListsRepository = $TodoListsRepository;
        $this->entityManager = $entityManagerInterface;
        $this->userRepository = $userRepository;
    }
    /**
     * @Rest\Get("/list", name="getLists")
     */
    public function getLists()
    {

        $user = $this->userRepository->find($_COOKIE["USER_ID"]);
        $lists = $user->getLists();
        //$data = $this->todoListsRepository->findBy(array("user_id" => 2));
        return $this->view($lists, statusCode: Response::HTTP_OK);
    }

    /**
     * @Rest\Post("/list", name="PostList")
     * @Rest\RequestParam(name="title", description="Title of the list", nullable=false)
     * @param ParamFetcher $paramFetcher
     * @return \FOS\RestBundle\View\View
     */
    public function postList(ParamFetcher $paramFetcher)
    {
        $title = $paramFetcher->get(name: 'title');
        if ($title) {

            $user = $this->userRepository->find($_COOKIE["USER_ID"]);


            $list = new TodoLists();

            $preferences = new Preference();
            $preferences->setList($list);
            $list->setPreferences($preferences);

            $list->setTitle($title);
            $list->setUser($user);
            $user->addList($list);

            $this->entityManager->persist($list);
            $this->entityManager->flush();
            return $this->view($list, Response::HTTP_CREATED);
        }
        return $this->view(["error" => "Something went wrong..."], Response::HTTP_BAD_REQUEST);
    }

    /**
     * @Rest\Get("/list/{id}", name="getListById")
     */
    public function getList(int $id)
    {
        $data = $this->todoListsRepository->findBy(["id" => $id]);
        if ($data) {
            return $this->view($data, Response::HTTP_OK);
        }
        return $this->view(["error" => "Can´t find any post with this Id"], Response::HTTP_BAD_REQUEST);
    }

    //Use post for testing in use change method to PATCH.

    /**
     * @Rest\Post("/insertBg/{id}", name="insertBgToList")
     * @Rest\FileParam(name="file", description="The background of the lists", nullable=false, image=true)
     * @param ParamFethcer $paramFetcher
     * @param int $id
     */
    public function bgLists(Request $request, ParamFetcher $paramFetcher, TodoLists $list)
    {
        //If exists old file remove it
        $currentBg = $list->getBackground();
        if (!is_null($currentBg)) {
            $filesystem = new Filesystem();
            $filesystem->remove(
                $this->getUploadsDir() . $currentBg
            );
        }
        /**
         * @var UploadedFile $file
         */
        $file = $paramFetcher->get('file');
        if ($file) {
            $filename = md5(uniqid()) . '.' . $file->guessClientExtension();

            $file->move(
                $this->getUploadsDir(),
                $filename
            );
            $list->setBackground($filename);
            $list->setBackgroundPath('/uploads/' . $filename);
            $this->entityManager->persist($list);
            $this->entityManager->flush();

            $data = $request->getUriForPath(
                $list->getBackgroundPath()
            );
            return $this->view($data, Response::HTTP_OK);
        }


        return $this->view(["message" => "Something went wrong with uplouding of your file."], Response::HTTP_BAD_REQUEST);
    }

    /**
     * @Rest\Get("/list/{id}/task", name="GetListsTasks")
     * @param int $id
     */
    public function getTaks(TodoLists $list)
    {
        return $this->view($list->getTasks(), Response::HTTP_OK);
    }

    /**
     * @Rest\Delete("/list/{id}", name="DeleteList")
     * @param int $id
     */
    public function delList(TodoLists $list)
    {
        if ($list) {
            $this->entityManager->remove($list);
            $this->entityManager->flush();
            return $this->view(null, Response::HTTP_NO_CONTENT);
        }

        return $this->view(["message" => "Something went wrong."], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @Rest\Patch("/list/{id}", name="UpdateListTitle")
     * @Rest\RequestParam(name="title", description="New title for the list", nullable=false)
     * @param ParamFetcher $paramFetcher
     * @param int $id
     * @return \FOS\RestBundle\View\View
     */
    public function updateListTitle(ParamFetcher $paramFetcher, TodoLists $list)
    {
        $errors = [];
        $title = $paramFetcher->get('title');
        if (trim($title) !== '') {
            if ($list) {
                $list->setTitle($title);
                $this->entityManager->persist($list);
                $this->entityManager->flush();

                return $this->view(null, Response::HTTP_NO_CONTENT);
            }
            $errors[] = [
                "list" => "List with this id was not found"
            ];
        }
        $errors[] = ["title" => "The title cannot be blank."];
        return $this->view("There was a problem updating your title - " . $errors, Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @Rest\Post("/list/{id}/task", name="PostTask")
     * @Rest\RequestParam(name="title", description="Title for the task", nullable=false)
     * @param ParamFetcher $paramFetcher
     * @param int $id
     */
    public function postTask(TasksRepository $tasksRepository, ParamFetcher $paramFetcher, TodoLists $list)
    {
        if ($list) {
            $title = $paramFetcher->get("title");

            $task = new Tasks();

            $task->setTitle($title);
            $task->setList($list);
            $list->addTask($task);


            $this->entityManager->persist($task);
            $this->entityManager->flush();



            return $this->view($task, Response::HTTP_CREATED);
        }
        return $this->view(["message" => "Something went wrong."], Response::HTTP_INTERNAL_SERVER_ERROR);
    }


    private function getUploadsDir()
    {
        return $this->getParameter('uploads_dir');
    }
}
