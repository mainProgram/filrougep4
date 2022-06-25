<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ClientRepository;
use ApiPlatform\Core\Annotation\ApiResource;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
#[ApiResource(
    collectionOperations:[
        "sign_up" => [
            "method" => "post",
            "status" => 201,
            "path" => "/sign_up"
        ]
    ]          
)]
class Client extends User
{

}
