<?php

namespace Mindk\Framework\DB;

/**
 * Interface DBOConnectorInterface
 * @package Mindk\Framework\DB
 */
interface DBOConnectorInterface
{
    /**
     * Set sql query
     *
     * @param $sql
     * @return mixed
     */
    public function setQuery($sql);

    /**
     * Get single row result
     *
     * @param $target
     * @return bool
     */
    public function getSuccess(&$target);

     /**
     * Get single row result
     *
     * @param $target
     * @return bool
     */
    public function getSuccessStatement(&$target);

    /**
     * Get single row result
     *
     * @param $target
     * @return mixed
     */
    public function getResult(&$target);

    /**
     * Get single num result
     *
     * @param $target
     * @return mixed
     */
    public function getArray(&$target);

    /**
     * Get list of resulting rows
     *
     * @param string $targetClass
     * @return mixed
     */
    public function getList($targetClass = '\stdClass');
}