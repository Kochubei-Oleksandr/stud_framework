<?php

namespace Mindk\Framework\Models;

use Mindk\Framework\DB\DBOConnectorInterface;

/**
 * Basic Model Class
 * @package Mindk\Framework\Models
 */
abstract class Model
{
    /**
     * @var string  DB Table name
     */
    protected $tableName = '';

    /**
     * @var string  DB Table primary key
     */
    protected $primaryKey = 'id';

    /**
     * @var null
     */
    protected $dbo = null;

    /**
     * Model constructor.
     * @param DBOConnectorInterface $db
     */
    public function __construct(DBOConnectorInterface $db)
    {
        $this->dbo = $db;
    }

    /**
     * Create new record
     */
    public function create( $data ) {
        //@TODO: Implement this
    }

    /**
     * Read record
     *
     * @param   int Record ID
     *
     * @return  object
     */
    public function load( $id ) {
        $sql = 'SELECT * FROM `' . $this->tableName .
            '` WHERE `'.$this->primaryKey.'`='.(int)$id; //!

        return $this->dbo->setQuery($sql)->getResult($this);
    }

    /**
     * Find user by access token
     *
     * @param $token
     *
     * @return mixed
     */
    public function findByToken($token){
        $token = filter_var($token, FILTER_SANITIZE_STRING);
        $sql = sprintf("SELECT * FROM `%s` WHERE `token`='%s'", $this->tableName, $token);
        return $this->dbo->setQuery($sql)->getResult($this);
    }

    /**
     * Save record state to db
     *
     * @return bool
     */
    public function save() {
        //@TODO: Implement this
    }

    /**
     * Delete record from DB
     */
    public function delete() {
        //@TODO: Implement this
    }
}