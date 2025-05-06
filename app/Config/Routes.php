<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Login::index', ['as' => 'home']);
$routes->match(['GET', 'POST'], 'login', 'Login::index', ['as' => 'login']);
$routes->get('about', function () {
    return view('about');
}, ['filter' => 'auth']);

$routes->match(['GET', 'POST'], 'forgot/password', 'Login::forgot_password', ['as' => 'forgot-password']);
$routes->match(['GET', 'POST'], 'reset/password', 'Login::reset_password', ['as' => 'reset-password']);
$routes->match(['GET', 'POST'], 'signup', 'Signup::index', ['as' => 'signup']);
$routes->get('admincp/tasks', 'Task::index', ['filter' => 'auth', 'as' => 'tasks']);
$routes->match(['GET', 'POST'], 'admincp/task/create', 'Task::create', ['as' => 'create-task']);
$routes->match(['GET', 'POST'], 'admincp/task/update/(:num)', 'Task::update/$1', ['as' => 'edit-task']);
$routes->delete('admincp/task/delete/(:num)', 'Task::delete/$1');
$routes->get('admincp/task/categories', 'TaskCategory::index', ['filter' => 'auth', 'as' => 'task-categories']);
$routes->match(['GET', 'POST'], 'admincp/task/category/create', 'TaskCategory::create', ['as' => 'create-task-category']);
$routes->match(['GET', 'POST'], 'admincp/task/category/update/(:num)', 'TaskCategory::update/$1', ['as' => 'edit-task-category']);
$routes->delete('admincp/task/category/delete/(:num)', 'TaskCategory::delete/$1');
$routes->get('admincp', 'Dashboard::index', ['filter' => 'auth', 'as' => 'admincp']);
$routes->get('admincp/dashboard', 'Dashboard::index', ['filter' => ['role:admin-access'], 'as' => 'dashboard']);
$routes->get('logout', 'Logout::index', ['as' => 'logout']);
