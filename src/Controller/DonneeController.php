<?php

namespace App\Controller;

use App\Entity\Donnee;
use App\Entity\Monnaie;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Exception\ConnectionException;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



/**
 * Controller de l'entité Donnée :
 * Endpoints :
 *  GET "/api/donnee" Récupération de toutes les données
 *  GET "/api/donnee/{monnaie}" Récupération de toutes les données en fonction de la monnaie
 *  POST "/api/donnee" Insertion des données d'un fichier csv
 */
#[Route('api/donnee')]
class DonneeController extends AbstractController
{
    #[Route('', name: 'app_donnee_list', methods: ["GET"])]
    public function listAll(EntityManagerInterface $entityManager): JsonResponse
    {
        try{
            // "SELECT * FROM donnee;"
            $donnees = $entityManager->getRepository(Donnee::class)->findAll();
            $res = [];
            foreach($donnees as $donnee){
                $res[] = ["monnaie" => $donnee->getMonnaie()];
                $res[] = ["id" => $donnee->getId()];
                $res[] = ["date" => $donnee->getDate_change()];
                $res[] = ["taux" => $donnee->getTaux_change()];
            }        

            return ($this->json($res));
        }catch(ConnectionException $exeption){
            return $this->json(['status' => 'error', 'message' => "La connexion au serveur de base de donnée n'a pas pu être effectuée !"], 500);
        }
        
    }

    #[Route('/{monnaie}', name: 'app_donnee_list_by_monnaie', methods: ["GET"])]
    public function listAllByMonnaie(EntityManagerInterface $entityManager, string $monnaie): JsonResponse
    {
        try{
            // "SELECT * FROM donnee WHERE monnaie = '$monnaie';"
            $donnees = $entityManager->getRepository(Donnee::class)->findByMonnaie($monnaie);
            $res = [];
            foreach($donnees as $donnee){
                $res[] = ["monnaie" => $donnee->getMonnaie()];
                $res[] = ["id" => $donnee->getId()];
                $res[] = ["date" => $donnee->getDate_change()];
                $res[] = ["taux" => $donnee->getTaux_change()];
            }        

            return ($this->json($res));
        }catch(ConnectionException $exeption){
            return $this->json(['status' => 'error', 'message' => "La connexion au serveur de base de données n'a pas pu être effectuée !"], 500);
        }
    }


    #[Route('/', name: 'app_donnee_post_data', methods: ["POST"])]
    public function postData(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {   
        $file_data = $request->files->get('csvData');
        if($file_data && $file_data->isValid()){
            $rows = [];
            $handle = fopen($file_data->getRealPath(), 'r');
            $first_row = 0;
            $lengh = 0;
            $file_monnaies = [];
            $bdd_monnaies = [];
            try{
                $bdd_monnaies_object = $entityManager->getRepository(Monnaie::class)->findAll();
                foreach($bdd_monnaies_object as $obj){
                    $bdd_monnaies[] = $obj->getMonnaie(); 
                }
            }catch(ConnectionException $exception){
                return $this->json(['status' => 'error', 'message' => "La connexion au serveur de base de données n'a pas pu être effectuée !"], 500);
            }
            while (($data = fgetcsv($handle)) !== false) {
                $rows[] = $data;
                if($first_row == 0){ // Si première ligne : On teste que le fichier fourni est bien conforme
                    $first_row = 1;
                    $lengh = count($data);
                    if($lengh <= 1) // sinon On envoie une erreur
                        return $this->json(['status' => 'error', 'message' => 'Fichier CSV invalide 1 seule colonne ou moins'], 400);
                    else{
                        // Si le fichier est valide on récupère les valeurs 
                        for($i = 1; $i < $lengh; $i++){
                            $file_monnaie = substr($data[$i], 0, strpos($data[$i], "_"));
                            if(in_array($file_monnaie, $bdd_monnaies)){
                                $file_monnaies[] = $file_monnaie;
                            }else{
                                try{
                                    $monnaie = new Monnaie();
                                    $monnaie->setMonnaie($file_monnaie);
                                    $entityManager->persist($monnaie);
                                    $entityManager->flush();
                                }catch(ConnectionException $exception){
                                    return $this->json(['status' => 'error', 'message' => "La connexion au serveur de base de données n'a pas pu être effectuée !"], 500);
                                }
                                $file_monnaies[] = $file_monnaie;
                            }

                        }
                    }
                }else{
                    try{
                        // Pour les lignes suivantes on crée une donnée et on isert dans la base de données 
                        for($i = 1; $i < $lengh; $i++){
                            $donnee = new Donnee();
                            $donnee->setMonnaie($file_monnaies[$i - 1]);
                            $donnee->setDate_change(new DateTime($data[0]));
                            $donnee->setTaux_change($data[$i]);
                            $entityManager->persist($donnee);
                            $entityManager->flush();
                        }
                    }catch(ConnectionException $e){
                        return $this->json(['status' => 'error', 'message' => "La connexion au serveur de base de données n'a pas pu être effectuée !"], 500);
                    }catch(ForeignKeyConstraintViolationException $exeption){
                        return $this->json(['status' => 'error', 'message' => "Une erreur de contrainte de clé étrangère a été détectée !"], 500);
            
                    }
                   
                }
            }
            fclose($handle);

            return $this->json(['status' => 'success', 'data' => $rows], 200);

        }
        else
            return $this->json(['status' => 'error', 'message' => 'Fichier CSV invalide ou manquant'], 400);

    }
}
