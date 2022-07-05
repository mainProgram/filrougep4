<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Regex;

class FunctionService
{
    public function calculPrix($data)
    {
        $prix = 0;

        foreach($data->getBurgers() as $burger)
            $prix+= $burger->getPrix();

        foreach($data->getFrites() as $frite)
            $prix+= $frite->getPrix();

        foreach($data->getTailleBoissons() as $boisson)
            $prix+= $boisson->getPrix();

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