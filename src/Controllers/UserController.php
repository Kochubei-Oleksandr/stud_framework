<?php

namespace Mindk\Framework\Controllers;

use Mindk\Framework\Exceptions\AuthRequiredException;
use Mindk\Framework\Http\Request\Request;
use Mindk\Framework\Models\UserModel;
use Mindk\Framework\DB\DBOConnectorInterface;

/**
 * Class UserController
 * @package Mindk\Framework\Controllers
 */
class UserController
{
    public function register(Request $request, UserModel $model) {
        
        $name = $request->get('name', 'string');
        $role = (int)1;
        $password = $request->get('password', 'string');
        $email = $request->get('email', 'string');
        $created_at = $request->get('created_at', 'string');
        $token = md5(uniqid());
        //var_dump($role); die;    
        
        $user = $model->saveUser($name, $role, $password, $email, $created_at, $token);
        var_dump($user); die;

        /* if(empty($user)) {
            throw new AuthRequiredException('Register is falled');
        } */

        // Generate new access token and save:
        //$user->token = md5(uniqid());
        //$user->save();
        //@TODO: REMOVE THIS when UserModel::save() implemented
        //$dbo->setQuery("UPDATE `users` SET `token`='".$user->token."' WHERE `id`=".(int)$user->id);

        //return $user->token;
    }

    /**
     * Login through action
     *
     * @param Request $request
     * @param UserModel $model
     *
     * @return mixed
     * @throws AuthRequiredException
     */
    public function login(Request $request, UserModel $model, DBOConnectorInterface $dbo) {

        

        if($login = $request->get('login', '', 'string')) {
            $user = $model->findByCredentials($login, $request->get('password', ''));
        }

        if(empty($user)) {
            throw new AuthRequiredException('Bad access credentials provided');
        }

        // Generate new access token and save:
        $user->token = md5(uniqid());
        //$user->save();
        //@TODO: REMOVE THIS when UserModel::save() implemented
        $dbo->setQuery("UPDATE `users` SET `token`='".$user->token."' WHERE `id`=".(int)$user->id);

        return $user->token;
    }

    public function logout(Request $request) {
        //@TODO: Implement
    }
}