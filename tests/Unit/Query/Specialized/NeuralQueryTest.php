<?php

/**
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @category Tests
 * @package  ONGR\ElasticsearchDSL\Tests\Unit\Query\Specialized
 * @author   NFQ Technologies UAB <info@nfq.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     https://github.com/ongr-io/ElasticsearchDSL
 */

namespace ONGR\ElasticsearchDSL\Tests\Unit\Query\Specialized;

use ONGR\ElasticsearchDSL\Query\Specialized\NeuralQuery;
use PHPUnit\Framework\TestCase;

/**
 * Unit test for NeuralQuery.
 *
 * @category Tests
 * @package  ONGR\ElasticsearchDSL\Tests\Unit\Query\Specialized
 * @author   NFQ Technologies UAB <info@nfq.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     https://github.com/ongr-io/ElasticsearchDSL
 */
class NeuralQueryTest extends TestCase
{
    /**
     * Tests toArray method.
     *
     * @return void
     */
    public function testToArray()
    {
        $queryVector = [0.1, 0.2, 0.3, 0.4];
        $query = new NeuralQuery('embedding_field', $queryVector, 5);

        $expected = [
            'neural' => [
                'embedding_field' => [
                    'query_vector' => [0.1, 0.2, 0.3, 0.4],
                    'k' => 5,
                ],
            ],
        ];

        $this->assertEquals($expected, $query->toArray());
    }

    /**
     * Tests toArray method with additional parameters.
     *
     * @return void
     */
    public function testToArrayWithParameters()
    {
        $queryVector = [0.5, 0.6, 0.7];
        $parameters = [
            'boost' => 2.0,
            'filter' => ['term' => ['category' => 'electronics']],
        ];
        
        $query = new NeuralQuery('vector_field', $queryVector, 10, $parameters);

        $expected = [
            'neural' => [
                'vector_field' => [
                    'query_vector' => [0.5, 0.6, 0.7],
                    'k' => 10,
                    'boost' => 2.0,
                    'filter' => ['term' => ['category' => 'electronics']],
                ],
            ],
        ];

        $this->assertEquals($expected, $query->toArray());
    }

    /**
     * Tests toArray method with complex query vector.
     *
     * @return void
     */
    public function testToArrayWithComplexVector()
    {
        $queryVector = [
            -0.123, 0.456, -0.789, 1.234, 0.0,
            0.999, -0.001, 0.5, -0.5, 0.25
        ];
        
        $query = new NeuralQuery('dense_vector', $queryVector, 3);

        $expected = [
            'neural' => [
                'dense_vector' => [
                    'query_vector' => $queryVector,
                    'k' => 3,
                ],
            ],
        ];

        $this->assertEquals($expected, $query->toArray());
    }

    /**
     * Tests toArray method with empty vector.
     *
     * @return void
     */
    public function testToArrayWithEmptyVector()
    {
        $queryVector = [];
        $query = new NeuralQuery('empty_field', $queryVector, 1);

        $expected = [
            'neural' => [
                'empty_field' => [
                    'query_vector' => [],
                    'k' => 1,
                ],
            ],
        ];

        $this->assertEquals($expected, $query->toArray());
    }

    /**
     * Tests toArray method with large k value.
     *
     * @return void
     */
    public function testToArrayWithLargeK()
    {
        $queryVector = [1.0, 2.0, 3.0];
        $query = new NeuralQuery('text_embedding', $queryVector, 1000);

        $expected = [
            'neural' => [
                'text_embedding' => [
                    'query_vector' => [1.0, 2.0, 3.0],
                    'k' => 1000,
                ],
            ],
        ];

        $this->assertEquals($expected, $query->toArray());
    }

    /**
     * Tests toArray method with filter parameter.
     *
     * @return void
     */
    public function testToArrayWithFilter()
    {
        $queryVector = [0.1, 0.2];
        $parameters = [
            'filter' => [
                'bool' => [
                    'must' => [
                        ['range' => ['price' => ['gte' => 10]]],
                        ['term' => ['status' => 'active']],
                    ],
                ],
            ],
        ];
        
        $query = new NeuralQuery('product_embedding', $queryVector, 20, $parameters);

        $expected = [
            'neural' => [
                'product_embedding' => [
                    'query_vector' => [0.1, 0.2],
                    'k' => 20,
                    'filter' => [
                        'bool' => [
                            'must' => [
                                ['range' => ['price' => ['gte' => 10]]],
                                ['term' => ['status' => 'active']],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $this->assertEquals($expected, $query->toArray());
    }

    /**
     * Tests getType method.
     *
     * @return void
     */
    public function testGetType()
    {
        $query = new NeuralQuery('field', [1.0], 1);
        $this->assertEquals('neural', $query->getType());
    }

    /**
     * Tests constructor with different data types.
     *
     * @return void
     */
    public function testConstructorWithDifferentTypes()
    {
        $queryVector = [1, 2.5, -3, 0, 4.7];
        $query = new NeuralQuery('mixed_field', $queryVector, 15);

        $expected = [
            'neural' => [
                'mixed_field' => [
                    'query_vector' => [1, 2.5, -3, 0, 4.7],
                    'k' => 15,
                ],
            ],
        ];

        $this->assertEquals($expected, $query->toArray());
    }
}
