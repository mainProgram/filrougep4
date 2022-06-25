<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\GestionnaireRepository;
use ApiPlatform\Core\Annotation\ApiResource;

#[ORM\Entity(repositoryClass: GestionnaireRepository::class)]
#[ApiResource]
class Gestionnaire extends User
{
    public function getRole() : string
    {
        return "ROLE_GESTIONNAIRE";
    }
}
