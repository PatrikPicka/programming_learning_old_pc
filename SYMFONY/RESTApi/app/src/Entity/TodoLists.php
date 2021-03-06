<?php

namespace App\Entity;

use App\Repository\TodoListsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TodoListsRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class TodoLists
{
    use Timestamps;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, options={"default"="background.png"}, nullable=true)
     */
    private $background;

    /**
     * @ORM\Column(type="string", length=255, options={"default"="background.png"}, nullable=true)
     */
    private $backgroundPath;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Tasks", mappedBy="list", cascade={"REMOVE"})
     */
    private $tasks;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="lists")
     */
    private $user;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Preference", mappedBy="list", cascade={"PERSIST", "REMOVE"})
     */
    private $preferences;

    public function __construct()
    {
        $this->tasks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getBackground(): ?string
    {
        return $this->background;
    }

    public function setBackground(string $background): self
    {
        $this->background = $background;

        return $this;
    }

    public function getBackgroundPath(): ?string
    {
        return $this->backgroundPath;
    }

    public function setBackgroundPath(string $backgroundPath): self
    {
        $this->backgroundPath = $backgroundPath;

        return $this;
    }

    /**
     * @return Collection|Tasks[]
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function addTask(Tasks $task): self
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks[] = $task;
            $task->setList($this);
        }

        return $this;
    }

    public function removeTask(Tasks $task): self
    {
        if ($this->tasks->removeElement($task)) {
            // set the owning side to null (unless already changed)
            if ($task->getList() === $this) {
                $task->setList(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getPreferences(): ?Preference
    {
        return $this->preferences;
    }

    public function setPreferences(?Preference $preferences): self
    {
        // unset the owning side of the relation if necessary
        if ($preferences === null && $this->preferences !== null) {
            $this->preferences->setList(null);
        }

        // set the owning side of the relation if necessary
        if ($preferences !== null && $preferences->getList() !== $this) {
            $preferences->setList($this);
        }

        $this->preferences = $preferences;

        return $this;
    }
}
