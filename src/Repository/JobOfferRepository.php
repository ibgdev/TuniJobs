<?php

namespace App\Repository;

use App\Entity\JobOffer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<JobOffer>
 */
class JobOfferRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, JobOffer::class);
    }

//    /**
//     * @return JobOffer[] Returns an array of JobOffer objects
//     */
    public function findByCompanyId($value): array
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.entreprise = :val')
            ->setParameter('val', $value)
            ->orderBy('j.id', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findAllRecent(): array{
        return $this->createQueryBuilder('j')
        ->orderBy("j.datePublication","desc")
        ->setMaxResults(4)
        ->getQuery()
        ->getResult();
    }

//    public function findOneBySomeField($value): ?JobOffer
//    {
//        return $this->createQueryBuilder('j')
//            ->andWhere('j.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
