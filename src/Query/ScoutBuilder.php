<?php

namespace Lomkit\Rest\Query;

use Lomkit\Rest\Contracts\QueryBuilder;
use Lomkit\Rest\Http\Resource;

class ScoutBuilder implements QueryBuilder
{
    /**
     * Construct a new query builder for a resource.
     *
     * @param resource $resource
     *
     * @return void
     */
    public function __construct(Resource $resource)
    {
        $this->resource = $resource;
    }

    /**
     * The query builder instance.
     *
     * @var resource
     */
    protected $resource;

    /**
     * The query builder instance.
     *
     * @var \Laravel\Scout\Builder
     */
    protected $queryBuilder;

    /**
     * Convert the query builder to an Eloquent query builder.
     *
     * @return \Laravel\Scout\Builder
     */
    public function toBase()
    {
        return $this->queryBuilder;
    }

    public function search(array $parameters = [])
    {
        // TODO: Implement search() method.
    }
}
