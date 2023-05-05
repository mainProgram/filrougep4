<?php

namespace App\Entity;

use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiFilter;
#[ApiResource(operations: [new GetCollection(normalizationContext: ['groups' => ['complement:read']])])]
class Complement
{
}
