<?php
$output = '';
if (isset($_POST['selector'], $_POST['validator'], $_POST['input_passwort0'])) {
    // receive the form variables
    $selector = $_POST['selector'];
    $validator = $_POST['validator'];
    if ($selector == '' || $validator == '') {
        $output = 'Ihre Anfrage zum Zurücksetzen des Passworts kann nicht ohne ein Berechtigungstoken verarbeitet werden';
    } else {
        // check if there is a token already saved for this user
        require_once('../../utilities/db_connection.php');
        $database = new DatabaseConnection();
        $conn = $database->getConn();
        // use prepared statement to avoid SQL injection
        $sql = "SELECT email, token FROM keis2_password_reset WHERE selector = ?
                AND expires >= ?";
        $statement1 = $conn->prepare($sql);
        $time = time();
        $statement1->bind_param("ss", $selector, $time);
        $statement1->execute();
        $statement1->store_result();
        $email = '';
        $token = '';
        // token found
        if ($statement1->num_rows == 1) {
            $statement1->bind_result($email, $token);
            $statement1->fetch();
            $statement1->free_result();
            $statement1->close();
            $calc = hash('sha256', hex2bin($validator));
            // Validate the token
            if (hash_equals($calc, $token)) {
                // check if there is a user in the users table matching the email address found in the password_reset table
                $statement2 = $conn->prepare("SELECT id FROM keis2_benutzer WHERE email = ?");
                $statement2->bind_param('s', $email);
                $statement2->execute();
                $result = $statement2->get_result();
                if ($result->num_rows == 1) {
                    // Delete any related existing password reset
                    $sql = "DELETE FROM keis2_password_reset
                            WHERE email='".$conn->real_escape_string($email)."'";
                    $conn->query($sql);
                    
                    // Update password
                    $passwort = password_hash($_POST['input_passwort0'], PASSWORD_ARGON2I);
                    $sql = "UPDATE keis2_benutzer SET passwort=
                            '".$conn->real_escape_string($passwort)."',
                            update_date= NOW()
                            WHERE email='".$conn->real_escape_string($email)."'";
                    $conn->query($sql);
                    $output = 'Das Passwort wurde erfolgreich zurückgesetzt';
                } else {
                    if ($result->num_rows == 0) {
                        $output = 'Es gibt keinen Benutzer, dessen E-Mail-Adresse mit Ihrer übereinstimmt';
                    } else {
                        $output = 'Es gibt viele Benutzerkonten, die mit Ihrer E-Mail-Adresse verknüpft sind';
                    }
                }
            } else {
                $output = 'Ihr Autorisierungstoken ist ungültig';
            }
        } else {
            $output = 'Ihr Autorisierungstoken ist abgelaufen oder stimmt nicht mit dem gespeicherten überein';
        }
        $database->closeConnection();
    }
} else {
    $output = 'Die Formularvariablen sind ungültig oder werden nicht empfangen';
}
echo $output;