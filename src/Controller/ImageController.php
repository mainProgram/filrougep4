<?php

namespace App\Controller;

use App\Entity\Burger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ImageController extends AbstractController
{
    public function __invoke(Request $request, EntityManagerInterface $manager)
    {
        $burger = new Burger();

        $file = $request->files->get('image');
        
        // $test = stream_get_contents(fopen($file->getRealPath(), "rb"));
        // dd($test);
        $nom = ($request->request->get("nom"));
        $prix = ($request->request->get("prix"));

        $burger->setNom($nom);  
        $burger->setPrix($prix);

        // $burger->setImage(base64_encode($file));
        $burger->setImage(stream_get_contents(fopen($file->getRealPath(), "rb")));

        $manager->persist($burger);
        $manager->flush();

        return $this->json($burger, 200);
    }
}
