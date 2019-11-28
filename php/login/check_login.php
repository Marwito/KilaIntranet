<?php
require_once('../utilities/constants.php');
if (isset($_POST["myusername"], $_POST["mypassword"])) {
    // check if the user exists
    require_once('../utilities/db_connection.php');
    $database = new DatabaseConnection();
    $conn = $database->getConn();
    
    # Prepare the statement to avoid SQL injection
    $sql = "SELECT id, passwort, position, aktiv, einrichtung, kueche FROM keis2_benutzer
            WHERE benutzername=?";
    $stmt = $conn->prepare($sql);
    
    # Bind the parameters, this is where the variable "enter" the query safely.
    $stmt->bind_param("s", $_POST["myusername"]);
    
    # Execute the statement
    $stmt->execute();
    
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($id, $passwort, $position, $aktiv, $einrichtung, $kueche);
        $stmt->fetch();
        $stmt->free_result();
        if ($aktiv == 1) {
            if (password_verify($_POST["mypassword"], $passwort)) {
                // include the function class to use a custom function to ghet the name of a user's position using its ID
                require_once('../utilities/functions.php');
                $customFunction = new CustomFunctions();
                // Start a new Session
                require_once('session.php');
                //session_start();
                $session = Session::getInstance();
                $_SESSION['username'] = $_POST['myusername'];
                $positionName = $customFunction->getNamePosition($position);
                $_SESSION['usergroup'] = $positionName;
                $_SESSION['kind'] = -1;
                if ($positionName == 'Administrator') {
                    $_SESSION['einrichtung_kueche'] = 0;
                    $url = '../administration/einstellungen.php';
                } else if($positionName == 'Leiter') {
                    $_SESSION['einrichtung_kueche'] = $einrichtung;
                    $url = '../AdebisKITA/kinder.php';
                } else if ($positionName == 'Eltern') {
                    $_SESSION['einrichtung_kueche'] = 0;
                    $url = '../elternbereich/elternbereich.php';
                } else if ($positionName == 'Caterer/Küche') {
                    $_SESSION['einrichtung_kueche'] = $kueche;
                    $url = '../kuechenbereich/kuechenbereich.php';
                } else {
                    $url = '../kuechenbereich/-------.php';
                }
            } else {
                $url = Constants::getBaseURL() . '/index.php?password_error=set';
            }
        } else {
            $url = Constants::getBaseURL() . '/index.php?user_deaktiviert=set';
        }
    } else {
        $url = Constants::getBaseURL() . '/index.php?login_error=set';
    }
    $stmt->close();
    $database->closeConnection();
} else {
    $url = Constants::getBaseURL() . '/index.php?checklogin_error=set';
}
header("location: ". $url);
?>