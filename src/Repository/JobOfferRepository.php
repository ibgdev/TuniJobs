<?php

namespace App\Repository;

use App\Entity\JobOffer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

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

    public function searchAndPaginate(Request $request, int $limit = 3): Paginator
    {
        $page = max(1, (int) $request->query->get('page', 1));
        $search = $request->query->get('search');
        $category = $request->query->get('category');
        $location = $request->query->get('location');

        $qb = $this->createQueryBuilder('j')
            ->leftJoin('j.categorie', 'c')
            ->addSelect('c');

        if ($search) {
            $qb->andWhere('LOWER(j.titre) LIKE :search')
                ->setParameter('search', '%' . strtolower($search) . '%');
        }

        if ($category) {
            $qb->andWhere('c.id = :category')
                ->setParameter('category', $category);
        }

        if ($location) {
            $qb->andWhere('j.location = :location')
                ->setParameter('location', $location);
        }

        $qb->orderBy('j.datePublication', 'DESC')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);

        return new Paginator($qb->getQuery(), true);
    }
    public function findAllRecent(): array
    {
        return $this->createQueryBuilder('j')
            ->orderBy("j.datePublication", "desc")
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
