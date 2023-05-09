<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
/**
 * Reclamation
 *
 * @ORM\Table(name="reclamation", indexes={@ORM\Index(name="idcrec", columns={"id_user"}), @ORM\Index(name="idreprec", columns={"id_reponse"})})
 * @ORM\Entity
 */
class Reclamation
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
     * @ORM\Column(name="type", type="string", length=30, nullable=false)
     * @Assert\NotBlank(message="type cannot be null")
     *  @Groups({"post:read"})
    */ 
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="sujet", type="string", length=100, nullable=false)
     * @Assert\NotBlank(message="sujet cannot be null")
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
    private $sujet;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=8000, nullable=false)
     * @Assert\NotBlank(message="description cannot be null")
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
     * @var \DateTime|null
     *
     * @ORM\Column(name="date", type="date", nullable=true)
     *  @Groups({"post:read"})
    */ 
    private $date = '';

    /**
     * @var string
     *
     * @ORM\Column(name="photo", type="blob", length=0, nullable=false)
     */
    private $photo;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=20, nullable=false)
     *  @Groups({"post:read"})
    */ 
    private $status;

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

    /**
     * @var \Reponse
     *
     * @ORM\ManyToOne(targetEntity="Reponse")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_reponse", referencedColumnName="id")
     * })
     *  @Groups({"post:read"})
    */ 
    private $idReponse;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getSujet(): ?string
    {
        return $this->sujet;
    }

    public function setSujet(string $sujet): self
    {
        $this->sujet = $sujet;

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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getPhoto()
    {
        return $this->photo;
    }

    public function setPhoto($photo): self
    {
        $this->photo = $photo;

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

    public function getIdUser(): ?User
    {
        return $this->idUser;
    }

    public function setIdUser(?User $idUser): self
    {
        $this->idUser = $idUser;

        return $this;
    }

    public function getIdReponse(): ?Reponse
    {
        return $this->idReponse;
    }

    public function setIdReponse(?Reponse $idReponse): self
    {
        $this->idReponse = $idReponse;

        return $this;
    }
    public function __toString(): string
    {
        return $this->id;
    }

}
