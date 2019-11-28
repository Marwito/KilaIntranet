<?php
if(isset($_POST['email'])){
    $selector = bin2hex(random_bytes(8));
    $token = random_bytes(32);
    require_once('../../utilities/constants.php');
    $url = sprintf('%s/php/benutzerverwaltung/zugang/handle_reset_request.php?%s', Constants::getBaseURL(), http_build_query([
            'selector' => $selector,
            'validator' => bin2hex($token)
    ]));
    // Token expiration
    $expires = new DateTime('NOW');
    $expires->add(new DateInterval('PT01H')); // 1 hour

    // Delete any existing tokens for this user
    require_once('../../utilities/db_connection.php');
    $database = new DatabaseConnection();
    $conn = $database->getConn();
    $sql = "DELETE FROM keis2_password_reset WHERE
            email = '".$conn->real_escape_string($_POST['email'])."'";
    
    $conn->query($sql);
    // Insert reset token into database
    $token = hash('sha256', $token);
    $sql = "INSERT INTO keis2_password_reset (email, selector, token, expires,
            insert_date)
    		VALUES ('".$conn->real_escape_string($_POST['email'])."',
            '".$conn->real_escape_string($selector)."',
            '".$conn->real_escape_string($token)."',
            '".$conn->real_escape_string($expires->format('U'))."',
            NOW())";
    $conn->query($sql);
    
    // send reset email
    $to = $_POST['email'];
    $subject = "Zurücksetzen Ihres Passworts (Eis2)";
    $link = sprintf('<a href="%s">%s</a></p>', $url, $url);
    $txt = "
            <html>
            <head>
            <title>Zurücksetzen Ihres Passworts</title>
            </head>
            <body>
            Hallo, <br>
            <p>
            Anscheinend haben Sie auf der Eis2-Website eine
            Passwortrücksetzung angefordert. Um Ihr Passwort zurückzusetzen, 
            klicken Sie bitte auf den Link unten. Wenn Sie nicht darauf 
            klicken können, fügen Sie es bitte in die Adressleiste Ihres
            Webbrowsers ein! Bitte beachten Sie, dass dieser Link nur eine 
            Stunde gültig ist!</p>"
            .$link. "<br><br>
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
    $database->closeConnection();
} else {
    echo 'Formularvariable ist ungültig oder wird nicht empfangen';
}