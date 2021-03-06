<?php

namespace App\Entity;

use App\Repository\PreferenceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PreferenceRepository::class)
 */
class Preference
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $sortValue;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $filterValue;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\TodoLists", inversedBy="preferences")
     */
    private $list;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSortValue(): ?string
    {
        return $this->sortValue;
    }

    public function setSortValue(?string $sortValue): self
    {
        $this->sortValue = $sortValue;

        return $this;
    }

    public function getFilterValue(): ?string
    {
        return $this->filterValue;
    }

    public function setFilterValue(?string $filterValue): self
    {
        $this->filterValue = $filterValue;

        return $this;
    }

    public function getList(): ?TodoLists
    {
        return $this->list;
    }

    public function setList(?TodoLists $list): self
    {
        $this->list = $list;

        return $this;
    }
}
