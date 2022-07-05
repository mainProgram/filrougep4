<?php

namespace App\EventSubscriber;

use App\Entity\User;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class UserChangedNotifier
{
    // the entity listener methods receive two arguments:
    // the entity instance and the lifecycle event
    public function postUpdate(User $user, LifecycleEventArgs $event): void
    {
        dd("Modifié");
    }
}