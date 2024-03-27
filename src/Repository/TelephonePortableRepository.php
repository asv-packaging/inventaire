<?php

namespace App\Repository;

use App\Entity\TelephonePortable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TelephonePortable>
 *
 * @method TelephonePortable|null find($id, $lockMode = null, $lockVersion = null)
 * @method TelephonePortable|null findOneBy(array $criteria, array $orderBy = null)
 * @method TelephonePortable[]    findAll()
 * @method TelephonePortable[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TelephonePortableRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TelephonePortable::class);
    }

    public function save(TelephonePortable $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(TelephonePortable $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function countTelephonePortables(): int
    {
        return $this->createQueryBuilder('t')
            ->select('count(t.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

//    /**
//     * @return Telephone[] Returns an array of Telephone objects
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

//    public function findOneBySomeField($value): ?Telephone
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
