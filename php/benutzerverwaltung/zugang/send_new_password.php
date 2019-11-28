<?php
if(isset($_POST['email'], $_POST['password'])){
    $to = $_POST['email'];
    $subject = "neues Passwort für Eis2";
    $txt = "
            <html>
            <head>
            <title>neues Passwort für Eis2</title>
            </head>
            <body>
            Hallo, <br>
            <p>
            hier ist ihr neues Passwort für Eis2 : <br>
            Passwort : " . $_POST['password'] . "</p>
            Webadmin
            </body>
            </html>
    ";
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: noreply@kinderland-plus.de" . "\r\n";
    if (mail($to, $subject, $txt, $headers) === true) {
        echo 'OK';
    } else {
        echo 'no success';
    } 
} else {
    echo 'POST-variablen sind ungültig oder werden nicht empfangen';
}