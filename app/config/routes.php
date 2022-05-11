<?php

// Auth Routes
$router->post('register', 'Auth@register', ['Auth@guest', 'Validation@username|name|email|password@user']); //*🚀
$router->post('register/admin', 'Auth@registerAdmin', ['Auth@guest', 'Validation@username|name|email|password@user']); //*🚀
$router->post('login', 'Auth@login', ['Auth@guest', 'Validation@login|password@user']); //*🚀

// Resources Routes
$router->get('resources', 'Resources@index'); //*🚀
$router->get('resource', 'Resources@show', ['Validation@id@resource']); //*🚀
$router->post('resource', 'Resources@store', ['Auth@admin', 'Validation@title|description|link@resource']); //*🚀
$router->post('resource/update', 'Resources@update', ['Auth@admin', 'Validation@id|title|description|link@resource']); //*🚀
$router->post('resource/delete', 'Resources@destroy', ['Auth@admin', 'Validation@id@resource']); //*🚀

// Interview Questions Routes
$router->get('questions', 'Questions@index'); //*🚀
$router->get('question', 'Questions@show', ['Validation@id@question']); //*🚀
$router->post('question', 'Questions@store', ['Auth@admin', 'Validation@question|answer@question']); //*🚀
$router->post('question/update', 'Questions@update', ['Auth@admin', 'Validation@id|question|answer@question']); //*🚀
$router->post('question/delete', 'Questions@destroy', ['Auth@admin', 'Validation@id@question']); //*🚀

// Modules Routes
$router->get('module', 'Modules@show', ['Auth@client', 'Validation@id@module']); //*🚀
$router->post('module/completed', 'Modules@toggleCompleted', ['Auth@client', 'Validation@id@module']); //*🚀

// Roadmaps Routes
$router->get('roadmaps', 'Roadmaps@index@roadmap'); //*🚀
$router->get('roadmap/modules', 'Roadmaps@getModules', ['Auth@client', 'Validation@id@roadmap']); //*🚀
$router->get('roadmap/status', 'Roadmaps@status', ['Auth@client', 'Validation@id@roadmap']); //*🚀
$router->get('roadmap', 'Roadmaps@show', ['Auth@client', 'Validation@id@roadmap']); //*🚀
$router->post('roadmap', 'Roadmaps@store', ['Auth@client', 'Validation@title|description|modules@roadmap']); //*🚀
$router->post('roadmap/relaxed', 'Roadmaps@toggleRelaxed', ['Auth@client', 'Validation@id@roadmap']); //*🚀
$router->post('roadmap/delete', 'Roadmaps@destroy', ['Auth@client', 'Validation@id@roadmap']); //*🚀
