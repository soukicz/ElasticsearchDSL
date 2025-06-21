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
 * @package  ONGR\ElasticsearchDSL\Tests\Unit\Query\Compound
 * @author   NFQ Technologies UAB <info@nfq.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     https://github.com/ongr-io/ElasticsearchDSL
 */

namespace ONGR\ElasticsearchDSL\Tests\Unit\Query\Compound;

use ONGR\ElasticsearchDSL\Query\Compound\HybridQuery;
use PHPUnit\Framework\TestCase;

/**
 * Unit test for HybridQuery.
 *
 * @category Tests
 * @package  ONGR\ElasticsearchDSL\Tests\Unit\Query\Compound
 * @author   NFQ Technologies UAB <info@nfq.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     https://github.com/ongr-io/ElasticsearchDSL
 */
class HybridQueryTest extends TestCase
{
    /**
     * Tests toArray method.
     *
     * @return void
     */
    public function testToArray()
    {
        $mock = $this->getMockBuilder(
            'ONGR\ElasticsearchDSL\BuilderInterface'
        )->getMock();
        $mock
            ->expects($this->any())
            ->method('toArray')
            ->willReturn(['term' => ['user' => 'bob']]);

        $query = new HybridQuery();
        $query->addQuery($mock);

        $expected = [
            'hybrid' => [
                'queries' => [
                    ['term' => ['user' => 'bob']],
                ],
            ],
        ];

        $this->assertEquals($expected, $query->toArray());
    }

    /**
     * Tests toArray method with multiple queries.
     *
     * @return void
     */
    public function testToArrayWithMultipleQueries()
    {
        $mock1 = $this->getMockBuilder(
            'ONGR\ElasticsearchDSL\BuilderInterface'
        )->getMock();
        $mock1
            ->expects($this->any())
            ->method('toArray')
            ->willReturn(['term' => ['user' => 'bob']]);

        $mock2 = $this->getMockBuilder(
            'ONGR\ElasticsearchDSL\BuilderInterface'
        )->getMock();
        $mock2
            ->expects($this->any())
            ->method('toArray')
            ->willReturn(['match' => ['message' => 'hello']]);

        $query = new HybridQuery();
        $query->addQuery($mock1);
        $query->addQuery($mock2);

        $expected = [
            'hybrid' => [
                'queries' => [
                    ['term' => ['user' => 'bob']],
                    ['match' => ['message' => 'hello']],
                ],
            ],
        ];

        $this->assertEquals($expected, $query->toArray());
    }

    /**
     * Tests toArray method with parameters.
     *
     * @return void
     */
    public function testToArrayWithParameters()
    {
        $mock = $this->getMockBuilder(
            'ONGR\ElasticsearchDSL\BuilderInterface'
        )->getMock();
        $mock
            ->expects($this->any())
            ->method('toArray')
            ->willReturn(['term' => ['user' => 'bob']]);

        $query = new HybridQuery(['boost' => 1.5]);
        $query->addQuery($mock);

        $expected = [
            'hybrid' => [
                'queries' => [
                    ['term' => ['user' => 'bob']],
                ],
                'boost' => 1.5,
            ],
        ];

        $this->assertEquals($expected, $query->toArray());
    }

    /**
     * Tests toArray method with empty queries.
     *
     * @return void
     */
    public function testToArrayWithEmptyQueries()
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
     * Tests getQueries method.
     *
     * @return void
     */
    public function testGetQueries()
    {
        $mock = $this->getMockBuilder(
            'ONGR\ElasticsearchDSL\BuilderInterface'
        )->getMock();
        
        $query = new HybridQuery();
        $query->addQuery($mock);

        $this->assertIsArray($query->getQueries());
        $this->assertCount(1, $query->getQueries());
        $this->assertSame($mock, $query->getQueries()[0]);
    }

    /**
     * Tests getType method.
     *
     * @return void
     */
    public function testGetType()
    {
        $query = new HybridQuery();
        $this->assertEquals('hybrid', $query->getType());
    }

    /**
     * Tests fluent interface.
     *
     * @return void
     */
    public function testFluentInterface()
    {
        $mock = $this->getMockBuilder(
            'ONGR\ElasticsearchDSL\BuilderInterface'
        )->getMock();
        
        $query = new HybridQuery();
        $result = $query->addQuery($mock);

        $this->assertInstanceOf(HybridQuery::class, $result);
        $this->assertSame($query, $result);
    }
}
