<?php

namespace App\EventSubscriber;

use App\Entity\Boisson;
use App\Entity\Burger;
use App\Entity\Frite;
use App\Entity\Menu;
use Doctrine\ORM\Events;
use Doctrine\ORM\Query\Expr\From;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserSubscriber implements EventSubscriberInterface
{
    private ?TokenInterface $token;
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->token = $tokenStorage->getToken();
    }
    
    public static function getSubscribedEvents(): array
    {
        return [
            Events::prePersist,
        ];
    }
    
    private function getUser()
    {
        if (null === $token = $this->token) {
            return null;
        }
        if (!is_object($user = $token->getUser())) {
            return null;
        }
        return $user;
    }
    
    public function prePersist(LifecycleEventArgs $args)
    {
        if ($args->getObject() instanceof Burger or $args->getObject() instanceof Menu  or $args->getObject() instanceof Frite  or $args->getObject() instanceof Boisson) {
            $args->getObject()->setUser($this->getUser());    
        }
    }
}
