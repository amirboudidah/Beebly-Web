<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Commentaire
 *
 * @ORM\Table(name="commentaire", indexes={@ORM\Index(name="idsujcom", columns={"idsujet"}), @ORM\Index(name="idccom", columns={"iduser"})})
 * @ORM\Entity
 */
class Commentaire
{
    /**
     * @var int
     *
     * @ORM\Column(name="idcom", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idcom;

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
     * @ORM\Column(name="nblike", type="integer", nullable=false)
     */
    private $nblike = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="nbdislike", type="integer", nullable=false)
     */
    private $nbdislike = '0';

    /**
     * @var \Sujet
     *
     * @ORM\ManyToOne(targetEntity="Sujet")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idsujet", referencedColumnName="idsujet")
     * })
     */
    private $idsujet;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="iduser", referencedColumnName="id")
     * })
     */
    private $iduser;

    public function getIdcom(): ?int
    {
        return $this->idcom;
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

    public function getNblike(): ?int
    {
        return $this->nblike;
    }

    public function setNblike(int $nblike): self
    {
        $this->nblike = $nblike;

        return $this;
    }

    public function getNbdislike(): ?int
    {
        return $this->nbdislike;
    }

    public function setNbdislike(int $nbdislike): self
    {
        $this->nbdislike = $nbdislike;

        return $this;
    }

    public function getIdsujet(): ?Sujet
    {
        return $this->idsujet;
    }

    public function setIdsujet(?Sujet $idsujet): self
    {
        $this->idsujet = $idsujet;

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
