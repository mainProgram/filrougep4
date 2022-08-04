<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;

#[ApiResource(
    collectionOperations:
    [
        // "complements" => [
        //     "method" => "get",
        //     "status" => 200,
        //     "path" => "/complements"
        // ],
        "get" => [
            "normalization_context" => [
                "groups" => ["complement:read"]
            ]
        ]
    ],  
    itemOperations: []
)]
class Complement
{

}
