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
 * @package  ONGR\ElasticsearchDSL\Query\Specialized
 * @author   NFQ Technologies UAB <info@nfq.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     https://github.com/ongr-io/ElasticsearchDSL
 */

namespace ONGR\ElasticsearchDSL\Query\Specialized;

use ONGR\ElasticsearchDSL\BuilderInterface;
use ONGR\ElasticsearchDSL\ParametersTrait;

/**
 * Represents OpenSearch "neural" query.
 *
 * @category Query
 * @package  ONGR\ElasticsearchDSL\Query\Specialized
 * @author   NFQ Technologies UAB <info@nfq.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     https://docs.opensearch.org/docs/latest/query-dsl/specialized/neural/
 */
class NeuralQuery implements BuilderInterface
{
    use ParametersTrait;

    /**
     * The vector field to search against.
     *
     * @var string
     */
    private $field;

    /**
     * The query vector for semantic search.
     *
     * @var array
     */
    private $queryVector;

    /**
     * Number of nearest neighbors to return.
     *
     * @var int
     */
    private $k;

    /**
     * Neural query constructor.
     *
     * @param string $field       The vector field to search against.
     * @param array  $queryVector The query vector for semantic search.
     * @param int    $k           Number of nearest neighbors to return.
     * @param array  $parameters  Additional parameters.
     */
    public function __construct(
        $field,
        array $queryVector,
        $k,
        array $parameters = []
    ) {
        $this->field = $field;
        $this->queryVector = $queryVector;
        $this->k = $k;
        $this->setParameters($parameters);
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getType()
    {
        return 'neural';
    }

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public function toArray()
    {
        $query = [
            'query_vector' => $this->queryVector,
            'k' => $this->k,
        ];

        $output = $this->processArray($query);

        return [
            $this->getType() => [
                $this->field => $output,
            ],
        ];
    }
}
