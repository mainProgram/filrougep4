<?php

namespace App\DataPersister;

use App\Entity\Menu;
use App\Entity\User;
use App\Entity\Frite;
use App\Entity\Burger;
use App\Entity\Boisson;
use App\Entity\Produit;
use App\Services\FunctionService;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\TailleBoisson;
use Symfony\Component\HttpFoundation\Request;

class ProduitDataPersister implements DataPersisterInterface
{
  
    public function __construct(private EntityManagerInterface $entityManager, private FunctionService $service)
    {
    }

    public function supports($data): bool
    {
        // if($data instanceof Burger)
        //     dd("hi");
        return $data instanceof Burger or $data instanceof Frite or $data instanceof Boisson or $data instanceof Menu  or $data instanceof Produit or $data instanceof TailleBoisson;
    }

    public function persist($data)
    {
        // dd($data);

        if($data instanceof Boisson)
        {
            $data->setPrix((0));
        }
        elseif ($data instanceof Menu)
        {
            $this->service->calculPrix($data);
        }
        else if($data instanceof TailleBoisson)
        {

            if(!$data->getNom())
            {
                $nom = $data->getBoisson()->getNom() . " " . $data->getTaille()->getNom();
                $data->setNom($nom);
            }
            if($data->getQuantiteStock() <= 0)
            {
                $data->setIsEtat(false);
            }

            // $prix = $data->getTailleBoissons()[0]->getTaille()->getPrix();
            // $data->getTailleBoissons()[0]->setPrix($prix);
        //     $nom = $data->getNom() . " " . $data->getTailleBoissons()[0]->getTaille()->getNom();
        //     $data->getTailleBoissons()[0]->setNom($nom);

        //     $prix = $data->getTailleBoissons()[0]->getTaille()->getPrix();
        //     $data->getTailleBoissons()[0]->setPrix($prix);
        }

        $file = $data->getImageWrapper();
        if($file){
            $img = file_get_contents($file);
            $data->setImage($img);
        }
        // dd($data);


        // $file = $data->getImageWrapper()->getRealPath();
        // $img = stream_get_contents(fopen($file, "rb"));
        // $data->setImage($img);

        $this->entityManager->persist($data);       
        $this->entityManager->flush();       
    }

   

    public function remove($data)
    {
        // dd($data->getMenuBurgers()[0]->getBurger());
        if($data instanceof Burger)
        {
            if( $data->isIsEtat())
            {
                if(count($data->getMenuBurgers()) == 0){
                    $data->setIsEtat(false);
                    // dd("false etat");    
                }
                else{
                    foreach($data->getMenuBurgers() as $m){
                        $data->removeMenuBurger($m);
                    }
                    // dd("Ce burger se trouve dans un menu !");    
                }
            }
            else
                $data->setIsEtat(true);
        }

        $data->isIsEtat() ?  $data->setIsEtat(false) :  $data->setIsEtat(true);

        $this->entityManager->flush();
    }
}