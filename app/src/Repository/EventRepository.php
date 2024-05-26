<?php
/**
 * Event repository.
 */

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Event;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class EventRepository.
 *
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @extends ServiceEntityRepository<Event>
 */
class EventRepository extends ServiceEntityRepository
{
    /**
     * Constructor.
     *
     * @param ManagerRegistry $registry Manager registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    /**
     * Query all records.
     *
     * @return QueryBuilder Query builder
     */
    public function queryAll(): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder()
            ->select(
                'partial event.{id, createdAt, updatedAt, title}',
                'partial category.{id, title}'
            )
            ->join('event.category', 'category')
            ->orderBy('event.updatedAt', 'DESC');
    }

    /**
     * Count events by category.
     *
     * @param Category $category Category
     *
     * @return int Number of events in category
     *
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function countByCategory(Category $category): int
    {
        $qb = $this->getOrCreateQueryBuilder();

        return $qb->select($qb->expr()->countDistinct('event.id'))
            ->where('event.category = :category')
            ->setParameter(':category', $category)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Save entity.
     *
     * @param Event $event Event entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Event $event): void
    {
        assert($this->_em instanceof EntityManager);
        $this->_em->persist($event);
        $this->_em->flush();
    }

    /**
     * Delete entity.
     *
     * @param Event $event Event entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(Event $event): void
    {
        assert($this->_em instanceof EntityManager);
        $this->_em->remove($event);
        $this->_em->flush();
    }

    /**
     * Get or create new query builder.
     *
     * @param QueryBuilder|null $queryBuilder Query builder
     *
     * @return QueryBuilder Query builder
     */
    private function getOrCreateQueryBuilder(QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $queryBuilder ?? $this->createQueryBuilder('event');
    }

    /**
     * Query tasks by author.
     *
     * @param User $user User entity
     *
     * @return QueryBuilder Query builder
     */
    public function queryByAuthor(User $user): QueryBuilder
    {
        $queryBuilder = $this->queryAll();

        $queryBuilder->andWhere('event.author = :author')
            ->setParameter('author', $user);

        return $queryBuilder;
    }
}
