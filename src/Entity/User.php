<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'string', length: 255)]
    private $status;

    #[ORM\OneToOne(mappedBy: 'user', targetEntity: Reservation::class, cascade: ['persist', 'remove'])]
    private $no;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getNo(): ?Reservation
    {
        return $this->no;
    }

    public function setNo(Reservation $no): self
    {
        // set the owning side of the relation if necessary
        if ($no->getUser() !== $this) {
            $no->setUser($this);
        }

        $this->no = $no;

        return $this;
    }
}
