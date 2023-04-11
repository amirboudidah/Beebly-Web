<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ClotureAchatRepository;
use Symfony\Component\Validator\Constraints as Assert;



/**
 * ClotureAchat
 *
 * @ORM\Table(name="cloture_achat", indexes={@ORM\Index(name="foreign_idx", columns={"id_details_livraison"})})
 * @ORM\Entity(repositoryClass="App\Repository\ClotureAchatRepository")
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
     *
     *
     * @Assert\NotBlank
     * @Assert\Type(type="numeric")
     * @Assert\GreaterThan(value=0, message="Le prix d'achat finale doit être supérieur à {{ compared_value }}.")
     */
    private $pointachatfinale ;

    /**
     * @var string|null
     *
     * @ORM\Column(name="etatCloture", type="string", length=45, nullable=true, options={"default"="NULL"})
     */
    private $etatcloture ;

    /**
     * @var string|null
     *
     * @ORM\Column(name="etatLivre", type="string", length=45, nullable=true, options={"default"="NULL"})
     *
     *  * @Assert\NotBlank(message="La description de l'etat ne peut pas être vide")
     * @Assert\Length(
     *     max=45,
     *     maxMessage="La description de l'etat ne peut pas dépasser {{ limit }} caractères",
     *     min=4,
     *     minMessage="La description de l'etat doit etre supperiere a 4 caractére ")
     */
    private $etatlivre ;

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
    public function __toString(): string
    {
        return $this->idCloture;
    }



}
