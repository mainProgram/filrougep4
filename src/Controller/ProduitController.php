<?php

namespace App\Controller;

use App\Repository\FriteRepository;
use App\Repository\BoissonRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProduitController extends AbstractController
{
    // #[Route('/my_catalogue', name: 'catalogue')]
    // public function catalogue(FriteRepository $frite, BoissonRepository $boisson)
    // {  
    //     $tabComplements[] = $frite->findAll();
    //     $tabComplements[] = $boisson->findAll();

    //     $complementsJSON = $serializer->serialize($tabComplements, "json", [ "groups" => ["burger:list"]]);

    //     return new JsonResponse($complementsJSON, Response::HTTP_OK, [], true);

    // }

    #[Route('/all_complements', name: 'complements')]
    public function complements(SerializerInterface $serializer, FriteRepository $frite, BoissonRepository $boisson)
    {  
        $tabComplements[] = $frite->findAll();
        $tabComplements[] = $boisson->findAll();

        $complementsJSON = $serializer->serialize($tabComplements, "json", [ "groups" => ["burger:list"]]);

        return new JsonResponse($complementsJSON, Response::HTTP_OK, [], true);
    }
}
