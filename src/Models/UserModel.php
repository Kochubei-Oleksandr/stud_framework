<?php
/**
 * Created by PhpStorm.
 * User: dimmask
 * Date: 11.04.18
 * Time: 20:10
 */
namespace Mindk\Framework\Models;
/**
 * Class UserModel
 * @package Mindk\Framework\Models
 */
class UserModel extends Model
{
    /**
     * @var string  DB Table name
     */
    protected $tableName = 'users';
    /**
     * Find user by credentials
     *
     * @param $login
     * @param $password
     *
     * @return mixed
     */
    public function findByCredentials($login, $password){
        $sql = sprintf("SELECT * FROM `%s` WHERE `email`='%s' AND `password`='%s'", $this->tableName, $login, md5($password));
        return $this->dbo->setQuery($sql)->getResult($this);
    }

    public function checkUserEmail($email){
        $sql = sprintf("SELECT * FROM `%s` WHERE `email`='%s'", $this->tableName, $email);
        return $this->dbo->setQuery($sql)->getSuccess($this);
    }
    
    public function saveUser($name, $role, $password, $email, $created_at, $token){
        $sql = sprintf("INSERT INTO `%s` (`name`,`id_role_user`,`password`,`email`,`created_at`,`token`) VALUES
            ('%s','%s','%s','%s','%s','%s')", $this->tableName, $name, $role, md5($password), $email, $created_at, $token);
        return $this->dbo->setQuery($sql)->getSuccessStatement($this);
    }

    public function returnToken($token, $userId){
        $sql = ("UPDATE `users` SET `token`='".$token."' WHERE `id`=".(int)$userId);
        return $this->dbo->setQuery($sql)->getSuccessStatement($this);
    }
}