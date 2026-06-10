<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// Landing Page
$routes->get('/', 'Home::index');
// Public search from home
$routes->get('search', 'Home::search');

// Auth Routes
$routes->group('auth', function ($routes) {
    $routes->get('login', 'Auth::login');
    $routes->post('login', 'Auth::login');
    $routes->get('register', 'Auth::register');
    $routes->post('register', 'Auth::register');
    $routes->get('logout', 'Auth::logout');
});

// Admin Routes
$routes->group('admin', ['filter' => 'auth'], function ($routes) {
    $routes->get('dashboard', 'Admin::dashboard');
    
    // Books Management
    $routes->get('books', 'Admin::booksIndex');
    $routes->get('books/create', 'Admin::bookCreate');
    $routes->post('books/store', 'Admin::bookStore');
    $routes->get('books/edit/(:num)', 'Admin::bookEdit/$1');
    $routes->post('books/update/(:num)', 'Admin::bookUpdate/$1');
    $routes->get('books/delete/(:num)', 'Admin::bookDelete/$1');
    
    // Peminjaman Management
    $routes->get('peminjaman', 'Admin::peminjaman');
    $routes->get('approve-return/(:num)', 'Admin::approveReturn/$1');
    // Users Management
    $routes->get('users', 'Admin::usersIndex');
    $routes->get('users/create', 'Admin::userCreate');
    $routes->post('users/store', 'Admin::userStore');
    $routes->get('users/edit/(:num)', 'Admin::userEdit/$1');
    $routes->post('users/update/(:num)', 'Admin::userUpdate/$1');
    $routes->get('users/toggle/(:num)', 'Admin::toggleUserStatus/$1');
    // Site images
    $routes->get('site/images', 'Admin::siteImages');
    $routes->post('site/images/upload', 'Admin::uploadSiteImage');
});

// User Routes
$routes->group('user', ['filter' => 'auth'], function ($routes) {
    $routes->get('dashboard', 'User::dashboard');
    $routes->get('search', 'User::searchBooks');
    $routes->get('borrow/(:num)', 'User::borrowBook/$1');
    $routes->get('history', 'User::history');
    $routes->get('return/(:num)', 'User::returnBookRequest/$1');
});
