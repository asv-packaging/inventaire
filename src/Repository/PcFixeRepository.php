<?php

namespace App\Repository;

use App\Entity\PcFixe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PcFixe>
 *
 * @method PcFixe|null find($id, $lockMode = null, $lockVersion = null)
 * @method PcFixe|null findOneBy(array $criteria, array $orderBy = null)
 * @method PcFixe[]    findAll()
 * @method PcFixe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PcFixeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PcFixe::class);
    }

    public function save(PcFixe $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(PcFixe $entity, bool $flush = false): void
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
                ->setParameter('search', '%' . $search . '%');
        }
        elseif($recherche === 'systeme_exploitation_id')
        {
            $queryBuilder->join('e.systeme_exploitation', 'se')
                ->andWhere('se.nom LIKE :search')
                ->setParameter('search', '%' . $search . '%');
        }
        elseif($recherche === 'utilisateur_id')
        {
            $queryBuilder->join('e.utilisateur', 'u')
                ->andWhere('u.nom LIKE :search')
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

    public function countPcFixes(): int
    {
        return $this->createQueryBuilder('p')
            ->select('count(p.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

//    /**
//     * @return PcFixe[] Returns an array of PcFixe objects
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

//    public function findOneBySomeField($value): ?PcFixe
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
