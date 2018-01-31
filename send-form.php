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

if (isset($_POST['send'])) {
    $err = false;
    $msg = '';
    $email = '';

    //Apply some basic validation and filtering to the subject
    if (array_key_exists('subject', $_POST)) {
        $subject = substr(strip_tags($_POST['subject']), 0, 255);
    } else {
        $subject = 'No subject given';
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
    //Get store name
    if (array_key_exists('phone_number' , $_POST)) {
        $phone_number = substr(strip_tags($_POST['phone_number']), 0, 255);
    }
    //Get store name
    if (array_key_exists('cell_number' , $_POST)) {
      $cell_number = substr(strip_tags($_POST['cell_number']), 0, 255);
    }
    //Get store name
    if (array_key_exists('street' , $_POST)) {
      $street = substr(strip_tags($_POST['street']), 0, 255);
    }
    //Get store name
    if (array_key_exists('city' , $_POST)) {
      $city = substr(strip_tags($_POST['city']), 0, 255);
    }
    //Get store name
    if (array_key_exists('state' , $_POST)) {
      $state = substr(strip_tags($_POST['state']), 0, 255);
    }
    //Get store name
    if (array_key_exists('zip' , $_POST)) {
      $zip = substr(strip_tags($_POST['zip']), 0, 255);
    }
    //Get store name
    if (array_key_exists('tax_id' , $_POST)) {
      $tax_id = substr(strip_tags($_POST['tax_id']), 0, 255);
    }
    

    // I'm using a set $to address, don't need anyone else getting these emails.
    $to = 'josh@dariwholesales.com';

    //Make sure the address they provided is valid before trying to use it
    if (array_key_exists('email', $_POST) and PHPMailer::validateAddress($_POST['email'])) {
        $email = $_POST['email'];
    } else {
        $msg .= "Error: invalid email address provided";
        $err = true;
    }
    // Upload the file in the file uploader
    //$uploadfile = tempnam(sys_get_temp_dir(), hash('sha256', $_FILES['userfile']['name.png']));
    


    //If no error, then send the message.
    if (!$err) {
        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->isHTML(true);
        $mail->Host = 'mail.dariwholesales.com';
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'josh@dariwholesales.com';                 // SMTP username
        $mail->Password = 'ChiChi617$';                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;      
        $mail->CharSet = 'utf-8';
        if (isset($_FILES['userfile']) &&
            $_FILES['userfile']['error'] == UPLOAD_ERROR_OK) {
            $mail->addAttachment($_FILES['userfile']['tmp_name'],
                                  $_FILES['userfile']['name']);
      }
        //It's important not to use the submitter's address as the from address as it's forgery,
        //which will cause your messages to fail SPF checks.
        //Use an address in your own domain as the from address, put the submitter's address in a reply-to
        $mail->setFrom('sales@dariwholesales.com', (empty($name) ? 'Contact form' : $name));
        $mail->addAddress($to);
        $mail->addReplyTo($email, $first_name);
        $mail->Subject = 'New Customer: ' . $store_name;
        // Start composing the body of the e-mail
        $mail->Body = "<h2>Store Name: </h2> " . $store_name . "<br>";
        $mail->Body .= "<h2>Phone Number: </h2> " . $phone_number . "<br>";
        $mail->Body .= "<h2>Cell Number: </h2> " . $cell_number . "<br>";
        $mail->Body .= "<h2>Tax ID Number:</h2>" . $tax_id . "<br>";
        $mail->Body .= "<h2>Address</h2>" . $street . " , " . $city . " , " . "<br>" . $state . " , " . $zip . "<br>"; 
        //$mail->addAttachment($uploadfile);
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
          <a href="/open-account.html">New Customer</a>
        </li>
      </ul>

      <ul id="nav-mobile" class="sidenav">
        <li>
          <a href="/index.html">Home</a>
        </li>
        <li>
          <a href="/open-account.html">New Customer</a>
        </li>
      </ul>
      <a href="#" data-target="nav-mobile" class="sidenav-trigger">
        <i class="material-icons">menu</i>
      </a>
    </div>
  </nav>

  <!-- Account Sign-up Form -->
  <div class="container" height="1024px">
      <br>
      <br>
      <h1 class="header center orange-text"><b>Thanks!</b></h1>
      <div class="row center">
        <h5 class="header col s12 light">Your account has been submitted.</h5>
        <?php echo $msg ?>
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
  <!-- <script type="text/javascript" src="/form-submission-handler.js"></script> -->
  <!-- <script src="/js/jquery.validate.js"></script> -->
  <!-- <script src="/js/form-validator.js"></script> -->

</body>

</html>