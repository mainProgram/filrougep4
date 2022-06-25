<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MailController extends AbstractController
{
    public function __invoke(Request $request, UserRepository $userR, EntityManagerInterface $manager)
    {
        $token = $request->get("token");

        $user = $userR->findOneBy(["token" => $token]);

        if(!$user)
            return new JsonResponse( ["error" => "Token invalide"], 400);

        if( $user->isIsActivated())
            return new JsonResponse( ["message" => "Le compte est déjà activé !"]);

        if( $user->getExpiredAt() < new \DateTime())
            return new JsonResponse( ["error" => "Token invalide"], 400);

        $user->setIsActivated(true);
        $manager->flush();

        return new JsonResponse( ["message" => "Le compte a été activé avec succès !"], 200);

    }
}
