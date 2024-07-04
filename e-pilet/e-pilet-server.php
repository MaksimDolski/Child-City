<?php

require 'stripe-composer/init.php';
require 'vendor/autoload.php';


\Stripe\Stripe::setApiKey($stripeLiveSecretKey);

$content = json_decode(file_get_contents('php://input'), true);

$amount = intval($content['amount'] * 100);
$children_quantity = $content['children_quantity'] ?? null;
$hours_quantity = $content['hours_quantity'] ?? null;
$open_visitation = $content['open_visitation'] ?? null;
$selected_source = $content['selected_source'] ?? null;

$session = \Stripe\Checkout\Session::create([
    'payment_method_types' => ['card'],
    'line_items' => [[
        'price_data' => [
            'unit_amount' => $amount,
            'currency' => 'eur',
            'product_data' => [
                'name' => 'e-pilet',
                'images' => ['https://child-city.ee/wp-content/uploads/2024/01/IMAGE-2024-01-22-200518.jpg'],
            ],
            
        ],
        'quantity' => 1,
    ]],
    'automatic_tax' => ['enabled' => true],
    'metadata' => [
        'product_name' => 'e-pilet',
    ],

    'payment_intent_data'=> [
        "metadata" => [
            'product_name' => 'e-pilet',
            'open_visitation' => $open_visitation,
            'selected_source' => $selected_source,
        ],
    ],
    'mode' => 'payment',
    'success_url' => 'https://child-city.ee/success/',
    'cancel_url' => 'https://child-city.ee/cancel/'
]);

header('Content-Type: application/json');

echo json_encode($session);

?>