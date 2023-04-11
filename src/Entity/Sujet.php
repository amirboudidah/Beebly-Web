<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Sujet
 *
 * @ORM\Table(name="sujet", indexes={@ORM\Index(name="idsujtopic", columns={"idtopic"}), @ORM\Index(name="idcsujet", columns={"iduser"})})
 * @ORM\Entity
 */
class Sujet
{
    /**
     * @var int
     *
     * @ORM\Column(name="idsujet", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idsujet;

    /**
     * @var string
     *
     * @ORM\Column(name="titresujet", type="string", length=255)
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
     */
    private $titresujet;

    /**
     * @var string
     *
     * @ORM\Column(name="contenu", type="text", length=65535, nullable=false)
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
     */
    private $contenu;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date", nullable=false)
     * @Assert\GreaterThanOrEqual(
     *      value = "today",
     *      message = "The date should be equal or greater than today"
     * )
     */
    private $date;

    /**
     * @var int
     *
     * @ORM\Column(name="accepter", type="integer", nullable=false)
     */
    private $accepter;

    /**
     * @var int
     *
     * @ORM\Column(name="nbcom", type="integer", nullable=false)
     */
    private $nbcom;

    /**
     * @var \Topic
     *
     * @ORM\ManyToOne(targetEntity="Topic")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idtopic", referencedColumnName="idtopic")
     * })
     */
    private $idtopic;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="iduser", referencedColumnName="id")
     * })
     */
    private $iduser;

    public function getIdsujet(): ?int
    {
        return $this->idsujet;
    }

    public function getTitresujet(): ?string
    {
        return $this->titresujet;
    }

    public function setTitresujet(string $titresujet): self
    {
        $this->titresujet = $titresujet;

        return $this;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): self
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getAccepter(): ?int
    {
        return $this->accepter;
    }

    public function setAccepter(int $accepter): self
    {
        $this->accepter = $accepter;

        return $this;
    }

    public function getNbcom(): ?int
    {
        return $this->nbcom;
    }

    public function setNbcom(int $nbcom): self
    {
        $this->nbcom = $nbcom;

        return $this;
    }

    public function getIdtopic(): ?Topic
    {
        return $this->idtopic;
    }

    public function setIdtopic(?Topic $idtopic): self
    {
        $this->idtopic = $idtopic;

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
