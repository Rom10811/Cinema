<?php

namespace App\Entity;

use App\Repository\SalleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SalleRepository::class)
 */
class Salle
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $Numero;

    /**
     * @ORM\Column(type="integer")
     */
    private $TotalPlaces;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $PlacesRestantes;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $PlaceReserv;


    public function __construct()
    {
        $this->seances = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumero(): ?int
    {
        return $this->Numero;
    }

    public function setNumero(int $Numero): self
    {
        $this->Numero = $Numero;

        return $this;
    }

    public function getTotalPlaces(): ?int
    {
        return $this->TotalPlaces;
    }

    public function setTotalPlaces(int $TotalPlaces): self
    {
        $this->TotalPlaces = $TotalPlaces;

        return $this;
    }

    public function getPlacesRestantes(): ?int
    {
        return $this->PlacesRestantes;
    }

    public function setPlacesRestantes(?int $PlacesRestantes): self
    {
        $this->PlacesRestantes = $PlacesRestantes;

        return $this;
    }

    public function getPlaceReserv(): ?int
    {
        return $this->PlaceReserv;
    }

    public function setPlaceReserv(?int $PlaceReserv): self
    {
        $this->PlaceReserv = $PlaceReserv;

        return $this;
    }

    /**
     * @return Collection|Seance[]
     */
    public function getSeances(): Collection
    {
        return $this->seances;
    }

    public function addSeance(Seance $seance): self
    {
        if (!$this->seances->contains($seance)) {
            $this->seances[] = $seance;
            $seance->setNomSalle($this);
        }

        return $this;
    }

    public function removeSeance(Seance $seance): self
    {
        if ($this->seances->removeElement($seance)) {
            // set the owning side to null (unless already changed)
            if ($seance->getNomSalle() === $this) {
                $seance->setNomSalle(null);
            }
        }

        return $this;
    }
}
