<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/background/get_all_bg', 'Admin\Background::get_all_bg');
$routes->get('/frame/get_all_frame', 'Admin\Frame::get_all_frame');
$routes->get('/order', 'Home::order');
$routes->get('/payment/(:any)', 'Home::payment/$1');
$routes->get('/frame', 'Home::frame');

