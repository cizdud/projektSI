<?php
/**
 * Event repository.
 */

namespace App\Repository;

use App\Dto\EventListFiltersDto;
use App\Entity\Category;
use App\Entity\Event;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

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
    public const PAGINATOR_ITEMS_PER_PAGE = 10;

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
     * @param EventListFiltersDto $filters Filters
     *
     * @return QueryBuilder Query builder
     */
    public function queryAll(EventListFiltersDto $filters): QueryBuilder
    {
        $queryBuilder = $this->getOrCreateQueryBuilder()
            ->select(
                'partial event.{id, createdAt, updatedAt, title, eventDate}',
                'partial category.{id, title}'
            )
            ->join('event.category', 'category')
            ->orderBy('event.updatedAt', 'DESC');

        return $this->applyFiltersToList($queryBuilder, $filters);
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
    private function getOrCreateQueryBuilder(?QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $queryBuilder ?? $this->createQueryBuilder('event');
    }

    /**
     * Query events by author.
     *
     * @param UserInterface       $user    User entity
     * @param EventListFiltersDto $filters Filters
     *
     * @return QueryBuilder Query builder
     */
    public function queryByAuthor(UserInterface $user, EventListFiltersDto $filters): QueryBuilder
    {
        $queryBuilder = $this->queryAll($filters);

        $queryBuilder->andWhere('event.author = :author')
            ->setParameter('author', $user);

        return $queryBuilder;
    }

    public function queryActualEvents(EventListFiltersDto $filters): QueryBuilder
    {
        $queryBuilder = $this->getOrCreateQueryBuilder()
            ->select(
                'partial event.{id, createdAt, updatedAt, title, eventDate}',
                'partial category.{id, title}'
            )
            ->join('event.category', 'category')
            ->where('event.eventDate >= :start_date')
            ->andWhere('event.eventDate <= :end_date')
            ->setParameter('start_date', new \DateTime('-7 days'))
            ->setParameter('end_date', new \DateTime())
            ->orderBy('event.eventDate', 'ASC');

        return $this->applyFiltersToList($queryBuilder, $filters);
    }

    public function queryFutureEvents(EventListFiltersDto $filters): QueryBuilder
    {
        $queryBuilder = $this->getOrCreateQueryBuilder()
            ->select(
                'partial event.{id, createdAt, updatedAt, title, eventDate}',
                'partial category.{id, title}'
            )
            ->join('event.category', 'category')
            ->where('event.eventDate > :current_date')
            ->setParameter('current_date', new \DateTime())
            ->orderBy('event.eventDate', 'ASC');

        return $this->applyFiltersToList($queryBuilder, $filters);
    }


    /**
     * Apply filters to paginated list.
     *
     * @param QueryBuilder        $queryBuilder Query builder
     * @param EventListFiltersDto $filters      Filters
     *
     * @return QueryBuilder Query builder
     */
    private function applyFiltersToList(QueryBuilder $queryBuilder, EventListFiltersDto $filters): QueryBuilder
    {
        if ($filters->category instanceof Category) {
            $queryBuilder->andWhere('category = :category')
                ->setParameter('category', $filters->category);
        }

        return $queryBuilder;
    }
}
