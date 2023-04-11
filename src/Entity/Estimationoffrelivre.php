<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Estimationoffrelivre
 *
 * @ORM\Table(name="estimationoffrelivre", indexes={@ORM\Index(name="for_idx", columns={"idProposition"})})
 * @ORM\Entity
 */
class Estimationoffrelivre
{
    /**
     * @var int
     *
     * @ORM\Column(name="idEstimationOffreLivre", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idestimationoffrelivre;

    /**
     * @var int|null
     *
     * @ORM\Column(name="pointEstime", type="integer", nullable=true)
     */
    private $pointestime;

    /**
     * @var string|null
     *
     * @ORM\Column(name="etat", type="string", length=45, nullable=true, options={"default"="NULL"})
     */
    private $etat = 'NULL';

    /**
     * @var \Propositionlivre
     *
     * @ORM\ManyToOne(targetEntity="Propositionlivre")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idProposition", referencedColumnName="idPropositionLivre")
     * })
     */
    private $idproposition;

    public function getIdestimationoffrelivre(): ?int
    {
        return $this->idestimationoffrelivre;
    }

    public function getPointestime(): ?int
    {
        return $this->pointestime;
    }

    public function setPointestime(?int $pointestime): self
    {
        $this->pointestime = $pointestime;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(?string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getIdproposition(): ?Propositionlivre
    {
        return $this->idproposition;
    }

    public function setIdproposition(?Propositionlivre $idproposition): self
    {
        $this->idproposition = $idproposition;

        return $this;
    }


}
