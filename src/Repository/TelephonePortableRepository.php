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

    public function findByCritere($recherche, $search)
    {
        $queryBuilder = $this->createQueryBuilder('e');

        if ($recherche === 'ligne' || $recherche === 'imei1' || $recherche === 'imei2' || $recherche === 'numero_serie' || $recherche === 'date_installation' || $recherche === 'date_achat' || $recherche === 'date_garantie' || $recherche === 'commentaire')
        {
            $queryBuilder
                ->andWhere('e.'.$recherche.' LIKE :search')
                ->setParameter('search', '%' . $search . '%')
                ->orderBy('e.id', 'DESC');
        }
        elseif($recherche === 'utilisateur_id')
        {
            $queryBuilder->join('e.utilisateur', 'u')
                ->andWhere('u.nom LIKE :search OR u.prenom LIKE :search')
                ->setParameter('search', '%' . $search . '%')
                ->orderBy('e.id', 'DESC');
        }
        elseif ($recherche === 'entreprise_id')
        {
            $queryBuilder->join('e.entreprise', 'ent')
                ->andWhere('ent.nom LIKE :search')
                ->setParameter('search', '%' . $search . '%')
                ->orderBy('e.id', 'DESC');
        }

        return $queryBuilder->getQuery();
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
