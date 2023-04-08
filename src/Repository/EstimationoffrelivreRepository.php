<?php

namespace App\Repository;

use App\Entity\Estimationoffrelivre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Estimationoffrelivre>
 *
 * @method Estimationoffrelivre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Estimationoffrelivre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Estimationoffrelivre[]    findAll()
 * @method Estimationoffrelivre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EstimationoffrelivreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Estimationoffrelivre::class);
    }

    public function save(Estimationoffrelivre $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Estimationoffrelivre $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Estimationoffrelivre[] Returns an array of Estimationoffrelivre objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Estimationoffrelivre
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
