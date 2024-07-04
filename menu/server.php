<?php

require_once 'stripe-composer/init.php';


\Stripe\Stripe::setApiKey($stripeLiveSecretKey);


$content = json_decode(file_get_contents('php://input'), true);

$amount = intval($content['amount'] * 100);


$session = \Stripe\Checkout\Session::create([
    'payment_method_types' => ['card'],
    'line_items' => [[
        'price_data' => [
            'unit_amount' => $amount,
            'currency' => 'eur',
            'product_data' => [
                'name' => 'Menüü',
                'images' => ['https://child-city.ee/wp-content/uploads/2024/01/IMAGE-2024-01-22-200518.jpg'],
            ],
        ],
            'quantity' => 1,
    ]],
    'automatic_tax' => ['enabled' => true],
    'mode' => 'payment',
    'success_url' => 'https://child-city.ee/success/',
    'cancel_url' => 'https://child-city.ee/cancel/',
]);


header('Content-Type: application/json');

echo json_encode($session);


?>