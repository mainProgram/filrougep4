<?php

namespace App\Entity;

use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\ApiProperty;
use App\State\CatalogueProvider;

#[ApiResource(
    operations: [
        new GetCollection(status: 200, uriTemplate: '/catalogue', normalizationContext: ['groups' => ['produit:detail']], provider:CatalogueProvider::class)
    ]
)]
class Catalogue
{
    #[ApiProperty(identifier: true)]
    private $code;
}
