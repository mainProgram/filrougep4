<?php

namespace App\Entity;

use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\ApiResource;
use App\State\ComplementProvider;

#[ApiResource(operations: [new GetCollection(status: 200, uriTemplate: '/complements', normalizationContext: ['groups' => ['complement:read']], provider:ComplementProvider::class)])]

class Complement
{
}
