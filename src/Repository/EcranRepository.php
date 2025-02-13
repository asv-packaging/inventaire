<?php

namespace App\Repository;

use App\Entity\Ecran;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ecran>
 *
 * @method Ecran|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ecran|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ecran[]    findAll()
 * @method Ecran[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EcranRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ecran::class);
    }

    public function save(Ecran $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Ecran $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByCritere($recherche, $search)
    {
        $queryBuilder = $this->createQueryBuilder('e');

        if ($recherche === 'nom' || $recherche === 'numero_serie' || $recherche === 'date_installation' || $recherche === 'date_achat' || $recherche === 'date_garantie' || $recherche === 'commentaire')
        {
            $queryBuilder
                ->andWhere('e.'.$recherche.' LIKE :search')
                ->setParameter('search', '%' . $search . '%')
                ->orderBy('e.id', 'DESC');
        }
        elseif ($recherche === 'utilisateur_id')
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


    public function countEcrans(): int
    {
        return $this->createQueryBuilder('e')
            ->select('count(e.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

//    /**
//     * @return Ecran[] Returns an array of Ecran objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Ecran
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
