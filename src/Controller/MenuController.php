<?php

namespace App\Controller;

use App\Entity\Menu;
use App\Services\FunctionService;
use App\Repository\FriteRepository;
use App\Repository\BurgerRepository;
use App\Repository\TailleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class MenuController extends AbstractController
{
    public function __invoke(Request $request, SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $em, BurgerRepository $repoBurger, FriteRepository $repoFrite, TailleRepository $repoTaille, FunctionService $service, TokenStorageInterface $ts)
    {
        $content = json_decode($request->getContent());

        $nom = $content->nom;
        $frites = $content->menufrites;
        $burgers = $content->menuburgers;
        $tailles = $content->menuTailles;

        $menu = new Menu();

        if($nom == "")
            return new JsonResponse( ["error" => "Le nom du menu ne doit pas être vide !"], 400);

        if(!$burgers)
            return new JsonResponse( ["error" => "Choisissez au moins un burger !"], 400);

        if(!$frites && !$tailles)
            return new JsonResponse( ["error" => "Choisissez au moins des frites ou une boisson !"], 400);

        $menu->setNom($nom);

        foreach($burgers as $b)
        {
            $burger =  $repoBurger->find($b->burger);
            $menu->addBurger($burger, $b->quantite);
        }

        foreach($frites as $f)
        {
            $frite =  $repoFrite->find($f->frite);
            $menu->addFrite($frite, $f->quantite);
        }

        foreach($tailles as $t)
        {
            $taille =  $repoTaille->find($t->taille);
            $menu->addTaille($taille, $t->quantite);
        }

        $user = $ts->getToken()->getUser();
        $menu->setUser($user);

        $service->calculPrix($menu);
        dd($menu);
        
        // $menu = $serializer->deserialize($request->getContent(), "App\Entity\Menu", 'json', ["groups" => ["menu:write"]]);

        // $errors = $validator->validate($menu);
        // if (count($errors) > 0) {
        //     $errorsString =$serializer->serialize($errors,"json");
        //     return new JsonResponse( $errorsString , Response::HTTP_BAD_REQUEST, [],true);
        // }

        // $menufrites = $menu->getMenuFrites();
        // $menuburgers = $menu->getMenuBurgers();
        // $menuTailles = $menu->getMenuTailles();

        // dd($menu);


        $em->persist($menu);
        $em->flush();

    }
}
