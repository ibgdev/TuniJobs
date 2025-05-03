<?php

namespace App\Repository;

use App\Entity\Application;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Application>
 */
class ApplicationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Application::class);
    }

//    /**
//     * @return Application[] Returns an array of Application objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

public function findByEntreprise($value): array
{
    return $this->createQueryBuilder('a')
        ->leftJoin('a.job_offer', 'j') // ← le nom de la relation dans Application
        ->leftJoin('j.entreprise', 'e') // ← le nom de la relation dans JobOffer
        ->andWhere('e.id = :val') // ← filtre sur l’entreprise
        ->setParameter('val', $value)
        ->orderBy('a.CreatedAt', 'DESC')
        ->getQuery()
        ->getResult();
}

}
