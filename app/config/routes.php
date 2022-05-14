<?php

/**
 ** Documentation: Postman
 */

use App\Controllers\Auth;
use Core\Helpers\Response;
use Core\Router;

$router->get('/', fn () => Router::redirect('https://documenter.getpostman.com/view/19708524/UyxjF5zC'), ['Auth@admin']);

/**
 ** Auth Routes
 */
//? Guest
$router->post('register', 'Auth@register', ['Auth@guest', 'Validation@username|name|email|password@user']); //*🚀
$router->post('register/admin', 'Auth@registerAdmin', ['Auth@guest', 'Validation@username|name|email|password@user']); //*🚀
$router->post('login', 'Auth@login', ['Auth@guest', 'Validation@login|password@user']); //*🚀
$router->get('logout', 'Auth@logout', ['Auth@client']); //*🚀

/**
 ** User Routes
 */
//? Client
$router->get('user', function () {
    Response::send([
        'status' => 'success',
        'data' => [
            'user' => Auth::user()
        ]
    ]);
}, ['Auth@client']); //*🚀
$router->get('user/avatar', function () {
    Response::send([
        'status' => 'success',
        'data' => [
            'avatar' => file_get_contents(dirname(dirname(__DIR__)) . "/public/identicons/" . Auth::user()->avatar)
        ]
    ]);
}, ['Auth@client']); //*🚀

/**
 ** Resources Routes
 */
//? Guest
$router->get('resources', 'Resources@index'); //*🚀
$router->get('resource', 'Resources@show', ['Validation@id@resource']); //*🚀

//? Admin
$router->post('resource', 'Resources@store', ['Auth@admin', 'Validation@title|description|link@resource']); //*🚀
$router->post('resource/update', 'Resources@update', ['Auth@admin', 'Validation@id|description|link@resource']); //*🚀
$router->post('resource/delete', 'Resources@destroy', ['Auth@admin', 'Validation@id@resource']); //*🚀

/**
 ** Interview Questions Routes
 */
//? Guest
$router->get('questions', 'Questions@index'); //*🚀
$router->get('question', 'Questions@show', ['Validation@id@question']); //*🚀

//? Admin
$router->post('question', 'Questions@store', ['Auth@admin', 'Validation@question|answer@question']); //*🚀
$router->post('question/update', 'Questions@update', ['Auth@admin', 'Validation@id|answer@question']); //*🚀
$router->post('question/delete', 'Questions@destroy', ['Auth@admin', 'Validation@id@question']); //*🚀

/**
 ** Modules Routes
 */
//? Guest
$router->get('module', 'Modules@show', ['Validation@id@module']); //*🚀

//? Client
$router->post('module/completed', 'Modules@toggleCompleted', ['Auth@client', 'Validation@id@module']); //*🚀

/**
 ** Roadmaps Routes
 */
//? Guest
$router->get('roadmaps', 'Roadmaps@index'); //*🚀
$router->get('roadmap', 'Roadmaps@show', ['Validation@id@roadmap']); //*🚀
$router->get('roadmap/modules', 'Roadmaps@getModules', ['Validation@id@roadmap']); //*🚀

//? Client
$router->get('roadmap/status', 'Roadmaps@status', ['Auth@client', 'Validation@id@roadmap']); //*🚀
$router->post('roadmap/relaxed', 'Roadmaps@toggleRelaxed', ['Auth@client', 'Validation@id@roadmap']); //*🚀

//? Admin
$router->post('roadmap', 'Roadmaps@store', ['Auth@admin', 'Validation@title|description|modules@roadmap']); //*🚀
$router->post('roadmap/delete', 'Roadmaps@destroy', ['Auth@admin', 'Validation@id@roadmap']); //*🚀
