<?php

namespace App\EventSubscriber;

use App\Entity\Commande;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


// class CommandeSubscriber implements EventSubscriberInterface
class CommandeSubscriber 
{
    // public function on0(LifecycleEventArgs $event): void
    // {
    //     // ...
    // }

    // public static function getSubscribedEvents(): array
    // {
    //     return [
    //         '0' => 'on0',
    //     ];
    // }

    public function __construct(private TokenStorageInterface $token, private EntityManagerInterface $entityManager)
    {
        
    }

    public function postUpdate(Commande $commande, LifecycleEventArgs $event): void
    {
        $gestionnaire = $this->token->getToken()->getUser();

        $numero = "#".$commande->getId();

        $commande->setNumero($numero);

        $commande->setGestionnaire($gestionnaire);

        if($commande->getEtat() == "valide")
            dd("Etat valide");

        $this->entityManager->persist($commande);
        $this->entityManager->flush();

        // dd($commande);
    }
}
