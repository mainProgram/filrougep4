<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Serializer\Filter\PropertyFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\NumericFilter;
use ApiPlatform\Core\Annotation\ApiProperty;

#[ApiResource(
    collectionOperations:[
        "catalogue" => [
            "method" => "get",
            "status" => 200,
            "path" => "/catalogue",
            "normalization_context" => [
                "groups" => ["menu:list"]
            ]
        ]
    ],  
    itemOperations: []
)]
// #[ApiFilter(SearchFilter::class, properties: ['menus.nom' => 'ipartial', 'burgers.nom' => 'ipartial'])]
// #[ApiFilter(NumericFilter::class, properties: ['menus.prix'])]
// #[ApiFilter(PropertyFilter::class)]

class Catalogue
{
    #[ApiProperty(identifier:true)]
    private $code;

   
}
