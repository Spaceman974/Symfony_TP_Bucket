<?php

namespace App\Repository;

use App\Entity\Wish;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Wish>
 */
class WishRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Wish::class);
    }

    public function findWishByDate(int $page = 1)
    {

        $qb = $this->createQueryBuilder('wish'); //Nom de la table en param (alias)
        $qb
            ->andWhere('wish.isPublished = true ')
            ->addOrderBy('wish.dateCreated', 'DESC');

        $query = $qb->getQuery();
        $query->setMaxResults(10);
        $offset = ($page - 1) * 10; //Calcul de l'offset pour le changement de page. Si on est sur la page 1, offset à 0, si on est sur la page 2, offset à 50, etc.
        $query->setFirstResult($offset);
        return $query->getResult();
    }
}
