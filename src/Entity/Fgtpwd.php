<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Symfony\Component\Validator\Constraints as Assert;

class Fgtpwd
{
    /**
     * @Assert\NotBlank
     * @Assert\Email
     * 
     */
    private $adrmail;


    public function getAdrmail(): ?string
    {
        return $this->adrmail;
    }

    public function setAdrmail(string $adrmail): self
    {
        $this->adrmail = $adrmail;

        return $this;
    }


}