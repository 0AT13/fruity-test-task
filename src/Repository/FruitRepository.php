<?php

namespace App\Repository;

use App\Entity\Fruit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\InputBag;

/**
 * @extends ServiceEntityRepository<Fruit>
 *
 * @method Fruit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Fruit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Fruit[]    findAll()
 * @method Fruit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FruitRepository extends ServiceEntityRepository
{
    const ITEM_PER_PAGE = 10;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Fruit::class);
    }

    public function save(Fruit $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Fruit $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findOneByName(string $value): ?Fruit
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.name = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getPaginator(int $offset, InputBag $params, bool $getAllResults = false)
    {
        $query = $this->createQueryBuilder('c');

        if (!empty($params->getIterator()->getArrayCopy())) {
            foreach ($params->getIterator() as $key => $item) {
                if (!empty($item) && $key !== 'offset') {
                    $query = $query->andWhere('c.' . $key . ' = :' . $key)
                        ->setParameter($key, $item);
                }
            }
        };

        $query = $query->orderBy('c.createdAt', 'DESC');

        if (!$getAllResults) {
            $query = $query->setMaxResults(self::ITEM_PER_PAGE)
                ->setFirstResult($offset * self::ITEM_PER_PAGE);
        }

        $query = $query
            ->getQuery();

        return new Paginator($query);
    }
}
