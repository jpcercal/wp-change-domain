<?php

namespace Cekurte\Wordpress\ChangeDomain\Builder;

use Symfony\Component\HttpFoundation\Request;

class SqlBuilder implements SqlBuilderInterface
{
    /**
     * @var string
     */
    private $tablePrefix;

    /**
     * @var int
     */
    private $numberOfBlogs;

    /**
     * @var string
     */
    private $domainFrom;

    /**
     * @var string
     */
    private $domainTo;

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $this->tablePrefix   = $data['tablePrefix'];
        $this->numberOfBlogs = $data['numberOfBlogs'];

        $this->domainFrom = $data['domainFrom'];
        $this->domainTo   = $data['domainTo'];
    }

    /**
     * @return string
     */
    public function getTablePrefix()
    {
        return $this->tablePrefix;
    }

    /**
     * @return int
     */
    public function getNumberOfBlogs()
    {
        return $this->numberOfBlogs;
    }

    /**
     * @return string
     */
    public function getDomainFrom()
    {
        return $this->domainFrom;
    }

    /**
     * @return string
     */
    public function getDomainTo()
    {
        return $this->domainTo;
    }

    /**
     * @param  int $blogId
     *
     * @return string
     */
    protected function getTablePrefixByBlogId($blogId)
    {
        return $this->getTablePrefix() . ($blogId === 1 ? '' : $blogId . '_');
    }

    /**
     * @inheritdoc
     */
    public function getSqlQueries()
    {
        $data = [];

        $numberOfBlogs = $this->getNumberOfBlogs();

        for ($blogId = 1; $blogId <= $numberOfBlogs; $blogId++) {
            $tablePrefixByBlogId = $this->getTablePrefixByBlogId($blogId);

            $data[] = $this->buildSqlOptionsByBlogId($blogId);

            $data[] = $this->buildSql($tablePrefixByBlogId . 'posts',    'guid');
            $data[] = $this->buildSql($tablePrefixByBlogId . 'posts',    'post_content');
            $data[] = $this->buildSql($tablePrefixByBlogId . 'postmeta', 'meta_value');
        }

        $data[] = $this->buildSql($this->getTablePrefix() . 'site',  'domain');
        $data[] = $this->buildSql($this->getTablePrefix() . 'blogs', 'domain');

        return $data;
    }

    /**
     * @param  string      $table
     * @param  string      $field
     * @param  string|null $where
     *
     * @return string
     */
    protected function buildSql($table, $field, $where = null)
    {
        $domainFrom = $this->getDomainFrom();
        $domainTo   = $this->getDomainTo();

        $template = "UPDATE %s SET %s = REPLACE(%s, '{$domainFrom}', '{$domainTo}')%s;";

        return sprintf($template, $table, $field, $field, is_null($where) ? '' : ' ' . $where);
    }

    /**
     * @param  int $blogId
     *
     * @return string
     */
    protected function buildSqlOptionsByBlogId($blogId)
    {
        $table = $this->getTablePrefixByBlogId($blogId) . 'options';

        $where = "WHERE option_name = 'home' OR option_name = 'siteurl' OR option_name = 'ck_wp_panel_custom'";

        return $this->buildSql($table, 'option_value', $where);
    }
}
