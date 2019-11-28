<?php
try {
    if (isset($_POST['input_select0'], $_POST['input_text0'])) {
        if ($_POST['input_select0'] != '' && $_POST['input_text0'] != '') {
            $monat = $_POST['input_select0']; // monat
            $jahr = $_POST['input_text0']; // jahr
            $daysNumber = cal_days_in_month(CAL_GREGORIAN, $monat, $jahr);
            $zeit_variable1 = '1' . '.' . $monat . '.' . $jahr;
            $zeit_variable2 = $daysNumber . '.' . $monat . '.' . $jahr;
        } else {
            $zeit_variable1 = DateTime::createFromFormat('d.m.Y', $_POST['input_text1'])->format('j.n.Y');
            $zeit_variable2 = DateTime::createFromFormat('d.m.Y', $_POST['input_text2'])->format('j.n.Y');
        }
        require_once('../../utilities/db_connection.php');
        require_once('../../utilities/functions.php');
        $database = new DatabaseConnection();
        $customFunction = new CustomFunctions();
        $conn = $database->getConn();
        if ($customFunction->check_exist_zeitraum($zeit_variable1, $zeit_variable2)) {
            $test = 0;
        } else {
            if ($customFunction->check_overlapping_rechnungen($zeit_variable1, $zeit_variable2)) {
                $test = 2;
            } else {
                if ($customFunction->check_unabgeschlossene_rechnungen($zeit_variable1)) {
                    $test = 1;
                } else {
                    $test = 0;
                }
            }
        }
        echo $test;
        $database->closeConnection();
    } else {
        throw new Exception('Formularvariablen sind ungültig oder werden nicht empfangen');
    }
} catch (Exception $ex) {
    echo $ex->getMessage();
    exit();
}
?>