<?php

namespace App\Repository;

use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }

    public function getAllSortie($user){
        $req = $this->createQueryBuilder('sortie')
            ->innerJoin('sortie.etat', 'etat')->addSelect('etat')
            ->where("etat.libelle = 'Ouverte'")
            ->orWhere("sortie.organisateur = :user")
            ->setParameter('user',$user)
            ->orderBy('sortie.dateHeureDebut', 'desc');

        return $req->getQuery()->getResult();
    }

    public function getSortieById(int $id){
        $req = $this->createQueryBuilder('sortie')
            ->leftJoin('sortie.participants','user')->addSelect('user')
            ->innerJoin('sortie.lieu', 'lieu')->addSelect('lieu')
            ->innerJoin('lieu.ville', 'ville')->addSelect('ville')
            ->where('sortie.id = :id')
            ->setParameter('id', $id);

        return $req->getQuery()->getSingleResult();
    }
}
