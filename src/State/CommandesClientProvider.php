<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;

class CommandesClientProvider implements ProviderInterface
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private CommandeRepository $commandeRepository
    ) {}

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $orders = $this->commandeRepository->findByClient($uriVariables["id"]);
        return $orders;
    }
}
