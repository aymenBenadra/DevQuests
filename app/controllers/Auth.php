<?php

namespace App\Controllers;

use App\Models\User;
use Core\{Controller, Router};
use Core\Helpers\{Request, Validator, Response};
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
        $user = $this->model('User')->getBy('username', $data['username']);

        if ($user) {
            Router::abort(400, [
                'status' => 'error',
                'message' => 'Username already taken'
            ]);
        }

        $user = $this->model('User')->getBy('email', $data['email']);

        if ($user) {
            Router::abort(400, [
                'status' => 'error',
                'message' => 'Email already exists'
            ]);
        }

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
                'status' => 'error',
                'message' => 'Server error'
            ]);
        }

        $user = $this->model('User')->get($this->model('User')->getLastInsertedId());

        unset($user->password, $user->id);

        Response::send([
            'status' => 'Registered successfully!',
            'data' => $user
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

        if (!$user) {
            Router::abort(404, [
                'status' => 'error',
                'message' => 'User not found'
            ]);
        }

        if (!password_verify($data['password'], $user->password)) {
            Router::abort(401, [
                'status' => 'error',
                'message' => 'Invalid password'
            ]);
        }

        $secret_key = $_ENV['JWT_SECRET_KEY'];
        $issuer_claim = $_ENV['SERVER_ADDRESS']; // this can be the servername
        $audience_claim = $_ENV['CLIENT_ADDRESS'];
        $issuedat_claim = time(); // issued at
        // $notbefore_claim = $issuedat_claim + 10; //not before in seconds
        $expire_claim = $issuedat_claim + 86400; // expire time in seconds (24 hours from now)
        $payload = array(
            "iss" => $issuer_claim,
            "aud" => $audience_claim,
            "iat" => $issuedat_claim,
            // "nbf" => $notbefore_claim,
            "exp" => $expire_claim,
            "sub" => $user->username
        );

        $jwt = JWT::encode($payload, $secret_key, "HS256");

        // Set expirable cookie for JWT
        setcookie(name:'jwt', value:$jwt, expires_or_options:$expire_claim, httponly:true);

        Response::send(
            array(
                "message" => "Logged in successfully!",
                "jwt" => $jwt
            )
        );
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

        return (new User)->getBy('username', $token->sub);
    }
}
