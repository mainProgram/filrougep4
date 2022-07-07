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
        return $data instanceof Burger or $data instanceof Frite or $data instanceof Boisson or $data instanceof Menu  or $data instanceof Produit;
    }

    public function persist($data)
    {

        if($data instanceof Boisson)
            $data->setPrix((0));
        elseif ($data instanceof Menu)
            $this->service->calculPrix($data);

        // $file = $data->getImageWrapper();
        // $img = file_get_contents($file);

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