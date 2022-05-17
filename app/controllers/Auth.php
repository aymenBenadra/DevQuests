<?php

namespace App\Controllers;

use App\Models\User;
use Core\{Controller, Router};
use Core\Helpers\{Request, Validator, Response};
use Exception;
use Firebase\JWT\{JWT, Key};
use Jdenticon\Identicon;

/**
 * Auth Controller
 *
 * @author Mohammed-Aymen Benadra
 * @package App\Controllers
 */
class Auth extends Controller
{
    /**
     * Set headers for JSON response
     *
     * @return void
     */
    public function __construct()
    {
        Response::headers();
        Response::code();
    }

    /**
     * Register new User
     * 
     * @param array $data
     * @return void
     */
    public function register($data = [])
    {
        // Hash password
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

        // Generate Identicon
        $avatar = new Identicon(array(
            'value' => $data['username'],
            'size' => 100
        ));
        $avatar = $avatar->getImageData('svg');

        // Save avatar in idonticons folder
        file_put_contents(dirname(dirname(__DIR__)) . "/public/identicons/" . $data['username'] . ".svg", $avatar);

        // Save avatar file name to database (without path)
        $data['avatar'] = $data['username'] . ".svg";

        if (!$this->model('User')->add($data)) {
            Router::abort(500, [
                'message' => 'Server error'
            ]);
        }

        Response::send([
            'message' => 'Registered successfully!'
        ]);
    }

    /**
     * Register new Admin
     * 
     * @param array $data
     * @return void
     */
    public function registerAdmin($data = [])
    {
        $data['is_admin'] = 1;
        $this->register($data);
    }

    /**
     * Login an User using username and password with JWT
     * 
     * @param array $data
     * @return void
     */
    public function login($data = [])
    {
        $user = Validator::email($data['login']) === true
            ? $this->model('User')->getBy('email', $data['login'])
            : $this->model('User')->getBy('username', $data['login']);

        if (!password_verify($data['password'], $user->password)) {
            Router::abort(401, [
                'message' => 'Invalid password'
            ]);
        }

        // Create Refresh Token
        $refreshToken = $this->createToken($user->username, $_ENV['JWT_REFRESH_EXP_DELTA_SECONDS']);

        setcookie(
            name: 'auth',
            value: $refreshToken,
            expires_or_options: time() + $_ENV['JWT_REFRESH_EXP_DELTA_SECONDS'],
            httponly: true
        );
        // Create Access Token
        $accessToken = $this->createToken($user->username, $_ENV['JWT_ACCESS_EXP_DELTA_SECONDS']);

        unset($user->password, $user->id);
        $user->avatar = file_get_contents(dirname(dirname(__DIR__)) . "/public/identicons/" . $user->avatar);
        $user->accessToken = $accessToken;

        Response::send(
            $user
        );
    }

    /**
     * Refresh Access Token
     * 
     * @param array $data
     * @return void
     */
    public function refresh()
    {
        $refreshToken = Request::refreshToken();

        // Check if refresh token is valid
        try {
            if (!$refreshToken) {
                throw new Exception('No refresh token found');
            }

            $token = JWT::decode($refreshToken, new Key($_ENV['JWT_SECRET_KEY'], $_ENV['JWT_ALGORITHM']));

            // Check if User exists
            $user = (new User())->getBy('username', $token->sub);
            if (!$user) {
                throw new Exception('User not found');
            }

            Response::send([
                'accessToken' => $this->createToken($user->username, $_ENV['JWT_ACCESS_EXP_DELTA_SECONDS'])
            ]);
        } catch (Exception $e) {
            Router::abort(401, [
                'message' => 'Unauthorized: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Logout an User
     * 
     * @return void
     */
    public function logout()
    {
        setcookie(name: 'auth', value: '', expires_or_options: time() - 1, httponly: true);

        Response::send([
            'message' => 'Logged out successfully!'
        ]);
    }

    /**
     * Get current authenticated User
     * 
     * @return object
     */
    public static function user()
    {
        $jwt = Request::authorization();

        if (!$jwt) {
            return null;
        }

        $token = JWT::decode($jwt, new Key($_ENV['JWT_SECRET_KEY'], "HS256"));

        $user = (new User)->getBy('username', $token->sub);

        $user->avatar = file_get_contents(dirname(dirname(__DIR__)) . "/public/identicons/" . $user->avatar);

        unset($user->password, $user->id);

        return $user;
    }

    /**
     * Create token for user
     * 
     * @param string $sub
     * @param int $exp
     * @return string
     */
    public static function createToken($sub, $exp)
    {
        $secret_key = $_ENV['JWT_SECRET_KEY'];
        $issuer_claim = $_ENV['SERVER_ADDRESS']; // this can be the servername
        $audience_claim = $_ENV['CLIENT_ADDRESS'];
        $issuedat_claim = time(); // issued at
        $expire_claim = $issuedat_claim + $exp; // expire time in seconds (24 hours from now)
        $payload = array(
            "iss" => $issuer_claim,
            "aud" => $audience_claim,
            "iat" => $issuedat_claim,
            "exp" => $expire_claim,
            "sub" => $sub
        );

        return JWT::encode($payload, $secret_key, $_ENV['JWT_ALGORITHM']);
    }
}
