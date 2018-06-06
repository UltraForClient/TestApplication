<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\FAQCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method FAQCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method FAQCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method FAQCategory[]    findAll()
 * @method FAQCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FAQCategoryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, FAQCategory::class);
    }

    public function findAllByPosition()
    {
        return $this->createQueryBuilder('f')
            ->orderBy('f.position')
            ->getQuery()
            ->getResult();
    }
}
