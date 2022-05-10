<?php

// Auth Routes
$router->post('register', 'Auth@register', ['Auth@guest', 'Validation@username|name|email|password']); //*🚀
$router->post('register/admin', 'Auth@registerAdmin', ['Auth@guest', 'Validation@username|name|email|password']); //*🚀
$router->post('login', 'Auth@login', ['Auth@guest', 'Validation@login|password']); //*🚀

// Resources Routes
$router->get('resources', 'Resources@index', ['Auth@client']); //*🚀
$router->get('resource', 'Resources@show', ['Auth@client', 'Validation@resource_id']); //*🚀
$router->post('resource', 'Resources@store', ['Auth@client', 'Validation@title|description|link']); //*🚀
$router->post('resource/update', 'Resources@update', ['Auth@client', 'Validation@resource_id|title|description|link']); //*🚀
$router->post('resource/visited', 'Resources@toggleVisited', ['Auth@client', 'Validation@resource_id']); //*🚀
$router->post('resource/delete', 'Resources@destroy', ['Auth@client', 'Validation@resource_id']); //*🚀

// Interview Questions Routes
$router->get('questions', 'Questions@index', ['Auth@client']); //*🚀
$router->get('question', 'Questions@show', ['Auth@client', 'Validation@id']); //*🚀
$router->post('question', 'Questions@store', ['Auth@client', 'Validation@question|answer']); //*🚀
$router->post('question/update', 'Questions@update', ['Auth@client', 'Validation@id|question|answer']); //*🚀
$router->post('question/delete', 'Questions@destroy', ['Auth@client', 'Validation@id']); //*🚀

// Modules Routes
$router->get('module', 'Modules@show', ['Auth@client', 'Validation@id']); //*🚀
$router->post('module/completed', 'Modules@toggleCompleted', ['Auth@client', 'Validation@id']); //*🚀

// Roadmaps Routes
$router->get('roadmaps', 'Roadmaps@index', ['Auth@client']); //*🚀
$router->get('roadmap/modules', 'Roadmaps@getModules', ['Auth@client', 'Validation@id']); //*🚀
$router->get('roadmap/status', 'Roadmaps@status', ['Auth@client', 'Validation@id']); //*🚀
$router->get('roadmap', 'Roadmaps@show', ['Auth@client', 'Validation@id']); //*🚀
$router->post('roadmap', 'Roadmaps@store', ['Auth@client', 'Validation@title|description|modules']); //*🚀
$router->post('roadmap/relaxed', 'Roadmaps@toggleRelaxed', ['Auth@client', 'Validation@id']); //*🚀
$router->post('roadmap/delete', 'Roadmaps@destroy', ['Auth@client', 'Validation@id']); //*🚀
