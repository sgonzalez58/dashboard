<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Articles>
 *
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function add(Article $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Article $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function search($value, $conditions = array(), $type = 'id', $sens = 'ASC', $limit = null, $offset = null):array
    {
        $myQuery = $this->createQueryBuilder('a');
        $myQuery -> andWhere('a.nom LIKE :val');
        $myQuery -> setParameter(':val', '%'.$value.'%');
        foreach($conditions as $key => $myValue){
            if(is_array($myValue)){
                if($key == 'date_achat'){
                    if($myValue[0] == 'apres'){
                        $myQuery -> andWhere('a.'.$key.'> \''.$myValue[1].'\'');
                    }elseif($myValue[0] == 'avant'){
                        $myQuery -> andWhere('a.'.$key.'< \''.$myValue[1].'\'');
                    }else{
                        $myQuery -> andWhere('a.'.$key.' BETWEEN \''.$myValue[1].'\' AND \''.$myValue[2].'\'');
                    }
                }elseif($key == 'prix'){
                    if($myValue[0] == 'sup'){
                        $myQuery -> andWhere('a.'.$key.'>'.$myValue[1]);
                    }elseif($myValue[0] == 'inf'){
                        $myQuery -> andWhere('a.'.$key.'<'.$myValue[1]);
                    }else{
                        $myQuery -> andWhere('a.'.$key.' BETWEEN '.$myValue[1].' AND '.$myValue[2].'');
                    }
                }else{
                    $arrayQuery = '(a.'.$key.'='.array_shift($myValue)->getId();
                    foreach($myValue as $oneValue){
                        $arrayQuery = $arrayQuery.' OR a.'.$key.'='.($oneValue)->getId();
                    }
                    $arrayQuery = $arrayQuery.' )';
                    $myQuery-> andWhere($arrayQuery);
                }
            }elseif($key == 'date_garantie'){
                if($myValue == 'oui'){
                    $myQuery -> andWhere('a.'.$key.'>= \''.(date('Y-m-d', (new \DateTime())->getTimestamp())).'\'');
                }else{
                    $myQuery -> andWhere('a.'.$key.'< \''.(date('Y-m-d', (new \DateTime())->getTimestamp())).'\'');
                }
            }else{
                $myQuery -> andWhere('a.'.$key.'='.$myValue);
            }
        }
        $myQuery->orderBy('a.'.$type, $sens);
        $myQuery->setMaxResults($limit);
        $myQuery ->setFirstResult($offset);
        $myQuery = $myQuery->getQuery()->getResult();
        return $myQuery;
    }

//    /**
//     * @return Article[] Returns an array of Article objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Article
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
