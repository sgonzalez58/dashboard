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

    #[ORM\Column(type: 'string', length: 50)]
    private $nom;

    #[ORM\Column(type: 'datetime')]
    private $date_achat;

    #[ORM\Column(type: 'date')]
    private $date_garantie;

    #[ORM\Column(type: 'integer')]
    private $prix;

    #[ORM\Column(type: 'text', nullable: true)]
    private $zone_saisie;

    #[ORM\Column(type: 'text', nullable: true)]
    private $notice;

    #[ORM\ManyToOne(targetEntity: LieuAchat::class, inversedBy: 'articles')]
    #[ORM\JoinColumn(nullable: false)]
    private $lieu_achat;

    #[ORM\ManyToOne(targetEntity: Categorie::class, inversedBy: 'articles')]
    #[ORM\JoinColumn(nullable: false)]
    private $categorie;

    #[ORM\Column(type: 'string', length: 255)]
    private $photo;


    public function getId(): ?int
    {
        return $this->id;
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

    public function getNotice(): ?string
    {
        return $this->notice;
    }

    public function setNotice(?string $notice): self
    {
        $this->notice = $notice;

        return $this;
    }

    public function getLieuAchat(): ?LieuAchat
    {
        return $this->lieu_achat;
    }

    public function setLieuAchat(?LieuAchat $lieu_achat): self
    {
        $this->lieu_achat = $lieu_achat;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

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
}
