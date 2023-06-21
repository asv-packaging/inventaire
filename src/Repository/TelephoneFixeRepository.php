<?php

namespace App\Repository;

use App\Entity\TelephoneFixe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TelephoneFixe>
 *
 * @method TelephoneFixe|null find($id, $lockMode = null, $lockVersion = null)
 * @method TelephoneFixe|null findOneBy(array $criteria, array $orderBy = null)
 * @method TelephoneFixe[]    findAll()
 * @method TelephoneFixe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TelephoneFixeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TelephoneFixe::class);
    }

    public function save(TelephoneFixe $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(TelephoneFixe $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return TelephoneFixe[] Returns an array of TelephoneFixe objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?TelephoneFixe
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
