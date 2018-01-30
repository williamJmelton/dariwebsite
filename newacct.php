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
    //Apply some basic validation and filtering to the name
    if (array_key_exists('name', $_POST)) {
        //Limit length and strip HTML tags
        $name = substr(strip_tags($_POST['name']), 0, 255);
    } else {
        $name = '';
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
        $store_name = substr(strip_tags($_POST['store_name']), 0, 255);    
    }
    //Validate to address
    //Never allow arbitrary input for the 'to' address as it will turn your form into a spam gateway!
    //Substitute appropriate addresses from your own domain, or simply use a single, fixed address
    // if (array_key_exists('to', $_POST) and in_array($_POST['to'], ['josh', 'sales'])) {
    //     $to = $_POST['to'] . '@dariwholesales.com';
    // } else {
    //     $to = 'josh@dariwholesales.com';
    // }
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
<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0" />
  <title>Dari Wholesales</title>

  <!-- CSS  -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link href="css/materialize.min.css" type="text/css" rel="stylesheet" media="screen,projection" />
  <link href="css/style.css" type="text/css" rel="stylesheet" media="screen,projection" />
  <script src="//mozilla.github.io/pdf.js/build/pdf.js"></script>
</head>

<body>
  <nav class="light-blue lighten-1" role="navigation">
    <div class="nav-wrapper container">
      <!-- <a id="logo-container" href="#" class="brand-logo">Dari Wholesales</a> -->
      <img height="100%" class="logo" src="/img/logo.png">
      <ul class="right hide-on-med-and-down">
        <li>
          <a href="/index.html">Home</a>
        </li>
        <li>
          <a href="/index.html">New Customer</a>
        </li>
      </ul>

      <ul id="nav-mobile" class="sidenav">
        <li>
          <a href="/index.html">Home</a>
        </li>
        <li>
          <a href="/open-acct.html">New Customer</a>
        </li>
      </ul>
      <a href="#" data-target="nav-mobile" class="sidenav-trigger">
        <i class="material-icons">menu</i>
      </a>
    </div>
  </nav>


  <div class="section no-pad-bot" id="index-banner">
    <div class="container">
      <br>
      <br>
      <h1 class="header center orange-text">
        <b>Account Sign Up</b>
      </h1>
      <div class="row center">
        <h5 class="header col s12 light">We look forward to doing business with you.</h5>
      </div>
      <br>
      <br>
    </div>
  </div>

  <!-- Account Sign-up Form -->
  <div class="container">
    <div class="row">
      <form enctype="multipart/form-data" method="POST">
        class="col s12">
        <div class="row">
          <div class="input-field col s6 m6">
            <input name="first_name" id="first_name" type="text" class="validate">
            <label for="first_name">First Name</label>
          </div>
          <div class="input-field col s6 m6">
            <input name="last_name" id="last_name" type="text" class="validate">
            <label for="last_name">Last Name</label>
          </div>
        </div>
        <div class="row">
          <div class="input-field col m12 s12">
            <input name="email" id="email" type="email" class="validate">
            <label for="email" data-error="Please type an email address" data-success="OK">Email</label>
          </div>
        </div>
        <div class="row">
          <div class="input-field col s12 m12">
            <input name="store_name" id="store_name" type="text">
            <label for="store_name">Store Name</label>
          </div>
        </div>
        <div class="row">
          <div class="input-field col s6 m6">
            <input placeholder="xxx-xxx-xxxx" name="cell_number" id="cell_number" type="tel" pattern="^\d{3}-\d{3}-\d{4}$" >
            <label for="cell_number">Cellphone Number</label>
          </div>
          <div class="input-field col s6 m6">
            <input placeholder="xxx-xxx-xxxx" name="store_number" id="store_number" type="tel" pattern="^\d{3}-\d{3}-\d{4}$" >
            <label for="store_number"> Store Telephone Number</label>
          </div>
        </div>
        <div class="row">
          <div class="input-field col s6 m6">
            <input name="street" id="street" type="text" >
            <label for="street">Street</label>
          </div>
          <div class="input-field col s6 m6">
            <input name="city" id="city" type="text" >
            <label for="city">City</label>
          </div>
          <div class="input-field col s6 m6">
            <input name="state" id="state" type="text" >
            <label for="state">State</label>
          </div>
          <div class="input-field col s6 m6">
            <input name="zip" id="zip" type="text" >
            <label for="zip">Zip Code</label>
          </div>
        </div>
        <div class="row">
          <div class="input-field col s6 m6">
            <input name="tax_id" id="tax_id" type="text" pattern="^\d{9}$" >
            <label for="tax_id">Tax ID Number</label>
          </div>
          <div class="input-field col s6 m6">
            <input name="license_number" id="license_number" type="text" >
            <label for="license_number">Driver's License Number</label>
          </div>
        </div>
        <div class="row center">
          <div class="col s12 m12">
            <button name="send" id="submit-form-button" type="submit" class="btn-large waves-effect waves-light green">Submit</button>
            <button type="reset" class="btn-large waves-effect waves-light red">Clear</button>
          </div>
        </div>
        <!-- <iframe style="width: 100%; height: 600px; border: none;" src="/pdf/nctaxform.pdf"></iframe> -->
      </form>

      <!-- <form action="upload.php" method="post" enctype="multipart/form-data">
        Select image to upload:
        <input type="file" name="fileToUpload" id="fileToUpload">
        <input type="submit" value="Upload Image" name="submit">
    </form> -->

    </div>
  </div>


  <footer class="page-footer orange">
    <div class="container">
      <div class="row">
        <div class="col l6 s12">
          <h5 class="white-text">Company Bio</h5>
          <p class="grey-text text-lighten-4">In business for 17 years, Yousef Dari started this company from the ground up. Begining with only a truck and some
            general goods, he made Dari Wholesales what it is today. Backed with 17 years experience, we look forward to
            serving you.
          </p>


        </div>
        <div class="col l3 s12">
          <h5 class="white-text">Settings</h5>
          <ul>
            <li>
              <a class="white-text" href="#!">Link 1</a>
            </li>
            <li>
              <a class="white-text" href="#!">Link 2</a>
            </li>
            <li>
              <a class="white-text" href="#!">Link 3</a>
            </li>
            <li>
              <a class="white-text" href="#!">Link 4</a>
            </li>
          </ul>
        </div>
        <div class="col l3 s12">
          <h5 class="white-text">Connect</h5>
          <ul>
            <li>
              <a class="white-text" href="#!">Link 1</a>
            </li>
            <li>
              <a class="white-text" href="#!">Link 2</a>
            </li>
            <li>
              <a class="white-text" href="#!">Link 3</a>
            </li>
            <li>
              <a class="white-text" href="#!">Link 4</a>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <div class="footer-copyright">
      <div class="container">
        Made by
        <a class="orange-text text-lighten-3" href="#">Josh Melton</a>
      </div>
    </div>
  </footer>


  <!--  Scripts-->
  <script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
  <script src="js/materialize.js"></script>
  <script src="js/init.js"></script>
  <script src="js/pdfConfig.js"></script>
  <script type="text/javascript" src="/form-submission-handler.js"></script>
  <script src="/js/jquery.validate.js"></script>
  <script src="/js/form-validator.js"></script>

</body>

</html>