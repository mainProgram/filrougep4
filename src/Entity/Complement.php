<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;

#[ApiResource(
    collectionOperations:[
        "complements" => [
            "method" => "get",
            "status" => 200,
            "path" => "/complements"
        ]
    ],  
    itemOperations: []
)]
class Complement
{

}
