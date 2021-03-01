<?php

namespace App\Repository;

use App\Entity\Lieu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Lieu|null find($id, $lockMode = null, $lockVersion = null)
 * @method Lieu|null findOneBy(array $criteria, array $orderBy = null)
 * @method Lieu[]    findAll()
 * @method Lieu[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LieuRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Lieu::class);
    }

    public function getLieuByIdVille($idVille){
        $req = $this->createQueryBuilder('lieu')
            ->where('lieu.ville = :id_ville')
            ->setParameter('id_ville', $idVille);

        return $req->getQuery()->getResult();
    }

    public function getLieuById($idLieu){
        $req = $this->createQueryBuilder('lieu')
            ->innerJoin('lieu.ville','ville')->addSelect('ville')
            ->where('lieu.id = :id_lieu')
            ->setParameter('id_lieu', $idLieu);

        return $req->getQuery()->getSingleResult();
    }


    public function getAllSortie(){
        $req = $this->createQueryBuilder('lieu')
            ->where("lieu.nom");

        return $req->getQuery()->getResult();
    }


    public function getAllLieu()
    {
        $req = $this->createQueryBuilder('lieu');

        return $req->getQuery()->getResult();
    }
    // /**
    //  * @return Lieu[] Returns an array of Lieu objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Lieu
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
