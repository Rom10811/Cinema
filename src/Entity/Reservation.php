<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReservationRepository::class)
 */
class Reservation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Seance::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $idFilm;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $idUser;

    /**
     * @ORM\Column(type="integer")
     */
    private $NbrPlaces;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdFilm(): ?Seance
    {
        return $this->idFilm;
    }

    public function setIdFilm(?Seance $idFilm): self
    {
        $this->idFilm = $idFilm;

        return $this;
    }

    public function getIdUser(): ?User
    {
        return $this->idUser;
    }

    public function setIdUser(?User $idUser): self
    {
        $this->idUser = $idUser;

        return $this;
    }

    public function getNbrPlaces(): ?int
    {
        return $this->NbrPlaces;
    }

    public function setNbrPlaces(int $NbrPlaces): self
    {
        $this->NbrPlaces = $NbrPlaces;

        return $this;
    }
}
