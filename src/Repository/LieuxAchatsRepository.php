<?php

namespace App\Repository;

use App\Entity\LieuxAchats;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LieuxAchats>
 *
 * @method LieuxAchats|null find($id, $lockMode = null, $lockVersion = null)
 * @method LieuxAchats|null findOneBy(array $criteria, array $orderBy = null)
 * @method LieuxAchats[]    findAll()
 * @method LieuxAchats[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LieuxAchatsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LieuxAchats::class);
    }

    public function add(LieuxAchats $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(LieuxAchats $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return LieuxAchats[] Returns an array of LieuxAchats objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('l.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?LieuxAchats
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
