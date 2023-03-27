<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Detailslivraison
 *
 * @ORM\Table(name="detailslivraison", indexes={@ORM\Index(name="fore_idx", columns={"idEstimationOffreLivre"})})
 * @ORM\Entity
 */
class Detailslivraison
{
    /**
     * @var int
     *
     * @ORM\Column(name="idDetailsLivraison", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $iddetailslivraison;

    /**
     * @var string|null
     *
     * @ORM\Column(name="etatLivrasion", type="string", length=45, nullable=true, options={"default"="NULL"})
     */
    private $etatlivrasion = 'NULL';

    /**
     * @var string|null
     *
     * @ORM\Column(name="AdresseLivraison", type="string", length=45, nullable=true, options={"default"="NULL"})
     */
    private $adresselivraison = 'NULL';

    /**
     * @var \Estimationoffrelivre
     *
     * @ORM\ManyToOne(targetEntity="Estimationoffrelivre")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idEstimationOffreLivre", referencedColumnName="idEstimationOffreLivre")
     * })
     */
    private $idestimationoffrelivre;

    public function getIddetailslivraison(): ?int
    {
        return $this->iddetailslivraison;
    }

    public function getEtatlivrasion(): ?string
    {
        return $this->etatlivrasion;
    }

    public function setEtatlivrasion(?string $etatlivrasion): self
    {
        $this->etatlivrasion = $etatlivrasion;

        return $this;
    }

    public function getAdresselivraison(): ?string
    {
        return $this->adresselivraison;
    }

    public function setAdresselivraison(?string $adresselivraison): self
    {
        $this->adresselivraison = $adresselivraison;

        return $this;
    }

    public function getIdestimationoffrelivre(): ?Estimationoffrelivre
    {
        return $this->idestimationoffrelivre;
    }

    public function setIdestimationoffrelivre(?Estimationoffrelivre $idestimationoffrelivre): self
    {
        $this->idestimationoffrelivre = $idestimationoffrelivre;

        return $this;
    }


}
