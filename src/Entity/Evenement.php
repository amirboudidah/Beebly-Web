<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
/**
 * Evenement
 *
 * @ORM\Table(name="evenement", indexes={@ORM\Index(name="idcevent", columns={"id_user"})})
 * @ORM\Entity
 */
class Evenement
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *  @Groups({"post:read"})
    */ 
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="libelle", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="Remplir ce champ")
     * @Assert\Length(
     *      min = 5,
     *      max = 50,
     *      minMessage = "Votre message doit avoir au minimum 5 caractéres",
     *      maxMessage = "Votre message doit avoir au maximum 50 caractéres"
     * )
     * @Assert\Regex(
     *     pattern="/^[A-Z]/",
     *     match=true,
     *     message="Votre message doit commencer par une lettre majuscule"
     * )
     *  @Groups({"post:read"})
    */ 
    private $libelle;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=false)
     *  @Groups({"post:read"})
    */ 
    private $image;

    /**
     * @var string
     *
     * @ORM\Column(name="date", type="string", length=255, nullable=false)
     *  @Groups({"post:read"})
    */ 
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="Remplir ce champ")
     * @Assert\Length(
     *      min = 5,
     *      max = 50,
     *      minMessage = "Votre message doit avoir au minimum 5 caractéres",
     *      maxMessage = "Votre message doit avoir au maximum 50 caractéres"
     * )
     * @Assert\Regex(
     *     pattern="/^[A-Z]/",
     *     match=true,
     *     message="Votre message doit commencer par une lettre majuscule"
     * )
    *  @Groups({"post:read"})
    */ 
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="emplacement", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="Remplir ce champ")
     * @Assert\Length(
     *      min = 5,
     *      max = 50,
     *      minMessage = "Votre message doit avoir au minimum 5 caractéres",
     *      maxMessage = "Votre message doit avoir au maximum 50 caractéres"
     * )
     * @Assert\Regex(
     *     pattern="/^[A-Z]/",
     *     match=true,
     *     message="Votre message doit commencer par une lettre majuscule"
     * )
     *  @Groups({"post:read"})
    */ 
    private $emplacement;

    /**
     * @var int
     *
     * @ORM\Column(name="nb_place", type="integer", nullable=false)
     * @Assert\NotBlank(message="Remplir ce champ")
     *  @Groups({"post:read"})
    */ 
    private $nbPlace;

    /**
     * @var int
     *
     * @ORM\Column(name="duree", type="integer", nullable=false)
     * @Assert\NotBlank(message="Remplir ce champ")
     *  @Groups({"post:read"})
    */ 
    private $duree;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_user", referencedColumnName="id")
     * })
     *  @Groups({"post:read"})
    */ 
    private $idUser;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function setDate(string $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getEmplacement(): ?string
    {
        return $this->emplacement;
    }

    public function setEmplacement(string $emplacement): self
    {
        $this->emplacement = $emplacement;

        return $this;
    }

    public function getNbPlace(): ?int
    {
        return $this->nbPlace;
    }

    public function setNbPlace(int $nbPlace): self
    {
        $this->nbPlace = $nbPlace;

        return $this;
    }

    public function getDuree(): ?int
    {
        return $this->duree;
    }

    public function setDuree(int $duree): self
    {
        $this->duree = $duree;

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


}
