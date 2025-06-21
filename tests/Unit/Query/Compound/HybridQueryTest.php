<?php

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ONGR\ElasticsearchDSL\Tests\Unit\Query\Compound;

use ONGR\ElasticsearchDSL\Query\Compound\HybridQuery;
use ONGR\ElasticsearchDSL\Query\TermLevel\TermQuery;
use ONGR\ElasticsearchDSL\Query\FullText\MatchQuery;

/**
 * Unit test for HybridQuery.
 *
 * @category ElasticsearchDSL
 * @package  ONGR\ElasticsearchDSL\Tests\Unit\Query\Compound
 * @author   ONGR <info@nfq.com>
 * @license  MIT License
 * @link     https://github.com/ongr-io/ElasticsearchDSL
 */
class HybridQueryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Tests getType().
     *
     * @return void
     */
    public function testGetType()
    {
        $query = new HybridQuery();
        $this->assertEquals('hybrid', $query->getType());
    }

    /**
     * Tests toArray() with empty queries.
     *
     * @return void
     */
    public function testToArrayEmpty()
    {
        $query = new HybridQuery();
        $expected = [
            'hybrid' => [
                'queries' => [],
            ],
        ];

        $this->assertEquals($expected, $query->toArray());
    }

    /**
     * Tests toArray() with multiple queries.
     *
     * @return void
     */
    public function testToArray()
    {
        $mock1 = $this->getMockBuilder('ONGR\ElasticsearchDSL\BuilderInterface')
            ->getMock();
        $mock1
            ->expects($this->once())
            ->method('toArray')
            ->willReturn(
                [
                'neural' => ['field' => 'vector', 'query_vector' => [1, 2, 3]]
                ]
            );

        $mock2 = $this->getMockBuilder('ONGR\ElasticsearchDSL\BuilderInterface')
            ->getMock();
        $mock2
            ->expects($this->once())
            ->method('toArray')
            ->willReturn(['match' => ['title' => 'search text']]);

        $query = new HybridQuery();
        $query->addQuery($mock1);
        $query->addQuery($mock2);

        $expected = [
            'hybrid' => [
                'queries' => [
                    [
                        'neural' => [
                            'field' => 'vector',
                            'query_vector' => [1, 2, 3]
                        ]
                    ],
                    ['match' => ['title' => 'search text']],
                ],
            ],
        ];

        $this->assertEquals($expected, $query->toArray());
    }

    /**
     * Tests constructor with queries array.
     *
     * @return void
     */
    public function testConstructorWithQueries()
    {
        $termQuery = new TermQuery('field1', 'value1');
        $matchQuery = new MatchQuery('field2', 'value2');

        $query = new HybridQuery([$termQuery, $matchQuery]);

        $expected = [
            'hybrid' => [
                'queries' => [
                    ['term' => ['field1' => 'value1']],
                    ['match' => ['field2' => ['query' => 'value2']]],
                ],
            ],
        ];

        $this->assertEquals($expected, $query->toArray());
    }

    /**
     * Tests addQuery() method chaining.
     *
     * @return void
     */
    public function testAddQueryChaining()
    {
        $termQuery = new TermQuery('field1', 'value1');
        $matchQuery = new MatchQuery('field2', 'value2');

        $query = new HybridQuery();
        $result = $query->addQuery($termQuery)->addQuery($matchQuery);

        $this->assertSame($query, $result);
        $this->assertCount(2, $query->getQueries());
    }

    /**
     * Tests getQueries() method.
     *
     * @return void
     */
    public function testGetQueries()
    {
        $termQuery = new TermQuery('field1', 'value1');
        $matchQuery = new MatchQuery('field2', 'value2');

        $query = new HybridQuery();
        $query->addQuery($termQuery);
        $query->addQuery($matchQuery);

        $queries = $query->getQueries();
        $this->assertCount(2, $queries);
        $this->assertSame($termQuery, $queries[0]);
        $this->assertSame($matchQuery, $queries[1]);
    }

    /**
     * Tests toArray() with parameters.
     *
     * @return void
     */
    public function testToArrayWithParameters()
    {
        $mock = $this->getMockBuilder('ONGR\ElasticsearchDSL\BuilderInterface')
            ->getMock();
        $mock
            ->expects($this->once())
            ->method('toArray')
            ->willReturn(['match' => ['title' => 'test']]);

        $query = new HybridQuery();
        $query->addQuery($mock);
        $query->addParameter('boost', 1.5);
        $query->addParameter('_name', 'hybrid_query');

        $expected = [
            'hybrid' => [
                'queries' => [
                    ['match' => ['title' => 'test']],
                ],
                'boost' => 1.5,
                '_name' => 'hybrid_query',
            ],
        ];

        $this->assertEquals($expected, $query->toArray());
    }
}
