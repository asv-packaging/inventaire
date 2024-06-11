<?php

namespace App\Repository;

use App\Entity\Tablette;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Tablette>
 *
 * @method Tablette|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tablette|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tablette[]    findAll()
 * @method Tablette[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TabletteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tablette::class);
    }

    public function save(Tablette $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Tablette $entity, bool $flush = false): void
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
        elseif($recherche === 'utilisateur_id')
        {
            $queryBuilder->join('e.utilisateur', 'u')
                ->andWhere('u.nom LIKE :search OR u.prenom LIKE :search')
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

    public function countTablettes(): int
    {
        return $this->createQueryBuilder('t')
            ->select('count(t.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

//    /**
//     * @return Tablette[] Returns an array of Tablette objects
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

//    public function findOneBySomeField($value): ?Tablette
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
