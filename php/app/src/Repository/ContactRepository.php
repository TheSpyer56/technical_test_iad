<?php

namespace App\Repository;

use App\Entity\Contact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Contact>
 *
 * @method Contact|null find($id, $lockMode = null, $lockVersion = null)
 * @method Contact|null findOneBy(array $criteria, array $orderBy = null)
 * @method Contact[]    findAll()
 * @method Contact[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contact::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Contact $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Contact $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @return Contact[] Returns an array of Contact objects whose name field contains value
     */

     public function findByNameContains($value) {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT c
            FROM App\Entity\Contact c
            WHERE c.name
            LIKE :val'
        )->setParameter( 'val', '%'.$value.'%' );
        $res = $query->getResult();
        return $res;
     }

     public function findBySurnameContains($value) {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT c
            FROM App\Entity\Contact c
            WHERE c.surname
            LIKE :val'
        )->setParameter( 'val', '%'.$value.'%' );
        $res = $query->getResult();
        return $res;
     }

     public function findByEmailContains($value) {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT c
            FROM App\Entity\Contact c
            WHERE c.email
            LIKE :val'
        )->setParameter( 'val', '%'.$value.'%' );
        $res = $query->getResult();
        return $res;
     }

     public function findByAddressContains($value) {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT c
            FROM App\Entity\Contact c
            WHERE c.address
            LIKE :val'
        )->setParameter( 'val', '%'.$value.'%' );
        $res = $query->getResult();
        return $res;
     }

     public function findByPhoneContains($value) {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT c
            FROM App\Entity\Contact c
            WHERE c.phone
            LIKE :val'
        )->setParameter( 'val', '%'.$value.'%' );
        $res = $query->getResult();
        return $res;
     }

     public function findByAgeContains($value) {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT c
            FROM App\Entity\Contact c
            WHERE c.age
            LIKE :val'
        )->setParameter( 'val', '%'.$value.'%' );
        $res = $query->getResult();
        return $res;
     }

    /**
     * @return Contact[] Returns an array of Contact objects contain the $number last contact enter in database
     */

     public function findLast($value) {
        return $this->createQueryBuilder('Contact')
           ->orderBy('Contact.id', 'DESC')
           ->setMaxResults($value)
           ->getQuery()
           ->getResult()
       ;
    }

    // /**
    //  * @return Contact[] Returns an array of Contact objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Contact
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
