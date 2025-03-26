<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// testing
$routes->get('/testing', 'Home::testing');

$routes->get('/', 'Home::index');
$routes->get('/background/get_all_bg', 'Admin\Background::get_all_bg');
$routes->get('/frame/get_all_frame', 'Admin\Frame::get_all_frame');
$routes->get('/order', 'Home::order');
$routes->get('/payment/notify', 'Payment::notify');
$routes->get('/payment/check/(:any)', 'Payment::checkInvoice/$1');
$routes->get('/payment/(:any)', 'Home::payment/$1');
$routes->get('/print/(:any)', 'Home::print/$1');
$routes->get('/frame', 'Home::frame');
$routes->get('/finish', 'Home::finish');
$routes->get('/filter/(:any)', 'Home::filter/$1');
$routes->get('/download/(:any)', 'Home::userFiles/$1');
$routes->get('/browse/(:any)', 'Home::browseFiles/$1');
$routes->get('/delete/(:any)', 'Admin\Photo::delete_userFiles/$1');
$routes->get('/camera', 'Home::camera');
$routes->get('/capture', 'Home::capture');
$routes->get('/sign', 'Home::sign');
$routes->get('/login', 'Auth::index');
