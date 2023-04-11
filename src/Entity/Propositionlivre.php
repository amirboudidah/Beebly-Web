<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Propositionlivre
 *
 * @ORM\Table(name="propositionlivre", indexes={@ORM\Index(name="idcproplivre", columns={"idclient"})})
 * @ORM\Entity
 */
class Propositionlivre
{
    /**
     * @var int
     *
     * @ORM\Column(name="idPropositionLivre", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idpropositionlivre;

    /**
     * @var string|null
     *
     * @ORM\Column(name="titreLivre", type="string", length=45, nullable=true, options={"default"="NULL"})
     */
    private $titrelivre = 'NULL';

    /**
     * @var string
     *
     * @ORM\Column(name="editon", type="string", length=45, nullable=false)
     */
    private $editon;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="dateProposition", type="date", nullable=true, )
     */
    private $dateproposition = '';

    /**
     * @var string|null
     *
     * @ORM\Column(name="descriptionEtat", type="string", length=45, nullable=true, options={"default"="NULL"})
     */
    private $descriptionetat = 'NULL';

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idclient", referencedColumnName="id")
     * })
     */
    private $idclient;

    public function getIdpropositionlivre(): ?int
    {
        return $this->idpropositionlivre;
    }

    public function getTitrelivre(): ?string
    {
        return $this->titrelivre;
    }

    public function setTitrelivre(?string $titrelivre): self
    {
        $this->titrelivre = $titrelivre;

        return $this;
    }

    public function getEditon(): ?string
    {
        return $this->editon;
    }

    public function setEditon(string $editon): self
    {
        $this->editon = $editon;

        return $this;
    }

    public function getDateproposition(): ?\DateTimeInterface
    {
        return $this->dateproposition;
    }

    public function setDateproposition(?\DateTimeInterface $dateproposition): self
    {
        $this->dateproposition = $dateproposition;

        return $this;
    }

    public function getDescriptionetat(): ?string
    {
        return $this->descriptionetat;
    }

    public function setDescriptionetat(?string $descriptionetat): self
    {
        $this->descriptionetat = $descriptionetat;

        return $this;
    }

    public function getIdclient(): ?User
    {
        return $this->idclient;
    }

    public function setIdclient(?User $idclient): self
    {
        $this->idclient = $idclient;

        return $this;
    }


}
