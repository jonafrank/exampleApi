<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

/**
 * CategoryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CategoryRepository extends EntityRepository
{
    public function getAllWithProducts()
    {
        return $this->createQueryBuilder('c')
            ->select('c, p')
            ->leftJoin('c.products', 'p')
            ->getQuery()
            ->getArrayResult();
    }

    public function getOneWithProducts($id)
    {
        return $this->createQueryBuilder('c')
            ->select('c, p')
            ->leftJoin('c.products', 'p')
            ->where('c.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult(Query::HYDRATE_ARRAY);
    }
}
