<?php

namespace App\Controller;

use App\Repository\ZoneRepository;
use App\Entity\Commande;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ZoneController extends AbstractController
{
    public function __invoke(Request $request, ZoneRepository $zone)
    {
        $qb = $zone->createQueryBuilder('zone')
                    ->select('zone.id')
                    ->addSelect('zone.nom')
                    ->join('App\Entity\Commande', 'com', 'WITH', 'com.zone  = zone.id')
                    ->where('com.etat = ?1')
                    ->setParameter(1, 'termine')
                    ->distinct()
                    ->getQuery();
            return $qb->execute();
    }
}
