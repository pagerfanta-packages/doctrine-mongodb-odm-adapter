<?php declare(strict_types=1);

namespace Pagerfanta\Doctrine\MongoDBODM;

use Doctrine\ODM\MongoDB\Query\Builder;
use Pagerfanta\Adapter\AdapterInterface;

/**
 * Adapter which calculates pagination from a Doctrine MongoDB ODM QueryBuilder.
 */
class QueryAdapter implements AdapterInterface
{
    private Builder $queryBuilder;

    public function __construct(Builder $queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;
    }

    /**
     * @deprecated to be removed in 4.0
     */
    public function getQueryBuilder(): Builder
    {
        trigger_deprecation('pagerfanta/pagerfanta', '3.2', 'Retrieving the %s from "%s" is deprecated and will be removed in 4.0.', Builder::class, static::class);

        return $this->queryBuilder;
    }

    public function getNbResults(): int
    {
        $qb = clone $this->queryBuilder;

        return $qb->limit(0)
            ->skip(0)
            ->count()
            ->getQuery()
            ->execute();
    }

    public function getSlice(int $offset, int $length): iterable
    {
        return $this->queryBuilder->limit($length)
            ->skip($offset)
            ->getQuery()
            ->execute();
    }
}
