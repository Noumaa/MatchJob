<?php

namespace App\Repository;

use App\Entity\DemandStatusChange;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DemandStatusChange>
 *
 * @method DemandStatusChange|null find($id, $lockMode = null, $lockVersion = null)
 * @method DemandStatusChange|null findOneBy(array $criteria, array $orderBy = null)
 * @method DemandStatusChange[]    findAll()
 * @method DemandStatusChange[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DemandStatusChangeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DemandStatusChange::class);
    }

    public function save(DemandStatusChange $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(DemandStatusChange $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return DemandStatusChange[] Returns an array of DemandStatusChange objects
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

//    public function findOneBySomeField($value): ?DemandStatusChange
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
