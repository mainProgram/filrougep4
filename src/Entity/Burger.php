<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Controller\BurgerController;
use App\Repository\BurgerRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\NumericFilter;

#[ORM\Entity(repositoryClass: BurgerRepository::class)]
#[ApiResource(
    collectionOperations:[
        "get" => [
            "method" => "get",
            "status" => 200,
            "security" => "is_granted('ROLE_GESTIONNAIRE')",
            "security_message" => "Vous n'êtes pas autorisé !",
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
        "delete" => [
            "method" => "delete",
            "security" => "is_granted('ROLE_GESTIONNAIRE')",
            "security_message" => "Vous n'êtes pas autorisé !",
        ],
        "put" => [
            "method" => "put",
            "security" => "is_granted('ROLE_GESTIONNAIRE')",
            "security_message" => "Vous n'êtes pas autorisé !",
        ]
    ]
)]
#[ApiFilter(SearchFilter::class, properties: ['nom' => 'ipartial' ])]
#[ApiFilter(NumericFilter::class, properties: ['prix'])]
class Burger extends Produit
{
   
}
