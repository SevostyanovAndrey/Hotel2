<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'UsersController::index');
$routes->post('users/getUsers', 'UsersController::getUsers');
$routes->post('users/addUser', 'UsersController::addUser');
$routes->get('users/delete/(:num)', 'UsersController::delete/$1');





