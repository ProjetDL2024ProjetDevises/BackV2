<?php

namespace App\Controller;

use App\Entity\Monnaie;
use Doctrine\DBAL\Exception\ConnectionException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;


#[Route('/api/monnaie')]
class MonnaieController extends AbstractController
{

   
    #[Route("", name:"app_monnaie_list", methods:['GET'])]
    public function listAll(EntityManagerInterface $entityManager): JsonResponse
    {
        try{
             $monnaies = $entityManager->getRepository(Monnaie::class)->findAll();
            $res = [];
            foreach($monnaies as $monnaie){
                $res[] = $monnaie->getMonnaie();
            }        
            
            return ($this->json($res));
        }catch(ConnectionException $exeption){
            return $this->json(['status' => 'error', 'message' => "La connexion au serveur de base de donnée n'a pas pu être effectuée !"], 500);
        }
       
    }
}
