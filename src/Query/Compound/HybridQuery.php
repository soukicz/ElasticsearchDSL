<?php

/**
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @category Query
 * @package  ONGR\ElasticsearchDSL\Query\Compound
 * @author   NFQ Technologies UAB <info@nfq.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     https://github.com/ongr-io/ElasticsearchDSL
 */

namespace ONGR\ElasticsearchDSL\Query\Compound;

use ONGR\ElasticsearchDSL\BuilderInterface;
use ONGR\ElasticsearchDSL\ParametersTrait;

/**
 * Represents OpenSearch "hybrid" query.
 *
 * @category Query
 * @package  ONGR\ElasticsearchDSL\Query\Compound
 * @author   NFQ Technologies UAB <info@nfq.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     https://docs.opensearch.org/docs/latest/query-dsl/compound/hybrid/
 */
class HybridQuery implements BuilderInterface
{
    use ParametersTrait;

    /**
     * List of queries to execute in hybrid search.
     *
     * @var BuilderInterface[]
     */
    private $queries = [];

    /**
     * Initializes Hybrid query.
     *
     * @param array $parameters Parameters for the hybrid query.
     */
    public function __construct(array $parameters = [])
    {
        $this->setParameters($parameters);
    }

    /**
     * Add query to the hybrid query.
     *
     * @param BuilderInterface $query Query to add to hybrid search.
     *
     * @return HybridQuery
     */
    public function addQuery(BuilderInterface $query)
    {
        $this->queries[] = $query;

        return $this;
    }

    /**
     * Get all queries.
     *
     * @return BuilderInterface[]
     */
    public function getQueries()
    {
        return $this->queries;
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getType()
    {
        return 'hybrid';
    }

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public function toArray()
    {
        $query = [];
        foreach ($this->queries as $subQuery) {
            $query[] = $subQuery->toArray();
        }
        $output = $this->processArray(['queries' => $query]);

        return [$this->getType() => $output];
    }
}
