<?php

namespace App\Repository;

use App\Entity\PcPortable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PcPortable>
 *
 * @method PcPortable|null find($id, $lockMode = null, $lockVersion = null)
 * @method PcPortable|null findOneBy(array $criteria, array $orderBy = null)
 * @method PcPortable[]    findAll()
 * @method PcPortable[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PcPortableRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PcPortable::class);
    }

    public function save(PcPortable $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(PcPortable $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByCritere($recherche, $search)
    {
        $queryBuilder = $this->createQueryBuilder('e');

        if ($recherche === 'nom' || $recherche === 'ip' || $recherche === 'numero_serie' || $recherche === 'date_installation' || $recherche === 'date_achat' || $recherche === 'date_garantie' || $recherche === 'commentaire')
        {
            $queryBuilder
                ->andWhere('e.'.$recherche.' LIKE :search')
                ->setParameter('search', '%' . $search . '%')
                ->orderBy('e.id', 'DESC');
        }
        elseif($recherche === 'systeme_exploitation_id')
        {
            $queryBuilder->join('e.systeme_exploitation', 'se')
                ->andWhere('se.nom LIKE :search')
                ->setParameter('search', '%' . $search . '%')
                ->orderBy('e.id', 'DESC');
        }
        elseif($recherche === 'utilisateur_id')
        {
            $queryBuilder->join('e.utilisateur', 'u')
                ->andWhere('u.nom LIKE :search')
                ->setParameter('search', '%' . $search . '%')
                ->orderBy('e.id', 'DESC');
        }
        elseif ($recherche === 'emplacement_id')
        {
            $queryBuilder->join('e.emplacement', 'emp')
                ->andWhere('emp.nom LIKE :search')
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

    public function countPcPortables(): int
    {
        return $this->createQueryBuilder('p')
            ->select('count(p.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

//    /**
//     * @return PcPortable[] Returns an array of PcPortable objects
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

//    public function findOneBySomeField($value): ?PcPortable
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
