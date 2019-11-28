<?php
require_once('../login/session.php');
require_once('../utilities/constants.php');
$session = Session::getInstance();
if($session->checkSessionVariables('username', 'usergroup', 'kind')){
    require_once('../benutzerverwaltung/benutzer/benutzer_class.php');
    $benutzer = new Benutzer();
    if (!($benutzer->isEltern($session->usergroup) || $benutzer->isAdmin($session->usergroup) || $benutzer->isLeiter($session->usergroup))) {
        header('Location: ' . Constants::getBaseURL());
    }
}else {
    header('Location: ' . Constants::getBaseURL() . '/index.php?no_login=set');
}
if(isset($_GET['month']) && is_numeric($_GET['month'])) {
    $month = $_GET['month'];
} else {
    $month = 0;
}
require_once('../utilities/feiertage-api-connector.php');
$connector = LPLib_Feiertage_Connector::getInstance();
require_once('../utilities/db_connection.php');
$database = new DatabaseConnection();
$conn = $database->getConn();
if($benutzer->isEltern($session->usergroup)) {
    $hatRechte = 0;
    $sqlKind = "SELECT * FROM keis2_kind WHERE eltern=(SELECT id FROM keis2_benutzer WHERE benutzername='".$session->username."')";
    $resultKind = $conn->query($sqlKind);
    if($resultKind->num_rows == 1) {
        $rowKind = $resultKind->fetch_assoc();
    } else if($resultKind->num_rows > 1) {
        if($session->kind == -1) {
            $rowKind = $resultKind->fetch_assoc();
            $_SESSION['kind'] = $rowKind['id'];
        } else {
            do{
                $rowKind = $resultKind->fetch_assoc();
            } while($rowKind['id'] != $session->kind);
        }
    }
} else if(($benutzer->isAdmin($session->usergroup) || $benutzer->isLeiter($session->usergroup)) && $session->kind != -1) {
    $hatRechte = 1;
    $sqlKind = "SELECT * FROM keis2_kind WHERE id=".$session->kind;
    $resultKind = $conn->query($sqlKind);
    if($resultKind->num_rows == 1) {
        $rowKind = $resultKind->fetch_assoc();
    }
} else {
    throw new Exception("Kein Kind ausgew�hlt oder keine Berechtigung!");
}
$now = strtotime("now");

date_default_timezone_set('Europe/Berlin');

$sqlAbmeldefrist = "SELECT abmeldefrist, vortag FROM keis2_einrichtung WHERE id = ".$rowKind['zuordnung_einrichtung'];
$resultAbmeldefrist = $conn->query($sqlAbmeldefrist);
if ($resultAbmeldefrist->num_rows > 0) {
    $rowAbmeldefrist = $resultAbmeldefrist->fetch_assoc();
}

$abmeldefristTag = $rowAbmeldefrist['vortag'];
$abmeldefristZeit = $rowAbmeldefrist['abmeldefrist'];
$startdate = strtotime("first day of +".$month." months");
$temp = date("D", $startdate);
if($temp == "Sat" || $temp == "Sun")  //Wenn 1. eines Monats, dann Samstag oder Sonntag diese Woche nicht anzeigen
{
    $startdate = strtotime("Monday next week", $startdate);
} else {
    $startdate = strtotime("Monday this week", $startdate);     //startdate wird als ausgangszeitpunkt hergenommen, sonst ist "monday +0 weeks" nicht der montag dieser woche sondern nächster woche
}
$enddate = strtotime("last day of +".$month." months");
$enddate = intval(date('W', $enddate));
$startdateWeeks = intval(date('W', $startdate));
if($enddate < $startdateWeeks && $enddate != 1) {         //Für Jahresübergang, sonst ist weeksPerMonth negativ -> keine Tabelle
    $enddate = $enddate + $startdateWeeks;
} else if($enddate == 1) {
    $enddate = 53;
}
$weeksPerMonth = $enddate - $startdateWeeks;   //ausrechnen wie viele wochen angezeigt werden müssen

$sqlBestellung = "SELECT * FROM keis2_bestellung WHERE kind = '".$rowKind['id']."' AND update_date IS NULL AND datum = '";
$sqlAbbestellung = "SELECT * FROM keis2_abbestellung WHERE kind = '".$rowKind['id']."' AND update_date IS NULL AND datum = '";
$sqlTimeFormat = "Y-m-d";
$displayTimeFormat = "d.m.Y";
$variableTimeFormat = "Y_m_d";
$resultKindForDropdown = $conn->query($sqlKind);
setlocale(LC_TIME, 'de');
?>
				<div class="row">
					<div class="col">
						<h3><?php echo utf8_encode(strftime('%B', strtotime("first day of +".$month." months")));?></h3>
					</div>
						<?php 
						if($hatRechte)
				            echo '<div class="col">
                                    <h3 style="text-align:center">'.$rowKind["name"].' '.$rowKind["vorname"].'</h3>
                                  </div>
                                  <div class="col">
                                    <a href="../AdebisKITA/kinder.php" class="btn btn-primary btn-custom" style="float:right;">Zurück zur Kinderliste</a>
                                  </div>'
				        ?>
				</div>
				<?php if($resultKindForDropdown->num_rows > 1) {
				$kindSelect = '<div style="display:inline; float:right;">
				                 <label for="input_select1">Kind auswählen</label>
    		                     <select class="form-control" name="input_select1" id="input_select1">';
				      while($row = $resultKindForDropdown->fetch_assoc()){
		    	                         $kindSelect.='<option value="'.$row['id'].'"';
		    	                         $row['id'] == $session->kind ? $kindSelect.=' selected="selected"' : $kindSelect.='';
                                            $kindSelect.='>'.$row['vorname'].' '.$row['name'].'</option>';
		    	                     }
		    	$kindSelect.='</select>
				             </div>';
				echo $kindSelect;
				}?>
				<br>
				<div>
					<a href="./elternbereich.php?month=<?php echo $month-1 ?>" class="btn btn-primary btn-custom">Letzter Monat</a>
					<a href="./elternbereich.php" class="btn btn-primary btn-custom">Aktueller Monat</a>
					<a href="./elternbereich.php?month=<?php echo $month+1 ?>" class="btn btn-primary btn-custom">Nächster Monat</a>
				</div>
				<br>
				<form name="form" id="form" method="post" action="" class="needs-validation" novalidate>
				<input type="hidden" id="kind" name="kind" value="<?php echo $rowKind['id']; ?>">
				<!--  <label for="datetimepicker1">Geburtsdatum</label>
					<div class="form-group custom-font">
					    <input style="font-family:verdana;font-size:16px" type="text" class="form-control" data-toggle="datetimepicker" data-target="#datetimepicker1" id="datetimepicker1" name="input_text3" required>
					    <div class="invalid-feedback" style="font-family:verdana"> Bitte füllen Sie dieses Feld aus! </div>
				  	</div>-->
					<label>Zeitraum</label>
					<div class="row">
						<div class="col form-group custom-font">
							<label for="datetimepicker1">von</label>
					    	<input style="font-family:verdana;font-size:16px" type="text" class="form-control" id="datetimepicker1" name="datetimepicker1" data-toggle="datetimepicker" data-target="#datetimepicker1" required>
					    	<div class="invalid-feedback"> Bitte füllen Sie dieses Feld aus! </div>
						</div>
						<div class="col form-group custom-font">
							<label for="datetimepicker2">bis</label><br>
					    	<input style="font-family:verdana;font-size:16px" type="text" class="form-control"  id="datetimepicker2" name="datetimepicker2" data-toggle="datetimepicker" data-target="#datetimepicker2" required>
					    	<div class="invalid-feedback"> Bitte füllen Sie dieses Feld aus! </div>
						</div>
					</div>
				</form>
				<br>
				<button id="submitBestellen" name="submitBestellen" class="btn btn-primary btn-custom">Bestellen</button>
				<button id="submitAbbestellen" name="submitAbbestellen" class="btn btn-primary btn-custom">Abbestellen</button>
				<br>
				<br>
						<div class="card-deck" style="max-width:1390px;margin:0 auto;">
<?php
require_once('../utilities/functions.php');
$customFunction = new CustomFunctions();
$time = array();
for($i = 0; $i <= $weeksPerMonth; $i++)
{
    $time['Montag'] = strtotime("Monday +".$i." week", $startdate);
    $time['Dienstag'] = strtotime("Tuesday +".$i." week", $startdate);
    $time['Mittwoch'] = strtotime("Wednesday +".$i." week", $startdate);
    $time['Donnerstag'] = strtotime("Thursday +".$i." week", $startdate);
    $time['Freitag'] = strtotime("Friday +".$i." week", $startdate);
    $sqlDauerbestellung = "SELECT * FROM keis2_dauerbestellung WHERE kind = '".$rowKind['id']."'";
    $resultDauerbestellung = $conn->query($sqlDauerbestellung);
    $abbestellung = array();
    if($resultDauerbestellung->num_rows > 0) {
        $counter = 0;
        $rowDauerbestellung = $resultDauerbestellung->fetch_assoc();
        $counter++;
        while(strtotime($rowDauerbestellung['essenende']) < $time['Montag'] && $counter < $resultDauerbestellung->num_rows) {
            $rowDauerbestellung = $resultDauerbestellung->fetch_assoc();
            $counter++;
        }
        $abbestellung['Montag'] = $rowDauerbestellung['montag'];
        $abbestellung['Dienstag'] = $rowDauerbestellung['dienstag'];
        $abbestellung['Mittwoch'] = $rowDauerbestellung['mittwoch'];
        $abbestellung['Donnerstag'] = $rowDauerbestellung['donnerstag'];
        $abbestellung['Freitag'] = $rowDauerbestellung['freitag'];
        $essenstart = strtotime($rowDauerbestellung['essenstart']);
        $essenende = strtotime($rowDauerbestellung['essenende']);
        $essenkategorie = $rowDauerbestellung['essenkategorie'];
        $dauerbesteller = true;
    } else {
        $abbestellung['Montag'] = 0;
        $abbestellung['Dienstag'] = 0;
        $abbestellung['Mittwoch'] = 0;
        $abbestellung['Donnerstag'] = 0;
        $abbestellung['Freitag'] = 0;
        $essenstart = 0;
        $essenende = PHP_INT_MAX;
        $essenkategorie = -1;
        $dauerbesteller = false;
    }
?>
						
			      					<?php for($j = $time['Montag']; $j <= $time['Freitag']; $j = $j + 86400)
			      					{
			      					  $weekday = strftime("%A", $j);
			      					  $day = substr($weekday, 0, 2);
			      					  ?>
			      					  <div class="card text-center" style="min-width:202px;margin-bottom:20px;">
			      					  	<div class="card-body" id='<?php echo $date = date($sqlTimeFormat, $time[$weekday]);?>'>
			      					  		<h5 class="card-title"><?php echo $day.", ".date($displayTimeFormat, $time[$weekday]);?></h5>
			      					<?php
			      					    if($abbestellung[$weekday] && $time[$weekday] >= $essenstart && $time[$weekday] <= $essenende) {
			      						     $sql = $sqlAbbestellung.$date."';";
			      						     $result = $conn->query($sql);
			      						     if($result->num_rows) {
			      						         $bestellung = 0;
			      						     } else {
			      						         $bestellung = 1;
			      						     }
			      						} else {
			      					         $sql = $sqlBestellung.$date."';";
			      					         $result = $conn->query($sql);
			      					         if($result->num_rows) {
			      					             $bestellung = 1;
			      					         } else {
			      					             $bestellung = 0;
			      					         }
			      						} ?>
			      						<div id='<?php echo "bestellt".$day.$i; ?>'>
			      							 <p id='<?php echo "bestellt".$date; ?>'>
			      							 <?php if($connector->isFeiertagInLand($date, LPLib_Feiertage_Connector::LAND_BAYERN)) {
			      							            echo "Feiertag, keine Bestellungen möglich";
			      							       } else {
			      							           if($abbestellung[$weekday] && $time[$weekday] >= $essenstart && $time[$weekday] <= $essenende) {
			      							               echo "Essen bestellt: ".$customFunction->getNameEssenkategorie($essenkategorie);
			      							           } else if($result->num_rows) {
			      							               $row = $result->fetch_assoc();
			      							               echo "Essen bestellt: ".$customFunction->getNameEssenkategorie($row['essenkategorie']);
			      							           }
			      							       }?>
			      							</p>
			      							<?php 
			      							if(!$connector->isFeiertagInLand($date, LPLib_Feiertage_Connector::LAND_BAYERN)) {
			      							    if(($now > $abmeldefrist = strtotime("-".$abmeldefristTag." days ".$abmeldefristZeit, $time[$weekday])) && !$hatRechte) { 
			      							        $str = '<span class="d-inline-block" data-toggle="tooltip" data-placement="bottom" title="Essen kann nicht abbestellt werden, da die Frist schon abgelaufen ist.">
                                                                <a class="btn btn-danger btn-circle custom3 disabled" href="#">
                                                                    <span class="fa fa-trash-alt" aria-hidden="true"></span>
                                                                </a>
                                                            </span>';
			      							    } else {
			      							        $str = '<span class="d-inline-block" data-toggle="tooltip" data-placement="bottom" title="">
                                                                <a class="btn btn-danger btn-circle custom3" href="#">
                                                                    <span class="fa fa-trash-alt" aria-hidden="true" title="abbestellen"></span>
                                                                </a>
                                                            </span>';
			      							    }
                                            echo $str;
			      							}
			      							?>
			      						</div>
			      						<div id='<?php echo "nichtBestellt".$day.$i ?>'>
			      							<p> <?php if($connector->isFeiertagInLand($date, LPLib_Feiertage_Connector::LAND_BAYERN)) {
			      							            echo "Feiertag, keine Bestellungen möglich";
			      							       } else {
			      							            echo "Essen nicht bestellt";
			      							       }
			      							?></p>
			      							<?php 
			      						     if(!$connector->isFeiertagInLand($date, LPLib_Feiertage_Connector::LAND_BAYERN)) {
			      						         if($now > $abmeldefrist && !$hatRechte) {
			      						             $str = '<span class="d-inline-block" data-toggle="tooltip" data-placement="bottom" title="Essen kann nicht bestellt werden, da die Frist schon abgelaufen ist.">
                                                                <a class="btn btn-success btn-circle custom1 disabled" href="#">
                                                                    <span class="fa fa-plus" aria-hidden="true"></span>
                                                                </a>
                                                            </span>';
			      						         } else if(!$dauerbesteller || ($dauerbesteller && $abbestellung[$weekday] && ($now < $abmeldefrist || ($now > $abmeldefrist && $hatRechte)) && $time[$weekday] >= $essenstart && $time[$weekday] <= $essenende)){
			      						             $str = '<span class="d-inline-block" data-toggle="tooltip" data-placement="bottom" title="">
                                                                <a class="btn btn-success btn-circle custom1" href="#">
                                                                    <span class="fa fa-plus" aria-hidden="true" title="bestellen"></span>
                                                                </a>
                                                            </span>';
			      						         } else {
			      						             $str = '<span class="d-inline-block" data-toggle="tooltip" data-placement="bottom" title="Essen kann nicht bestellt werden, da bei Dauerbestellern nur Dauerbestellungen geändert werden können.">
                                                                <a class="btn btn-success btn-circle custom1 disabled" href="#">
                                                                    <span class="fa fa-plus" aria-hidden="true"></span>
                                                                </a>
                                                            </span>';
			      						         }
                                            echo $str;
			      							}
			      							?>
			      						</div>
			      						</div>
			      						<script>var result<?php echo $day.$i; ?> = <?php echo $bestellung; ?>;</script>
			      						<script>var abmeldefrist<?php echo date($variableTimeFormat, $time[$weekday]); ?> = <?php echo $hatRechte ? PHP_INT_MAX : $abmeldefrist; ?>;</script>
			      						<script>var abbestellung<?php echo date($variableTimeFormat, $time[$weekday]); ?> = <?php echo $abbestellung[$weekday]; ?>;</script>
			      						<script>var essenstart<?php echo date($variableTimeFormat, $time[$weekday]); ?> = "<?php echo $essenstart*1000; ?>";</script>
										<script>var essenende<?php echo date($variableTimeFormat, $time[$weekday]); ?> = "<?php echo ($essenende+86400)*1000; ?>";</script>
										<script>var essenkat<?php echo date($variableTimeFormat, $time[$weekday]); ?> = <?php echo $essenkategorie; ?>;</script>
			      						</div>
 			      					<?php 
			      					}?>
<?php
}
$database->closeConnection();
?>
</div>
<script>var admin = "<?php echo $hatRechte;?>";</script>
<script>var fristTag = "<?php echo $abmeldefristTag;?>";</script>
<script>var fristZeit = "<?php echo $abmeldefristZeit;?>";</script>
<script>var weeksOfThisMonth = "<?php echo $weeksPerMonth; ?>";</script>
<script>var kindId = "<?php echo $rowKind['id']; ?>";</script>