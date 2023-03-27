<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ClotureAchat
 *
 * @ORM\Table(name="cloture_achat", indexes={@ORM\Index(name="foreign_idx", columns={"id_details_livraison"})})
 * @ORM\Entity
 */
class ClotureAchat
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_cloture", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idCloture;

    /**
     * @var int|null
     *
     * @ORM\Column(name="pointAchatFinale", type="integer", nullable=true, options={"default"="NULL"})
     */
    private $pointachatfinale = NULL;

    /**
     * @var string|null
     *
     * @ORM\Column(name="etatCloture", type="string", length=45, nullable=true, options={"default"="NULL"})
     */
    private $etatcloture = 'NULL';

    /**
     * @var string|null
     *
     * @ORM\Column(name="etatLivre", type="string", length=45, nullable=true, options={"default"="NULL"})
     */
    private $etatlivre = 'NULL';

    /**
     * @var \Detailslivraison
     *
     * @ORM\ManyToOne(targetEntity="Detailslivraison")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_details_livraison", referencedColumnName="idDetailsLivraison")
     * })
     */
    private $idDetailsLivraison;

    public function getIdCloture(): ?int
    {
        return $this->idCloture;
    }

    public function getPointachatfinale(): ?int
    {
        return $this->pointachatfinale;
    }

    public function setPointachatfinale(?int $pointachatfinale): self
    {
        $this->pointachatfinale = $pointachatfinale;

        return $this;
    }

    public function getEtatcloture(): ?string
    {
        return $this->etatcloture;
    }

    public function setEtatcloture(?string $etatcloture): self
    {
        $this->etatcloture = $etatcloture;

        return $this;
    }

    public function getEtatlivre(): ?string
    {
        return $this->etatlivre;
    }

    public function setEtatlivre(?string $etatlivre): self
    {
        $this->etatlivre = $etatlivre;

        return $this;
    }

    public function getIdDetailsLivraison(): ?Detailslivraison
    {
        return $this->idDetailsLivraison;
    }

    public function setIdDetailsLivraison(?Detailslivraison $idDetailsLivraison): self
    {
        $this->idDetailsLivraison = $idDetailsLivraison;

        return $this;
    }


}
