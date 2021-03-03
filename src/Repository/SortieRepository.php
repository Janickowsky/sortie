<?php

namespace App\Repository;

use App\Entity\Site;
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

    public function getAllSortie($user, $datas = null){
        $req = $this->createQueryBuilder('sortie');

        if($user){
            $req->innerJoin('sortie.campus','site')->addSelect('site');
            $req->innerJoin('sortie.etat','etat')->addSelect('etat');
            $req->leftJoin('sortie.participants','user')->addSelect('user');

            $req->Where("etat.libelle NOT LIKE 'Clôturée'");

            if(($datas['site'])){
                $req->andWhere('sortie.campus = :Site')->setParameter('Site',$datas['site']);
            }

            if(($datas['nomSortie'])){
                $req->andWhere('sortie.nom LIKE :nomSortie')->setParameter('nomSortie', '%'.$datas['nomSortie'].'%');
            }

            if($datas['dateDepart'] && $datas['dateFin']){
                $req->andWhere('sortie.dateHeureDebut BETWEEN :dateDepart AND :dateFin')
                    ->setParameter('dateDepart', $datas['dateDepart'])
                    ->setParameter('dateFin', $datas['dateFin']->setTime(23,59,00));
            }


           if($datas['orgaTri'] or $datas['passeTri'] or $datas['inscritTri'] or $datas['nonInscritTri']) {
               if($datas['orgaTri']){
                   $req->andWhere("sortie.organisateur = :user")->setParameter('user',$user);
               }
               if($datas['passeTri']){
                   $req->andWhere("etat.libelle = 'Passée'");
               }
               if($datas['inscritTri']){
                   $req->andWhere(":user MEMBER OF sortie.participants")->setParameter('user',$user);
                   if($datas['nonInscritTri']){
                       $req->orWhere(":user NOT MEMBER OF sortie.participants")->setParameter('user',$user);
                       $req->andWhere("etat.libelle LIKE 'Ouverte'");
                   }
                   $req->andWhere("sortie.organisateur <> :user")->setParameter('user',$user);
               }else{
                   if($datas['nonInscritTri']){
                       $req->andWhere(":user NOT MEMBER OF sortie.participants")->setParameter('user',$user);
                       $req->andWhere("sortie.organisateur <> :user")->setParameter('user',$user);
                       $req->andWhere("etat.libelle LIKE 'Ouverte'");
                   }
               }
           }else{
               $req->andWhere("etat.libelle LIKE 'Ouverte'");
               $req->orWhere("etat.libelle LIKE 'Annulée'");
           }
           $req->orderBy('sortie.dateHeureDebut', 'desc');


        }
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
