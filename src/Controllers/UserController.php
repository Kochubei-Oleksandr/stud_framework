<?php
namespace Mindk\Framework\Controllers;

use Mindk\Framework\Exceptions\AuthRequiredException;
use Mindk\Framework\Http\Request\Request;
use Mindk\Framework\Models\UserModel;
use Mindk\Framework\DB\DBOConnectorInterface;
use Mindk\Framework\Http\Response\JsonResponse;
use Mindk\Framework\Http\Response\Response;
/**
 * Class UserController
 * @package Mindk\Framework\Controllers
 */
class UserController
{
    /**
     * Register through action
     *
     * @param Request $request
     * @param UserModel $model
     *
     * @return mixed
     * @throws AuthRequiredException
     */
    public function register(Request $request, UserModel $model) {
        
        $name = $request->get('name', '', 'string');
        $password = $request->get('password', '', 'string');
        $passwordConfirm = $request->get('passwordConfirm', '', 'string');
        $email = $request->get('email', '', 'string');

        if(empty($name) || empty($password) || empty($email)) {
            throw new AuthRequiredException('name or password or email = null');
        } else {
            if ($model->checkUserEmail($email) == true) {
                return 'На данную почту уже зарегистрирован аккаунт';
            } else {
                if ($password != $passwordConfirm) {
                    return 'Повторный пароль не совпал';
                }
    
                $created_at = date('Y-m-d');
                $token = md5(uniqid());
                $role = (int)2;
    
                $user = $model->saveUser($name, $role, $password, $email, $created_at, $token);
            }  
        }

        if ($user == true) {
            $user = $model->findByCredentials($email, $password);
            if(empty($user)) {
                throw new AuthRequiredException('Bad access credentials provided');
            }

            return [
                'user_id' => $user->id,
                'api_token' => $user->token,
                'name' => $user->name,
                'user_role' => $user->id_role_user
            ];
        }
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
        $tokk = $model->returnToken($user->token, $user->id);
        
        if ($tokk == true) {
            return [
                'user_id' => $user->id,
                'api_token' => $user->token,
                'name' => $user->name,
                'user_role' => $user->id_role_user
            ];
        }
    }

    /**
     * Logout through action
     *
     * @param Request $request
     * @param UserModel $model
     *
     * @return mixed
     * @throws AuthRequiredException
     */
    public function logout(Request $request, UserModel $model) {
        if($userToken = $request->get('token', '', 'string')) {
            $user = $model->findByToken($userToken);
        }
        if(empty($user)) {
            throw new AuthRequiredException('Token is not found');
        }
        // Generate new access token and save:
        $user->token = '';
        $tokk = $model->returnToken($user->token, $user->id);
        if ($tokk == true) {
            return $user->token;
        }
    }
}