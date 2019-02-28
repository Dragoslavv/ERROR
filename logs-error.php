<?php
error_reporting( E_ALL );
ini_set('display_errors',1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$mail = new PHPMailer(true); // Passing `true` enables exceptions

//Convert array to string
function array2string($data){
    $log_a = "";
    foreach ($data as $key => $value) {
        if(is_array($value))    $log_a .= "[".$key."] => (". array2string($value). ") \n";
        else                    $log_a .= "[".$key."] => ".$value."\n";
    }
    return $log_a;
}

function readFile1( $filename )
{

    $handle = fopen( $filename, "r" ) or die ( 'File opening failed' );

    $line = fgets( $handle );

    $array = array();

    while ( !feof($handle) ) {

        if( preg_match("/(ERROR.*)/", $line, $res) == 1 ) {

            foreach ($res as $val) $array[] = '<br>'. $val ;

        }

        $line = fgets( $handle );
    }

    $countArray = array_count_values($array);

    fclose( $handle );

    $array2string = array2string($countArray);

    try {
        $mail = new PHPMailer(); // create a new object
        $mail->IsSMTP(); // enable SMTP
        $mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
        $mail->SMTPAuth = true; // authentication enabled
        $mail->SMTPSecure = 'ssl'; // secure transfer enabled  for Gmail
        $mail->Host = "smtp.gmail.com";
        $mail->Port = 465; // or 587
        $mail->IsHTML(true);
        $mail->Username = "dragoslav.predojevic@procescom.com";
        $mail->Password = "Gagi4321";
        $mail->SetFrom("gagipredojevic65@gmail.com","Dragoslav");
        $mail->SetFrom("milos.vesic@procescom.com","Milos");

        $mail->Subject = "Read the log file";
        $mail->Body = ".$array2string.";
        $mail->AddAddress("gagipredojevic65@gmail.com");
//        $mail->AddAddress("milos.vesic@procescom.com");
        if(!$mail->Send()) {
            echo "Mailer Error: " . $mail->ErrorInfo;
        } else {
            echo "Message has been sent";
        }
    } catch ( Exception $e ) {
        echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
    }
}


$time = date("H:i");

// Read the log file every morning at 7 o'clock

if ( $time == "07:00" ) {

    $data  = date('Y-m-d',strtotime("-1 days"));

    $path    = './log';
    $files = array_diff(scandir($path), array('.', '..'));

    foreach ($files as $value) {
        if ( preg_match('/(.log-.*)/', $value, $matches, PREG_OFFSET_CAPTURE ) && preg_match('/(.log)/', $value, $output, PREG_OFFSET_CAPTURE ) ){

            readFile1($path . '/debug_' . $data . $matches[0][0]);
            echo "\n\n\n";
            readFile1($path . '/debug_' . $data . $output[0][0]);

        }
    }

}
