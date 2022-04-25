<?php

namespace Core\Middlewares;

use Core\Router;
use Core\Helpers\Request;
use App\Models\User;
use Core\Helpers\Response;
use Firebase\JWT\{JWT, Key};
use Exception;

/**
 * Auth class
 * - Handle the request
 * - Check if the user is authenticated
 * - Check if the user is authorized 
 * 
 * @package    Core
 * @author     Mohammed-Aymen Benadra
 * 
 */
class Auth
{
    /**
     * Setup headers for JSON response
     * 
     * @return void
     */
    public function __construct()
    {
        Response::headers();
    }

    /**
     * Handle Authentication using session
     * 
     * @param  mixed $role
     * @return void
     */
    public function handle($role)
    {
        $jwtToken = Request::authorization() ?? null;

        switch ($role) {
            case 'guest':
                if (!$jwtToken) {
                    return;
                }
                break;

            case 'client':
                if ($this->checkJWT($jwtToken)) {
                    return;
                }
                break;

            case 'admin':
                if ($this->checkJWTAndRole($jwtToken, 'admin')) {
                    return;
                }
                break;

            default:
                break;
        }

        // Redirect to login page if guest and to home page if user
        if (!$jwtToken) {
            Router::abort(401, [
                'status' => 'error',
                'message' => 'Unauthorized: You must be logged in'
            ]);
        } else {
            Router::abort(403, [
                'status' => 'error',
                'message' => 'Unauthorized: You\'re not allowed to access this page'
            ]);
        }
    }

    /**
     * Checks if JWT is valid and returns true if it does
     * 
     * @param  string $jwt
     * @return boolean
     */
    public function checkJWT($jwt)
    {
        if (!$jwt) {
            return false;
        }
        try {
            $token = JWT::decode($jwt, new Key($_ENV['JWT_SECRET_KEY'], "HS256"));

            // Check if User exists
            $user = (new User())->getBy('username', $token->sub);
            if (!$user) {
                throw new Exception('User not found');
            }

            return true;
        } catch (Exception $e) {
            Router::abort(401, [
                'status' => 'error',
                'message' => 'Unauthorized: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Checks if user is authorized
     * 
     * @param  string $jwt
     * @param  string $role
     * @return boolean
     */
    public function checkJWTAndRole($jwt, $role)
    {
        if (!$jwt) {
            return false;
        }
        try {
            $token = JWT::decode($jwt, new Key($_ENV['JWT_SECRET_KEY'], "HS256"));

            // Check if User exists
            $user = (new User())->getBy('username', $token->sub);
            if (!$user) {
                throw new Exception('User not found');
            }

            // Check if user is authorized
            if ($user->admin === 1 && $role === 'admin') {
                return true;
            }

            return false;
        } catch (Exception $e) {
            Router::abort(401, [
                'status' => 'error',
                'message' => 'Unauthorized: ' . $e->getMessage()
            ]);
        }
    }
}
