<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Regex;

class FunctionService
{
    public function calculPrix($data)
    {
        $prix = 0;

        foreach($data->getMenuburgers() as $burger)
            $prix+= $burger->getBurger()->getPrix() * $burger->getQuantite();

        foreach($data->getMenufrites() as $frite)
            $prix+= $frite->getFrite()->getPrix() * $frite->getQuantite();

        foreach($data->getMenuTailles() as $boisson)
            $prix+= $boisson->getTaille()->getPrix() * $boisson->getQuantite();

        $prix -= $prix * 0.05;
        $data->setPrix($prix);
    }

    public function image(Request $request)
    {
        $photo = $request->files->get('image');
        
        if(isset($photo))
            return fopen($photo->getRealPath(), "rb");
    }
}