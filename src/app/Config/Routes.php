<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->get('/comments/page/(:num)', 'Comments::index/$1');

$routes->post('/comments/addComment', 'Comments::addComment');

$routes->post('/comments/deleteComment/(:num)', 'Comments::deleteComment/$1');