<?php

require 'stripe-composer/init.php';
require 'vendor/autoload.php';


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/phpmailer/phpmailer/src/Exception.php';
require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/phpmailer/src/SMTP.php';

$stripe = new \Stripe\StripeClient($stripeLiveSecretKey);

$payload = @file_get_contents('php://input');
$sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
$event = null;

try {
  $event = \Stripe\Webhook::constructEvent(
    $payload, $sig_header, $endpoint_live_secret
  );
} catch(\UnexpectedValueException $e) {
  http_response_code(400);
  exit();
} catch(\Stripe\Exception\SignatureVerificationException $e) {
  http_response_code(400);
  exit();
}

  function sendEmail($product_name, $customer_amount, $customer_email, $open_visitation, $selected_source) {
        
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'localhost';
                $mail->SMTPAuth = false;
                $mail->Username = 'info@child-city.ee';
                $mail->Password = '';
                $mail->SMTPSecure = 'none';
                $mail->Port = 25;
                $mail->CharSet = 'UTF-8';
                $mail->isHTML(true);
                $mail->SMTPAutoTLS = false;
                $mail->setFrom('info@child-city.ee', 'Child City OÜ');
               $mail->Subject = $product_name;

               // Send to customer email
                $mail->addAddress($customer_email);
                $mail->Body = "
                    Tere,<br><br>

                    Teie lukukood on: <br>
                    Vaba külastamine: $open_visitation <br>
                    Kokku: $customer_amount eur<br><br>

                    NB! Palun lülitage välja tuled ja konditsioneer enne lahkumist.<br>

                    <img class='add' style='max-width: 350px; height: auto;' src='https://child-city.ee/wp-content/uploads/2024/07/2024-07-03-23.35.26.png'><br>
        Jäta Google'isse kommentaar <a href='https://search.google.com/local/writereview?placeid=ChIJxWboBgrtkkYR8S3V8VM1SYM'>siia</a><br><br>
       
        Lugupidamisega<br>
        Child City OÜ
                ";

                $mail->send();

                    // Clear the recipient addresses
                $mail->clearAddresses();

                // Send to Child City email
                    $mail->addAddress('info@child-city.ee');
        $mail->Body = "
            Tere,<br><br>

            Teie lukukood on: <br>
            Vaba külastamine: $open_visitation <br>
            Klient sai meie kohta teada: $selected_source <br>
            Kokku: $customer_amount eur<br><br>

            NB! Palun lülitage välja tuled ja konditsioneer enne lahkumist.<br>

             <img class='add' style='max-width: 350px; height: auto;' src='https://child-city.ee/wp-content/uploads/2024/07/2024-07-03-23.35.26.png'><br>
        Jäta Google'isse kommentaar <a href='https://search.google.com/local/writereview?placeid=ChIJxWboBgrtkkYR8S3V8VM1SYM'>siia</a><br><br>
       
        Lugupidamisega<br>
        Child City OÜ
        ";
        $mail->send();


                return true;
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
    }
 

switch ($event->type) {
    case 'payment_intent.succeeded':
        $paymentIntent = $event->data->object;
        $product_name = $paymentIntent->metadata->product_name ?? null;
        $open_visitation = $paymentIntent->metadata->open_visitation ?? null;
        $customer_email = $paymentIntent->receipt_email;
        $customer_amount = $paymentIntent->amount / 100;
        $selected_source = $paymentIntent->metadata->selected_source ?? null;
        
        if ($product_name == 'e-pilet') {
                    sendEmail($product_name, $customer_amount, $customer_email, $open_visitation, $selected_source);

        }
    break;
        
    default:
        echo 'Received unknown event type ' . $event->type;
        break;
}

http_response_code(200);
?>