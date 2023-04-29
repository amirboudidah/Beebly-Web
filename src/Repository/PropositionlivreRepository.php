<?php

namespace App\Repository;

use App\Entity\Propositionlivre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use DoctrineExtensions\Query\Mysql\Month;
use DoctrineExtensions\Query\Mysql\Year;


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
    public function findNonTreated(string $parameter): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        return $this->getEntityManager()->createQueryBuilder()
            ->select('p')
            ->from('App\Entity\Propositionlivre', 'p')
            ->leftJoin('App\Entity\Estimationoffrelivre', 'e', 'WITH', 'p.idpropositionlivre = e.idproposition')
            ->where('e.idproposition IS NULL')
            ->andWhere(
                $qb->expr()->orX(
                    $qb->expr()->like('p.idpropositionlivre', ':searchTerm'),
                    $qb->expr()->like('p.titrelivre', ':searchTerm'),
                    $qb->expr()->like('p.editon', ':searchTerm'),
                    $qb->expr()->like('p.dateproposition', ':searchTerm'),
                    $qb->expr()->like('p.descriptionetat', ':searchTerm')
                )
            )
            ->setParameter('searchTerm', '%' . $parameter . '%')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Propositionlivre[] Returns an array of Propositionlivre objects
     */
    public function findTreated(string $parameter): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        return $this->getEntityManager()->createQueryBuilder()
            ->select('p')
            ->from('App\Entity\Propositionlivre', 'p')
            ->join('App\Entity\Estimationoffrelivre', 'e', 'WITH', 'p.idpropositionlivre = e.idproposition')
            ->Where(
                $qb->expr()->orX(
                    $qb->expr()->like('p.idpropositionlivre', ':searchTerm'),
                    $qb->expr()->like('p.titrelivre', ':searchTerm'),
                    $qb->expr()->like('p.editon', ':searchTerm'),
                    $qb->expr()->like('p.dateproposition', ':searchTerm'),
                    $qb->expr()->like('p.descriptionetat', ':searchTerm')
                )
            )
            ->setParameter('searchTerm', '%' . $parameter . '%')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Propositionlivre[] Returns an array of Propositionlivre objects
     */
    public function findAllPropositions(string $parameter): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        return $this->getEntityManager()->createQueryBuilder()
            ->select('p')
            ->from('App\Entity\Propositionlivre', 'p')
            ->Where(
                $qb->expr()->orX(
                    $qb->expr()->like('p.idpropositionlivre', ':searchTerm'),
                    $qb->expr()->like('p.titrelivre', ':searchTerm'),
                    $qb->expr()->like('p.editon', ':searchTerm'),
                    $qb->expr()->like('p.dateproposition', ':searchTerm'),
                    $qb->expr()->like('p.descriptionetat', ':searchTerm')
                )
            )
            ->setParameter('searchTerm', '%' . $parameter . '%')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return void Returns a stat
     */
    public function getStat(int $month, int $year): array
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $queryBuilder->select('COUNT(p.idpropositionlivre)', 'p.dateproposition')
            ->from('App\Entity\Propositionlivre', 'p')
            ->where($queryBuilder->expr()->eq('month(p.dateproposition)', ':month'))
            ->andWhere($queryBuilder->expr()->eq('year(p.dateproposition)', ':year'))
            ->groupBy('p.dateproposition')
            ->orderBy('p.dateproposition')
            ->setParameter('month', $month)
            ->setParameter('year', $year);
        $query = $queryBuilder->getQuery();
        return $query->getResult();
    }
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



