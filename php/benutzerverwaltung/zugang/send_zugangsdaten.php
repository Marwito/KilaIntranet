<?php
if(isset($_POST['benutzername'], $_POST['password'], $_POST['email'])){
    $to = $_POST['email'];
    $subject = "Zugangsdaten zu Eis2";
    $txt = "
            <html>
            <head>
            <title>Zugangsdaten zu Eis2</title>
            </head>
            <body>
            Hallo, <br>
            <p>
            hier sind Ihre Zugangsdaten für das neue software Eis2 : <br>
            Benutzername : " . $_POST['benutzername'] . "<br>
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