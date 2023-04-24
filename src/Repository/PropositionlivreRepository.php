<?php

namespace App\Repository;

use App\Entity\Propositionlivre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Propositionlivre>
 *
 * @method Propositionlivre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Propositionlivre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Propositionlivre[]    findAll()
 * @method Propositionlivre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PropositionlivreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Propositionlivre::class);
    }

    public function save(Propositionlivre $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Propositionlivre $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    /**
     * @return Propositionlivre[] Returns an array of Propositionlivre objects
     */
    public function findNonTreated(): array
    {

        return  $this->getEntityManager()
            ->createQuery("select p from App\Entity\Propositionlivre p LEFT JOIN 
                    App\Entity\Estimationoffrelivre e WITH  p.idpropositionlivre = e.idproposition 
                     WHERE e.idproposition IS NULL")
            ->getResult();
   }
    /**
     * @return Propositionlivre[] Returns an array of Propositionlivre objects
     */
    public function findTreated(): array
    {

        return  $this->getEntityManager()
            ->createQuery("select p from App\Entity\Propositionlivre p INNER JOIN 
                    App\Entity\Estimationoffrelivre e WITH  p.idpropositionlivre = e.idproposition ")
            ->getResult();
    }

//    /**
//     * @return Propositionlivre[] Returns an array of Propositionlivre objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Propositionlivre
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }


}
