<?php

namespace App\Repository;

use App\Entity\Imprimante;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Imprimante>
 *
 * @method Imprimante|null find($id, $lockMode = null, $lockVersion = null)
 * @method Imprimante|null findOneBy(array $criteria, array $orderBy = null)
 * @method Imprimante[]    findAll()
 * @method Imprimante[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImprimanteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Imprimante::class);
    }

    public function save(Imprimante $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Imprimante $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByCritere($recherche, $search)
    {
        $queryBuilder = $this->createQueryBuilder('e');

        if ($recherche === 'nom' || $recherche === 'ip' || $recherche === 'contrat' || $recherche === 'numero_serie' || $recherche === 'date_installation' || $recherche === 'date_achat' || $recherche === 'date_garantie' || $recherche === 'commentaire')
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

    public function countImprimantes(): int
    {
        return $this->createQueryBuilder('i')
            ->select('count(i.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

//    /**
//     * @return Imprimante[] Returns an array of Imprimante objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Imprimante
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
