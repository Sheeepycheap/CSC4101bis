<?php

namespace App\Repository;

use App\Entity\CollectionDeVoiture;
use App\Entity\Voiture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CollectionDeVoiture>
 *
 * @method CollectionDeVoiture|null find($id, $lockMode = null, $lockVersion = null)
 * @method CollectionDeVoiture|null findOneBy(array $criteria, array $orderBy = null)
 * @method CollectionDeVoiture[]    findAll()
 * @method CollectionDeVoiture[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CollectionDeVoitureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CollectionDeVoiture::class);
    }

    public function add(CollectionDeVoiture $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CollectionDeVoiture $entity, bool $flush = false): void
    {
        $VoitureRepository = $this->getEntityManager()->getRepository(Voiture::class);

        // clean the [objets] properly
        $voitures = $entity->getVoitures();
        foreach($voitures as $voiture) {
            $VoitureRepository->remove($voiture, $flush);
        }
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }

    //     $this->getEntityManager()->remove($entity);

    //     if ($flush) {
    //         $this->getEntityManager()->flush();
    //     }
    // 
    }

//    /**
//     * @return CollectionDeVoiture[] Returns an array of CollectionDeVoiture objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?CollectionDeVoiture
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
