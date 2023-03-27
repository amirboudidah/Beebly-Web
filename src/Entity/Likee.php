<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Likee
 *
 * @ORM\Table(name="likee", indexes={@ORM\Index(name="idclike", columns={"id_user"}), @ORM\Index(name="idcomlike", columns={"id_commentaire"})})
 * @ORM\Entity
 */
class Likee
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_like", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idLike;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_user", referencedColumnName="id")
     * })
     */
    private $idUser;

    /**
     * @var \Commentaire
     *
     * @ORM\ManyToOne(targetEntity="Commentaire")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_commentaire", referencedColumnName="idcom")
     * })
     */
    private $idCommentaire;

    public function getIdLike(): ?int
    {
        return $this->idLike;
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

    public function getIdCommentaire(): ?Commentaire
    {
        return $this->idCommentaire;
    }

    public function setIdCommentaire(?Commentaire $idCommentaire): self
    {
        $this->idCommentaire = $idCommentaire;

        return $this;
    }


}
