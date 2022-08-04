<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class CommandeService
{
    public static function isThereABurgerOrAMenu($object, ExecutionContextInterface $context, $payload)
    {
        if(count($object->getCommandeMenus()) == 0 && count($object->getCommandeBurgers()) == 0)
            $context->buildViolation("Pas de commande sans burger ou menu !")
                    ->addViolation();
    }

    // public static function quantiteChoisieVsQuantiteMenu($object, ExecutionContextInterface $context, $payload)
    public  function quantiteChoisieVsQuantiteMenu($object)
    {
        foreach($object->getCommandeMenus() as $commandeMenu)
            if(count($commandeMenu->getMenu()->getMenuTailles()) > 0)
            {
                $quantiteBoissons = 0 ;  
                foreach($commandeMenu->getMenu()->getMenuTailles() as $menuTaille)
                    $quantiteBoissons += $menuTaille->getQuantite();

                $boissonsChoisies = $commandeMenu->getCommandeMenuTailleBoissons();
                // dump($quantiteBoissons);

                if(count($boissonsChoisies) != $quantiteBoissons)
                    return new JsonResponse( ["error" => "Le menu ".$commandeMenu->getMenu()->getNom()." a ".$quantiteBoissons." boisson (s)!"], 400);
        
            }
    }

    public function calculPrix($commande)
    {
        $prix = 0;

        if(count($commande->getCommandeBurgers()) > 0)
            foreach($commande->getCommandeBurgers() as $burger)
                $prix+= $burger->getBurger()->getPrix() * $burger->getQuantite();

        if(count($commande->getCommandeMenus()) > 0)
            foreach($commande->getCommandeMenus() as $menu)
                $prix+= $menu->getMenu()->getPrix() * $menu->getQuantite();

        if(count($commande->getCommandeFrites()) > 0)
            foreach($commande->getCommandeFrites() as $frite)
                $prix+= $frite->getFrite()->getPrix() * $frite->getQuantite();

        if(count($commande->getCommandeTailleBoissons()) > 0)
            foreach($commande->getCommandeTailleBoissons() as $complementBoisson)
                $prix += $complementBoisson->getQuantite() * $complementBoisson->getTailleBoisson()->getPrix();
        
        $zone = $commande->getZone();
        if($zone) $prix += $zone->getPrix();
            
        $commande->setPrix($prix);
    }
}