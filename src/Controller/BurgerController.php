<?php

namespace App\Controller;

use App\Entity\Burger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\Routing\Annotation\Route;
// use ApiPlatform\Core\Validator\ValidatorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class BurgerController extends AbstractController
{
    public function __invoke(Request $request, ValidatorInterface $validator, TokenStorageInterface $tokenStorage, SerializerInterface $serializer, EntityManagerInterface $entityManager): JsonResponse
    {
        $burger = $serializer->deserialize($request->getContent(), Burger::class, 'json');

        $errors = $validator->validate($burger);
        if (count($errors) > 0) 
        {
            $errorsString =$serializer->serialize($errors,"json");
            return new JsonResponse( $errorsString, Response::HTTP_BAD_REQUEST, [], true);
        }

        $burger->setUser($tokenStorage->getToken()->getUser());

        $entityManager->persist($burger);
        $entityManager->flush();
        
        $result =$serializer->serialize(['code'=>Response::HTTP_CREATED, 'data'=> $burger], "json", ["groups"=>["burger:detail"]]);
        return new JsonResponse($result, Response::HTTP_CREATED, [], true); 
    }
}
