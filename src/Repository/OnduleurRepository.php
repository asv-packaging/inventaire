<?php

namespace App\Repository;

use App\Entity\Onduleur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Onduleur>
 *
 * @method Onduleur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Onduleur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Onduleur[]    findAll()
 * @method Onduleur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OnduleurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Onduleur::class);
    }

    public function save(Onduleur $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Onduleur $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByCritere($recherche, $search)
    {
        $queryBuilder = $this->createQueryBuilder('e');

        if ($recherche === 'nom' || $recherche === 'capacite' || $recherche === 'type_prise' || $recherche === 'numero_serie' || $recherche === 'date_installation' || $recherche === 'date_achat' || $recherche === 'date_garantie' || $recherche === 'commentaire')
        {
            $queryBuilder
                ->andWhere('e.'.$recherche.' LIKE :search')
                ->setParameter('search', '%' . $search . '%');
        }
        elseif ($recherche === 'emplacement_id')
        {
            $queryBuilder->join('e.emplacement', 'emp')
                ->andWhere('emp.nom LIKE :search')
                ->setParameter('search', '%' . $search . '%');
        }
        elseif ($recherche === 'entreprise_id')
        {
            $queryBuilder->join('e.entreprise', 'ent')
                ->andWhere('ent.nom LIKE :search')
                ->setParameter('search', '%' . $search . '%');
        }

        return $queryBuilder->getQuery();
    }

    public function countOnduleurs(): int
    {
        return $this->createQueryBuilder('o')
            ->select('count(o.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

//    /**
//     * @return Onduleur[] Returns an array of Onduleur objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('o.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Onduleur
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
