<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Commandelignelivre
 *
 * @ORM\Table(name="commandelignelivre", indexes={@ORM\Index(name="fk_cmdd", columns={"idCommande"}), @ORM\Index(name="fk_cmd", columns={"idLivre"})})
 * @ORM\Entity
 */
class Commandelignelivre
{
    /**
     * @var int
     *
     * @ORM\Column(name="idLigne", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idligne;

    /**
     * @var int
     *
     * @ORM\Column(name="idLivre", type="integer", nullable=false)
     */
    private $idlivre;

    /**
     * @var int
     *
     * @ORM\Column(name="idCommande", type="integer", nullable=false)
     */
    private $idcommande;

    public function getIdligne(): ?int
    {
        return $this->idligne;
    }

    public function getIdlivre(): ?int
    {
        return $this->idlivre;
    }

    public function setIdlivre(int $idlivre): self
    {
        $this->idlivre = $idlivre;

        return $this;
    }

    public function getIdcommande(): ?int
    {
        return $this->idcommande;
    }

    public function setIdcommande(int $idcommande): self
    {
        $this->idcommande = $idcommande;

        return $this;
    }


}
