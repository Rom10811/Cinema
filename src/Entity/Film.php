<?php

namespace App\Entity;

use App\Repository\FilmRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FilmRepository::class)
 */
class Film
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Description;

    /**
     * @ORM\Column(type="date")
     */
    private $DateSortie;

    /**
     * @ORM\OneToOne(targetEntity=Images::class, mappedBy="IdFilm", cascade={"persist", "remove"})
     */
    private $images;

    /**
     * @ORM\OneToOne(targetEntity=Videos::class, mappedBy="idFilm", cascade={"persist", "remove"})
     */
    private $videos;



    public function __construct()
    {
        $this->seances = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): self
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(string $Description): self
    {
        $this->Description = $Description;

        return $this;
    }

    public function getDateSortie(): ?\DateTimeInterface
    {
        return $this->DateSortie;
    }

    public function setDateSortie(\DateTimeInterface $DateSortie): self
    {
        $this->DateSortie = $DateSortie;

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
            $seance->setNomFilm($this);
        }

        return $this;
    }

    public function removeSeance(Seance $seance): self
    {
        if ($this->seances->removeElement($seance)) {
            // set the owning side to null (unless already changed)
            if ($seance->getNomFilm() === $this) {
                $seance->setNomFilm(null);
            }
        }

        return $this;
    }

    public function getImages(): ?Images
    {
        return $this->images;
    }

    public function setImages(Images $images): self
    {
        // set the owning side of the relation if necessary
        if ($images->getIdFilm() !== $this) {
            $images->setIdFilm($this);
        }

        $this->images = $images;

        return $this;
    }

    public function getVideos(): ?Videos
    {
        return $this->videos;
    }

    public function setVideos(Videos $videos): self
    {
        // set the owning side of the relation if necessary
        if ($videos->getIdFilm() !== $this) {
            $videos->setIdFilm($this);
        }

        $this->videos = $videos;

        return $this;
    }

}
