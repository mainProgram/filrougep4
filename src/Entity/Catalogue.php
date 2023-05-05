<?php

namespace App\Entity;

use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\ApiProperty;
#[ApiResource(
    operations: [
        new GetCollection(status: 200, uriTemplate: '/catalogue', normalizationContext: ['groups' => ['produit:detail']])
    ]
)]
class Catalogue
{
    #[ApiProperty(identifier: true)]
    private $code;
}
