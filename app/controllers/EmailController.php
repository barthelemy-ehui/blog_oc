<?php
namespace App\Controllers;


class EmailController extends Controller
{
    public function send(): void
    {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $recipient = 'b.ehuinda@gmail.com';
        $mail_body = $_POST['message'];
        $subject = 'Email de - ' . $_POST['name'];
        $header = 'From: '. $name . ' <' . $email . '>\r\n';
    
        mail($recipient, $subject, $mail_body, $header);
    }
}