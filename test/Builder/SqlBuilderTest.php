<?php

namespace Cekurte\ResourceManager\Test\Builder;

use Cekurte\Tdd\ReflectionTestCase;
use Cekurte\Wordpress\ChangeDomain\Builder\SqlBuilder;

class SqlBuilderTest extends ReflectionTestCase
{
    public function testImplementsResourceInterface()
    {
        $reflection = new \ReflectionClass(
            '\\Cekurte\\Wordpress\\ChangeDomain\\Builder\\SqlBuilder'
        );

        $this->assertTrue($reflection->implementsInterface(
            '\\Cekurte\\Wordpress\\ChangeDomain\\Builder\\SqlBuilderInterface'
        ));
    }

    public function testConstructor()
    {
        $request = $this
            ->getMockBuilder('\\Symfony\\Component\\HttpFoundation\\Request')
            ->disableOriginalConstructor()
            ->setMethods(['getContent'])
            ->getMock()
        ;

        $request
            ->expects($this->once())
            ->method('getContent')
            ->will($this->returnValue(json_encode([
                'tablePrefix'   => 'wp_',
                'numberOfBlogs' => 1,
                'domainFrom'    => 'http://yourolddomain.com',
                'domainTo'      => 'http://yournewdomain.com',
            ])))
        ;

        $builder = new SqlBuilder($request);

        $this->assertEquals('wp_', $builder->getTablePrefix());

        $this->assertEquals(1, $builder->getNumberOfBlogs());

        $this->assertEquals('http://yourolddomain.com', $builder->getDomainFrom());

        $this->assertEquals('http://yournewdomain.com', $builder->getDomainTo());
    }

    public function dataProviderGetTablePrefixByBlogId()
    {
        return [
            [1],
            [2],
            [3],
            [10],
            [50],
            [100],
        ];
    }

    /**
     * @dataProvider dataProviderGetTablePrefixByBlogId
     */
    public function testGetTablePrefixByBlogId($blogId)
    {
        $builder = $this
            ->getMockBuilder('\\Cekurte\\Wordpress\\ChangeDomain\\Builder\\SqlBuilder')
            ->disableOriginalConstructor()
            ->setMethods(['getTablePrefix'])
            ->getMock()
        ;

        $builder
            ->expects($this->once())
            ->method('getTablePrefix')
            ->will($this->returnValue('tablePrefix'))
        ;

        $expected = 'tablePrefix';

        if ($blogId > 1) {
            $expected .= sprintf('%d_', $blogId);
        }

        $this->assertEquals(
            $expected,
            $this->invokeMethod($builder, 'getTablePrefixByBlogId', [$blogId])
        );
    }

    public function testBuildSql()
    {
        $builder = $this
            ->getMockBuilder('\\Cekurte\\Wordpress\\ChangeDomain\\Builder\\SqlBuilder')
            ->disableOriginalConstructor()
            ->setMethods(['getDomainFrom', 'getDomainTo'])
            ->getMock()
        ;

        $builder
            ->expects($this->exactly(2))
            ->method('getDomainFrom')
            ->will($this->returnValue('domainFrom'))
        ;

        $builder
            ->expects($this->exactly(2))
            ->method('getDomainTo')
            ->will($this->returnValue('domainTo'))
        ;

        $expected = "UPDATE tablename SET fieldname = REPLACE(fieldname, 'domainFrom', 'domainTo')";

        $this->assertEquals(
            $expected . ';',
            $this->invokeMethod($builder, 'buildSql', ['tablename', 'fieldname'])
        );

        $this->assertEquals(
            $expected . ' WHERE 1=1;',
            $this->invokeMethod($builder, 'buildSql', ['tablename', 'fieldname', 'WHERE 1=1'])
        );
    }

    public function testBuildSqlOptionsByBlogId()
    {
        $builder = $this
            ->getMockBuilder('\\Cekurte\\Wordpress\\ChangeDomain\\Builder\\SqlBuilder')
            ->disableOriginalConstructor()
            ->setMethods(['getTablePrefixByBlogId', 'getDomainFrom', 'getDomainTo'])
            ->getMock()
        ;

        $builder
            ->expects($this->once())
            ->method('getTablePrefixByBlogId')
            ->will($this->returnValue('tablePrefix'))
        ;

        $builder
            ->expects($this->once())
            ->method('getDomainFrom')
            ->will($this->returnValue('domainFrom'))
        ;

        $builder
            ->expects($this->once())
            ->method('getDomainTo')
            ->will($this->returnValue('domainTo'))
        ;

        $expected = "UPDATE tablePrefixoptions SET option_value = REPLACE(option_value, 'domainFrom', 'domainTo') WHERE option_name = 'home' OR option_name = 'siteurl' OR option_name = 'ck_wp_panel_custom';";

        $this->assertEquals(
            $expected,
            $this->invokeMethod($builder, 'buildSqlOptionsByBlogId', [2])
        );
    }

    public function testGetSqlQueries()
    {
        $builder = $this
            ->getMockBuilder('\\Cekurte\\Wordpress\\ChangeDomain\\Builder\\SqlBuilder')
            ->disableOriginalConstructor()
            ->setMethods([
                'getNumberOfBlogs',
                'getTablePrefixByBlogId',
                'buildSqlOptionsByBlogId',
                'buildSql',
                'getDomainFrom',
                'getDomainTo'
            ])
            ->getMock()
        ;

        $builder
            ->expects($this->once())
            ->method('getNumberOfBlogs')
            ->will($this->returnValue(1))
        ;

        $builder
            ->expects($this->any())
            ->method('getTablePrefixByBlogId')
            ->will($this->returnValue('tablePrefix'))
        ;

        $builder
            ->expects($this->once())
            ->method('buildSqlOptionsByBlogId')
            ->will($this->returnValue('buildSqlOptionsByBlogId'))
        ;

        $builder
            ->expects($this->exactly(5))
            ->method('buildSql')
            ->will($this->returnValue('buildSql'))
        ;

        $builder
            ->expects($this->any())
            ->method('getDomainFrom')
            ->will($this->returnValue('domainFrom'))
        ;

        $builder
            ->expects($this->any())
            ->method('getDomainTo')
            ->will($this->returnValue('domainTo'))
        ;

        $queries = $builder->getSqlQueries();

        $this->assertCount(6, $queries);
    }

    public function testGetSqlQueriesResult()
    {
        $builder = $this
            ->getMockBuilder('\\Cekurte\\Wordpress\\ChangeDomain\\Builder\\SqlBuilder')
            ->disableOriginalConstructor()
            ->setMethods([
                'getNumberOfBlogs',
                'getTablePrefixByBlogId',
            ])
            ->getMock()
        ;

        $builder
            ->expects($this->once())
            ->method('getNumberOfBlogs')
            ->will($this->returnValue(1))
        ;

        $builder
            ->expects($this->any())
            ->method('getTablePrefixByBlogId')
            ->will($this->returnValue('tablePrefix'))
        ;

        $queries = $builder->getSqlQueries();

        $this->assertCount(6, $queries);

        $this->assertContains('UPDATE tablePrefixoptions SET option_value', $queries[0]);

        $this->assertContains('UPDATE tablePrefixposts SET guid', $queries[1]);

        $this->assertContains('UPDATE tablePrefixposts SET post_content', $queries[2]);

        $this->assertContains('UPDATE tablePrefixpostmeta SET meta_value', $queries[3]);

        $this->assertContains('UPDATE site SET domain', $queries[4]);

        $this->assertContains('UPDATE blogs SET domain', $queries[5]);
    }
}
