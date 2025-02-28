<?php

use Ollyo\Task\Routes;
use Ollyo\Task\Controllers\Controller;

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/helper.php';

define('BASE_PATH', dirname(__FILE__));
define('BASE_URL', baseUrl());

$products = [
    [
        'name' => 'Minimalist Leather Backpack',
        'image' => BASE_URL . '/resources/images/backpack.webp',
        'qty' => 1,
        'price' => 120,
    ],
    [
        'name' => 'Wireless Noise-Canceling Headphones',
        'image' => BASE_URL . '/resources/images/headphone.jpg',
        'qty' => 1,
        'price' => 250,
    ],
    [
        'name' => 'Smart Fitness Watch',
        'image' => BASE_URL . '/resources/images/watch.webp', 
        'qty' => 1,
        'price' => 199,
    ],
    [
        'name' => 'Portable Bluetooth Speaker',
        'image' => BASE_URL . '/resources/images/speaker.webp',
        'qty' => 1,
        'price' => 89,
    ],
];
$shippingCost = 10;

$data = [
    'products' => $products,
    'shipping_cost' => $shippingCost,
    'address' => [
        'name' => 'Sherlock Holmes',
        'email' => 'sherlock@example.com',
        'address' => '221B Baker Street, London, England',
        'city' => 'London',
        'post_code' => 'NW16XE',
    ]
];

Routes::get('/', function () {
    return view('app', []);
});

Routes::get('/checkout', function () use ($data) {
    return view('checkout', $data);
});

Routes::get('/payment/success', function () {
    return view('payment_success', []);
});

Routes::get('/payment/cancel', function () {
    return view('payment_cancel', []);
});

Routes::post('/checkout', function ($request) {
    // @todo: Implement PayPal payment gateway integration here
    // 1. Initialize PayPal API client with credentials
    // 2. Create payment with order details from $data
    // 3. Execute payment and handle response
    // 4. If payment successful, save order and redirect to thank you page
    // 5. If payment fails, redirect to error payment page with message

    // Consider creating a dedicated controller class to handle payment processing
    // This helps separate payment logic from routing and keeps code organized
    Controller::checkout();
});

// Register thank you & payment failed routes with corresponding views here.

$route = Routes::getInstance();
$route->dispatch();
?>
