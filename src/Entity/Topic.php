<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
/**
 * Topic
 *
 * @ORM\Table(name="topic", indexes={@ORM\Index(name="idctopic", columns={"iduser"})})
 * @ORM\Entity
 */
class Topic
{
    /**
     * @var int
     *
     * @ORM\Column(name="idtopic", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *  @Groups({"post:read"})
    */ 
    private $idtopic;

    /**
     * @var string|null
     *
     * @ORM\Column(name="titretopic", type="string", length=255, nullable=true)
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
    private $titretopic;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
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
    private $description ;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date", type="date", nullable=true)
     * @Assert\GreaterThanOrEqual(
     *      value = "today",
     *      message = "The date should be equal or greater than today"
     * )
     *  @Groups({"post:read"})
    */ 
    private $date ;

    /**
     * @var bool
     *
     * @ORM\Column(name="accepter", type="boolean", nullable=false)
     *  @Groups({"post:read"})
    */ 
    private $accepter = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="nbsujet", type="integer", nullable=false)
     *  @Groups({"post:read"})
    */ 
    private $nbsujet = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="hide", type="integer", nullable=false)
     *  @Groups({"post:read"})
    */ 
    private $hide = '0';

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="iduser", referencedColumnName="id")
     * })
     *  @Groups({"post:read"})
    */ 
    private $iduser;

    public function getIdtopic(): ?int
    {
        return $this->idtopic;
    }

    public function getTitretopic(): ?string
    {
        return $this->titretopic;
    }

    public function setTitretopic(?string $titretopic): self
    {
        $this->titretopic = $titretopic;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
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

    public function isAccepter(): ?bool
    {
        return $this->accepter;
    }

    public function setAccepter(bool $accepter): self
    {
        $this->accepter = $accepter;

        return $this;
    }

    public function getNbsujet(): ?int
    {
        return $this->nbsujet;
    }

    public function setNbsujet(int $nbsujet): self
    {
        $this->nbsujet = $nbsujet;

        return $this;
    }

    public function getHide(): ?int
    {
        return $this->hide;
    }

    public function setHide(int $hide): self
    {
        $this->hide = $hide;

        return $this;
    }

    public function getIduser(): ?User
    {
        return $this->iduser;
    }

    public function setIduser(?User $iduser): self
    {
        $this->iduser = $iduser;

        return $this;
    }


}
