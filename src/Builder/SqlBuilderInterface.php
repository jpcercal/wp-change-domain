<?php

namespace Cekurte\Wordpress\ChangeDomain\Builder;

interface SqlBuilderInterface
{
    /**
     * @return array
     */
    public function getSqlQueries();
}
