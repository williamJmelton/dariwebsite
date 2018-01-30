<?php
/**
 * PHPMailer simple contact form example.
 * If you want to accept and send uploads in your form, look at the send_file_upload example.
 */
//Import the PHPMailer class into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/SMTP.php';

if (array_key_exists('to', $_POST)) {
    $err = false;
    $msg = '';
    $email = '';
    //Apply some basic validation and filtering to the subject
    if (array_key_exists('subject', $_POST)) {
        $subject = substr(strip_tags($_POST['subject']), 0, 255);
    } else {
        $subject = 'No subject given';
    }
    //Apply some basic validation and filtering to the query
    if (array_key_exists('query', $_POST)) {
        //Limit length and strip HTML tags
        $query = substr(strip_tags($_POST['query']), 0, 16384);
    } else {
        $query = '';
        $msg = 'No query provided!';
        $err = true;
    }
    //Get first name
    if (array_key_exists('first_name' , $_POST)) {
        $first_name = substr(strip_tags($_POST['first_name']), 0, 255);    
    }
    //Get last name
    if (array_key_exists('last_name' , $_POST)) {
        $last_name = substr(strip_tags($_POST['last_name']), 0, 255);    
    }
    //Get store name
    if (array_key_exists('store_name' , $_POST)) {
        $store_name = $_POST['store_name']; 
    }
    //Validate to address
    //Never allow arbitrary input for the 'to' address as it will turn your form into a spam gateway!
    //Substitute appropriate addresses from your own domain, or simply use a single, fixed address
    // if (array_key_exists('to', $_POST) and in_array($_POST['to'], ['josh', 'sales'])) {
    //     $to = $_POST['to'] . '@dariwholesales.com';
    // } else {
    //     $to = 'josh@dariwholesales.com';
    // }
    // I'm using a set $to address, don't need anyone else getting these emails.
    $to = 'josh@dariwholesales.com';
    //Make sure the address they provided is valid before trying to use it
    if (array_key_exists('email', $_POST) and PHPMailer::validateAddress($_POST['email'])) {
        $email = $_POST['email'];
    } else {
        $msg .= "Error: invalid email address provided";
        $err = true;
    }
    //If no error, then send the message.
    if (!$err) {
        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->Host = 'mail.dariwholesales.com';
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'josh@dariwholesales.com';                 // SMTP username
        $mail->Password = 'ChiChi617$';                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;      
        $mail->CharSet = 'utf-8';
        //It's important not to use the submitter's address as the from address as it's forgery,
        //which will cause your messages to fail SPF checks.
        //Use an address in your own domain as the from address, put the submitter's address in a reply-to
        $mail->setFrom('sales@dariwholesales.com', (empty($name) ? 'Contact form' : $name));
        $mail->addAddress($to);
        $mail->addReplyTo($email, $name);
        $mail->Subject = 'New Customer: ' . $store_name;
        $mail->Body = "Customer Information\n\n" . $store_name;
        if (!$mail->send()) {
            $msg .= "Mailer Error: " . $mail->ErrorInfo;
        } else {
            $msg .= "Message sent!";
        }
    }
} ?>