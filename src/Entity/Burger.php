<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\BurgerController;
use App\Repository\BurgerRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BurgerRepository::class)]
#[ApiResource(
    collectionOperations:[
        "get" => [
            "method" => "get",
            "status" => 200,
            "normalization_context" => [
                "groups" => ["burger:list"]
            ]
        ],
        "post" => [
            "status" => 201,
            "method" => "post",
            "security" => "is_granted('ROLE_GESTIONNAIRE')",
            "security_message" => "Vous n'êtes pas autorisé !",
            "normalization_context" => [
                "groups" => ["burger:detail"]
            ]
        ],
        "addBurger" => [
            "status" => 201,
            "method" => "post",
            "security" => "is_granted('ROLE_GESTIONNAIRE')",
            "security_message" => "Vous n'êtes pas autorisé !",
            "path" => "/addBurger",
            "controller" => BurgerController::class
        ]
        ],
        itemOperations: [
            "get" => [
                "method" => "get",
                "status" => 200,
                "normalization_context" => [
                    "groups" => ["burger:detail"]
                ]
            ],
        ]
)]
class Burger extends Produit
{
   
}
