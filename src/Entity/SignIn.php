<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Symfony\Component\Validator\Constraints as Assert;

class SignIn
{
    /**
     * @Assert\NotBlank
     * @Assert\Email
     * 
     */
    private $adrmail;
        /**
     * @Assert\NotBlank
     * 
     */

    private $mdp;

    public function getAdrmail(): ?string
    {
        return $this->adrmail;
    }

    public function setAdrmail(string $adrmail): self
    {
        $this->adrmail = $adrmail;

        return $this;
    }

    public function getMdp(): ?string
    {
        return $this->mdp;
    }

    public function setMdp(string $mdp): self
    {
        $this->mdp = $mdp;

        return $this;
    }
}