<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer')]
    private $lieu_achat;

    #[ORM\Column(type: 'string', length: 50)]
    private $nom;

    #[ORM\Column(type: 'integer')]
    private $categorie;

    #[ORM\Column(type: 'datetime')]
    private $date_achat;

    #[ORM\Column(type: 'date')]
    private $date_garantie;

    #[ORM\Column(type: 'integer')]
    private $prix;

    #[ORM\Column(type: 'text', nullable: true)]
    private $zone_saisie;

    #[ORM\Column(type: 'string', length: 40)]
    private $photo;

    #[ORM\Column(type: 'text', nullable: true)]
    private $notice;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLieuAchat(): ?int
    {
        return $this->lieu_achat;
    }

    public function setLieuAchat(int $lieu_achat): self
    {
        $this->lieu_achat = $lieu_achat;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getCategorie(): ?int
    {
        return $this->categorie;
    }

    public function setCategorie(int $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getDateAchat(): ?\DateTimeInterface
    {
        return $this->date_achat;
    }

    public function setDateAchat(\DateTimeInterface $date_achat): self
    {
        $this->date_achat = $date_achat;

        return $this;
    }

    public function getDateGarantie(): ?\DateTimeInterface
    {
        return $this->date_garantie;
    }

    public function setDateGarantie(\DateTimeInterface $date_garantie): self
    {
        $this->date_garantie = $date_garantie;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getZoneSaisie(): ?string
    {
        return $this->zone_saisie;
    }

    public function setZoneSaisie(?string $zone_saisie): self
    {
        $this->zone_saisie = $zone_saisie;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getNotice(): ?string
    {
        return $this->notice;
    }

    public function setNotice(?string $notice): self
    {
        $this->notice = $notice;

        return $this;
    }
}
