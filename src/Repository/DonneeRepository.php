<?php

namespace App\Repository;

use App\Entity\Donnee;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Parameter;

/**
 * @extends ServiceEntityRepository<Donnee>
 *
 * @method Donnee|null find($id, $lockMode = null, $lockVersion = null)
 * @method Donnee|null findOneBy(array $criteria, array $orderBy = null)
 * @method Donnee[]    findAll()
 * @method Donnee[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DonneeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Donnee::class);
    }

    /**
     * @return Donnee Returns a Donnee objects
     */
    public function findOneByDate_changeAndMonnaie($date_change, $monnaie){
        // return $this->createQueryBuilder("d")
        //             ->where("d.date_change = :date")
        //             ->setParameter("date", $date_change)
        //             ->andWhere("d.monnaie = :monnaie")
        //             ->setParameter("monnaie", $monnaie)
        //             ->getQuery()
        //             ->getResult();
        return $this->getEntityManager()->createQuery(
                    'SELECT d.id
                    FROM App\Entity\Donnee d
                    WHERE d.date_change = :date AND d.monnaie = :monnaie')
                    ->setParameter('date', $date_change)
                    ->setParameter('monnaie', $monnaie)
                    ->getResult();
    }
    // public function findOneByDate_changeAndMonnaie($date_change, $monnaie){
    //     return $this->createQueryBuilder("d")
    //                 ->where("d.date_change = :date")
    //                 ->andWhere("d.monnaie = :monnaie")
    //                 ->setParameter("date", $date_change)
    //                 ->setParameter("monnaie", $monnaie)
    //                 ->getQuery()
    //                 ->getResult();
    // }

//    /**
//     * @return Donnee[] Returns an array of Donnee objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Donnee
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
