<?php

namespace App\Repository;

use App\Entity\Message;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Message|null find($id, $lockMode = null, $lockVersion = null)
 * @method Message|null findOneBy(array $criteria, array $orderBy = null)
 * @method Message[]    findAll()
 * @method Message[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Message::class);
    }

    // /**
    //  * @return Message[] Returns an array of Message objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
    public function findRangeMessages($ip, $user_id = null)
    {
        $qb = $this->createQueryBuilder('m')
            ->andWhere('m.time > :time')
            ->setParameter('time', new \DateTime('now - 1 minute'));
        if (!is_null($user_id))
            $qb
                ->andWhere('m.user = :user')
                ->setParameter('user', $user_id);

           return count($qb
                ->orWhere('m.ip = :ip')
                ->andWhere('m.time > :time')
                ->setParameter('time', new \DateTime('now - 1 minute'))
                ->setParameter('ip', $ip)
                ->getQuery()
                ->getResult())<2;
    }

    public function findByUserSlug($slug) {
        return $qb = $this->createQueryBuilder('m')
            ->innerJoin('m.user', 'u')
            ->andWhere('u.slug = :slug')
            ->setParameter('slug', $slug)
            ->getQuery()
            ->getResult();
    }

    /*
    public function findOneBySomeField($value): ?Message
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
