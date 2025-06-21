<?php

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ONGR\ElasticsearchDSL\Query\Compound;

use ONGR\ElasticsearchDSL\BuilderInterface;
use ONGR\ElasticsearchDSL\ParametersTrait;

/**
 * Represents OpenSearch hybrid query.
 *
 * @category ElasticsearchDSL
 * @package  ONGR\ElasticsearchDSL\Query\Compound
 * @author   ONGR <info@nfq.com>
 * @license  MIT License
 * @link     https://docs.opensearch.org/docs/latest/query-dsl/compound/hybrid/
 */
class HybridQuery implements BuilderInterface
{
    use ParametersTrait;

    /**
     * Array of queries.
     *
     * @var BuilderInterface[]
     */
    private array $queries = [];

    /**
     * Constructor.
     *
     * @param BuilderInterface[] $queries Array of queries to initialize with
     */
    public function __construct(array $queries = [])
    {
        foreach ($queries as $query) {
            $this->addQuery($query);
        }
    }

    /**
     * Add query to hybrid query.
     *
     * @param BuilderInterface $query Query to add
     *
     * @return $this
     */
    public function addQuery(BuilderInterface $query): self
    {
        $this->queries[] = $query;

        return $this;
    }

    /**
     * Get all queries.
     *
     * @return BuilderInterface[]
     */
    public function getQueries(): array
    {
        return $this->queries;
    }

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public function toArray(): array
    {
        $queries = [];

        foreach ($this->queries as $query) {
            $queries[] = $query->toArray();
        }

        $output = [
            'queries' => $queries,
        ];

        $output = $this->processArray($output);

        return [$this->getType() => $output];
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getType(): string
    {
        return 'hybrid';
    }
}
