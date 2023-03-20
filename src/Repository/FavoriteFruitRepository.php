<?php

namespace App\Repository;

use App\Entity\FavoriteFruit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FavoriteFruit>
 *
 * @method FavoriteFruit|null find($id, $lockMode = null, $lockVersion = null)
 * @method FavoriteFruit|null findOneBy(array $criteria, array $orderBy = null)
 * @method FavoriteFruit[]    findAll()
 * @method FavoriteFruit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FavoriteFruitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FavoriteFruit::class);
    }

    public function save(FavoriteFruit $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(FavoriteFruit $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
