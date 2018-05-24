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
        $email = $request->get('email', '', 'string');

        if(empty($name) || empty($password) || empty($email)) {
            throw new AuthRequiredException('name or password or email = null');
        } else {
            $created_at = date('Y-m-d');
            $token = md5(uniqid());
            $role = (int)1;

            $user = $model->saveUser($name, $role, $password, $email, $created_at, $token);
        }
        if ($user == true) {
            return $token;
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
            return $user->token;
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