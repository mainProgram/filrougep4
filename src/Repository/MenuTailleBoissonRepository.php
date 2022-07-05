<?php

namespace App\Repository;

use App\Entity\MenuTailleBoisson;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MenuTailleBoisson>
 *
 * @method MenuTailleBoisson|null find($id, $lockMode = null, $lockVersion = null)
 * @method MenuTailleBoisson|null findOneBy(array $criteria, array $orderBy = null)
 * @method MenuTailleBoisson[]    findAll()
 * @method MenuTailleBoisson[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MenuTailleBoissonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MenuTailleBoisson::class);
    }

    public function add(MenuTailleBoisson $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(MenuTailleBoisson $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return MenuTailleBoisson[] Returns an array of MenuTailleBoisson objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?MenuTailleBoisson
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
