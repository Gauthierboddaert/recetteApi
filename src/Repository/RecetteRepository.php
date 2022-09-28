<?php

namespace App\Repository;

use App\Entity\Recette;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Recette>
 *
 * @method Recette|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recette|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recette[]    findAll()
 * @method Recette[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecetteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recette::class);
    }

    public function add(Recette $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Recette $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findRecetteByUser(int $userID)
    {
       return $this->createQueryBuilder('recette')
                   ->where('recette.user = :user')
                   ->setParameter('user', $userID)
                   ->getQuery()
                   ->getResult();
    }

    public function findUserByID(int $idUser){
        return $this->createQueryBuilder('recette')
                    ->innerJoin('recette.user', 'user')
                    ->where('recette.user = :idUser')
                    ->setParameter('idUser', $idUser)
                    ->getQuery()
                    ->getResult();
    }

    public function findFavoriteByUser(int $userID)
    {
        return $this->createQueryBuilder('recette')
                    ->leftJoin('recette.favories', 'recette_user')
                    ->where('recette_user.id = :userID')
                    ->setParameter('userID', $userID)
                    ->getQuery()
                    ->getResult();
    }
}
