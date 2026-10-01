<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ================= 1 & 2. PUBLIC (Homepage & Auth) =================
$routes->get('/', 'HomeController::index');
$routes->get('login', 'AuthController::loginForm');
$routes->post('login', 'AuthController::login');
$routes->get('logout', 'AuthController::logout');
$routes->get('register', 'AuthController::registerForm');
$routes->post('register', 'AuthController::register');
$routes->post('login/demo/(:segment)', 'AuthController::demoLogin/$1');
$routes->get('lang/(:any)', 'LanguageController::switch/$1');

// ================= APP AUTH (User Session) =================
$routes->group('', ['filter' => 'auth'], function ($routes) {

    // 3. Dashboard Utama: Feed Harian (My Log & Circle)
    $routes->get('dashboard', 'DashboardController::index');
    $routes->get('friends', 'FriendController::index');
    $routes->post('friends/add', 'FriendController::add');
    $routes->post('friends/accept/(:num)', 'FriendController::accept/$1');
    $routes->post('friends/reject/(:num)', 'FriendController::reject/$1');
    $routes->post('follow/toggle/(:num)', 'FriendController::toggleFollow/$1');
    $routes->post('sparks/ask-ai', 'DashboardController::askSparksAi');
    $routes->get('chat', 'ChatController::index');
    $routes->post('chat/send', 'ChatController::sendMessage');
    $routes->get('inbox', 'DirectMessageController::index');
    $routes->get('inbox/(:num)', 'DirectMessageController::index/$1');
    $routes->post('inbox/send/(:num)', 'DirectMessageController::send/$1');
    $routes->get('notifications/feed', 'NotificationsController::feed');
    $routes->post('notifications/read/(:num)', 'NotificationsController::markRead/$1');
    $routes->post('notifications/read-all', 'NotificationsController::markAllRead');

    // 4. Capture Daily Log & Reminder Agenda (.ics sync)
    $routes->get('capture', 'CaptureController::index');
    $routes->post('capture/publish', 'CaptureController::publish');
    $routes->get('calendar/sync-ics/(:num)', 'CaptureController::downloadIcs/$1');
    $routes->get('moments/edit/(:num)', 'CaptureController::edit/$1');
    $routes->post('moments/update/(:num)', 'CaptureController::update/$1');
    $routes->post('post/react', 'PostController::react');
    $routes->post('post/comment', 'PostController::comment');
    $routes->post('post/share', 'PostController::share');

    // Manajemen Jadwal / Agenda
    $routes->post('jadwal/store', 'ScheduleController::store');
    $routes->post('jadwal/done/(:num)', 'ScheduleController::markDone/$1');
    $routes->post('jadwal/delete/(:num)', 'ScheduleController::delete/$1');

    // 5. Memories, Calendar & Recaps (3 Tab Arsip)
    $routes->get('memories', 'MemoriesController::index');
    $routes->post('memories/upload-to-agenda', 'MemoriesController::uploadToAgenda');
    $routes->get('rekap', 'RekapController::index');

    // 6. Profile Akun
    $routes->get('profile', 'ProfileController::index');
    $routes->get('profile/(:segment)', 'ProfileController::index/$1');
    $routes->post('moments/delete/(:num)', 'ProfileController::deleteMoment/$1');
    $routes->post('profile/update-avatar', 'ProfileController::updateAvatar');
    $routes->post('profile/update-details', 'ProfileController::updateDetails');

    // 7. To-Do List & Deadline Reminder
    $routes->get('todo', 'TodoController::index');
    $routes->post('todo/create', 'TodoController::create');
    $routes->post('todo/toggle/(:num)', 'TodoController::toggle/$1');
    $routes->post('todo/delete/(:num)', 'TodoController::delete/$1');

});

// ================= ADMIN PANEL =================
$routes->group('admin', ['filter' => 'auth:admin'], function ($routes) {
    $routes->get('dashboard', 'Admin\DashboardController::index');
});
