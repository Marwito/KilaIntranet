<?php
require_once('../login/session.php');
require_once('../utilities/constants.php');
$session = Session::getInstance();
if($session->checkSessionVariables('username', 'usergroup')){
    require_once('../benutzerverwaltung/benutzer/benutzer_class.php');
    $benutzer = new Benutzer();
    if (!$benutzer->isAdmin($session->usergroup)) {
        header('Location: ' . Constants::getBaseURL());
    } else {
        if(isset($_GET['id'])){
            require_once('../utilities/db_connection.php');
            $database = new DatabaseConnection();
            $conn = $database->getConn();
            $sql = "SELECT * FROM keis2_but WHERE id =
                    ".$conn->real_escape_string($_GET['id'])."";
            
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
            }
            
            $database->closeConnection();
        }else{
            echo 'BUT nicht gefunden';
        }
    }
}else {
    header('Location: ' . Constants::getBaseURL() . '/index.php?no_login=set');
}
?>
<!doctype html>
<html lang="de">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/css/tempusdominus-bootstrap-4.min.css">
<link rel="stylesheet" href="../../../css/datatables.min.css">
<link rel="stylesheet" href="../../../css/styles.css">
<link rel="stylesheet" href="../../../css/layout.css">
<title>BUT</title>
</head>
<body>
    <?php 
        if(!@include_once('../utilities/navigation.php')) {
            require_once('../../utilities/navigation.php');
        }
    ?>
<nav class="navbar navbar-expand-lg navbar-light nav_backgroundImage" id="myNavbar">
	<div class="container-fluid">
		<div class="navbar-header">
			<a class="navbar-brand" href="#"><i class="fas fa-align-justify" onclick="openNav()"></i></a>
		</div>
        <?php 
            if(!@include_once('../utilities/header_navigation.php')) {
                require_once('../../utilities/header_navigation.php');
            }
        ?>  
	</div>
</nav>
<div id="main" style="padding:0; margin:0;">
	<div class="container-fluid" style="background-color:#f6f5f5;">
		<div class="row">
			<div class="col-lg-12">
				<div class="col-sm-11 offset-sm-1" style="padding-top:70px; padding-bottom:70px;">
					<h2><b>BUT</b></h2>
					<p>einen Bescheid aktualisieren</p>
				</div>
			</div>
		</div>
	</div>
	<div class="container-fluid" style="padding-top:60px;">
        <div class="row">
        	<div class="col-sm-10 offset-sm-1">
        		<form name="form" method="post" action="" novalidate>
        			<input type="hidden" id="butID" name="butID" value="<?php echo $row['id']; ?>">
                    <div class="messages"></div>
                          
                    <ul id="but_progressstep">
                      <li class="active" id="li-step-1">Kind ausw&auml;hlen</li>
                      <li id="li-but_step-2">Aktenzeichen eingeben</li>
                      <li id="li-but_step-3">Ansprechpartner ausw&auml;hlen</li>
                      <li id="li-but_step-4">Terminierung eingeben</li>
                      <li id="li-but_step-5">Anteilsart ausw&auml;hlen</li>
                      <li id="li-but_step-6">Anteilsbetrag eingeben</li>
                      <li id="li-but_step-7">Debitorennummer eingeben</li>
                      <li id="li-but_step-8">Abschluss</li>
                    </ul>        
                    
                    <fieldset id="but_step-1" class="">
                      <div class="col-xs-12">
                        <div class="col-md-12">
                          <div class="form-group">
        					<div class="mt-3 mb-2">
        		  				<label><strong>Kind ausw&auml;hlen</strong></label>
            			  	</div>
            			  	<div class="row">
            			  		<div class="col-sm-12 table-responsive">
            			  			<input type="hidden" id="kindID" name="kindID" value="<?php echo $row['kind']; ?>">
            						<table id="table_but_kind" class="table table-bordered dt-responsive hover" style="width:100%">
                				    	<thead>
                						    <tr>
                						    	<th></th>
                							    <th scope="col">Vorname</th>
                							    <th scope="col">Name</th>
                							    <th scope="col">ID</th>
                					    	</tr>
                				  		</thead>
                				  		<tbody>	
                				  		<?php
                				  		
                    				  		$output ='';
                    				  		require_once('../utilities/db_connection.php');
                    				  		$database = new DatabaseConnection();
                    				  		$conn = $database->getConn();
                    				  		$sql = "SELECT id, name, vorname FROM keis2_kind";
                    				  		$result = $conn->query($sql);
                    				  		$output .= '';
                                                				  		
                    				  		if ($result->num_rows > 0) {
                    				  		    while($row_edit = $result->fetch_assoc()) {
                    				  		        $output .= '
                                                        <tr id="'.$row_edit['id'].'">
                                                            <td></td>
                                                            <td>'.$row_edit['vorname'].'</td>
                                                            <td>'.$row_edit['name'].'</td>
                                    				        <td>'.$row_edit['id'].'</td>
                                                        </tr>
                                                ';
                                                }
                    				  		}
                    				  		$database->closeConnection();
                    				  		
                    				  		echo $output;
                				  		?>

                				  		</tbody>
            				  		</table>
            				  		<div class="mt-3 mb-2" id="error_kindId">Bitte wählen Sie ein Kind aus!</div>
        				  		</div>
            				</div>
                          </div>
                        </div>
                        <div class="col-md-12">
							<button class="btn btn-outline-success nextBtn btn-sm pull-right next" type="button" id="btn_but_step_1_next">vor</button>
                        </div>
                      </div>
                    </fieldset>
            
                    <fieldset id="but_step-2" class="">
                      <div class="col-xs-12">
                        <div class="col-md-12">
                          <div class="form-group">
                            <label class="control-label" for="aktenzeichen" style="padding-bottom:20px;">Aktenzeichen eingeben</label>
                            <input class="form-control" id="aktenzeichen" name="aktenzeichen" required value="<?php echo $row['aktenzeichen']; ?>"/>
                          </div>
                        </div>
                        <div class="col-md-12">
                        	<button class="btn btn-outline-success prevBtn btn-sm pull-left prev" type="button" id="btn_but_step_2_prev">zur&uuml;ck</button>
							<button class="btn btn-outline-success nextBtn btn-sm pull-right next" type="button" id="btn_but_step_2_next">vor</button>
                        </div>
                      </div>
                    </fieldset>

                    <fieldset id="but_step-3" class="">
                      <div class="col-xs-12">
                        <div class="col-md-12">
                          <div class="form-group">
							<div class="mt-3 mb-2">
        		  				<label><strong>Ansprechpartner ausw&auml;hlen</strong></label>
            			  	</div>
            			  	<div class="row">
            			  		<div class="col-sm-12 table-responsive">
            			  			<input type="hidden" id="ansprechpartnerID" name="ansprechpartnerID" value="<?php echo $row['ansprechpartner_id']; ?>">
            						<table id="table_but_ansprechpartner" class="table table-bordered dt-responsive hover" style="width:100%">
                				    	<thead>
                						    <tr>
                						    	<th></th>
                							    <th scope="col">Vorname</th>
                							    <th scope="col">Name</th>
                							    <th scope="col">ID</th>
                					    	</tr>
                				  		</thead>
                				  		<tbody>	
                				  		<?php
                				  		
                    				  		$output ='';
                    				  		require_once('../utilities/db_connection.php');
                    				  		$database = new DatabaseConnection();
                    				  		$conn = $database->getConn();
                    				  		$sql = "SELECT id, name, vorname FROM keis2_ansprechpartner";
                    				  		$result = $conn->query($sql);
                    				  		$output .= '';
                                                				  		
                    				  		if ($result->num_rows > 0) {
                    				  		    while($row_edit = $result->fetch_assoc()) {
                    				  		        $output .= '
                                                        <tr id="'.$row_edit['id'].'">
                                                            <td></td>
                                                            <td>'.$row_edit['vorname'].'</td>
                                                            <td>'.$row_edit['name'].'</td>
                                    				        <td>'.$row_edit['id'].'</td>
                                                        </tr>
                                                ';
                                                }
                    				  		}
                    				  		$database->closeConnection();
                    				  		
                    				  		echo $output;
                				  		?>

                				  		</tbody>
            				  		</table>
            				  		<div class="mt-3 mb-2" id="error_ansprechpartnerId">Bitte wählen Sie einen Ansprechpartner aus!</div>
        				  		</div>
            				</div>
                          </div>          
                        </div>
                        <div class="col-md-12">
                        	<button class="btn btn-outline-success prevBtn btn-sm pull-left prev" type="button" id="btn_but_step_3_prev">zur&uuml;ck</button>
							<button class="btn btn-outline-success nextBtn btn-sm pull-right next" type="button" id="btn_but_step_3_next">vor</button>
                        </div>
                      </div>
                    </fieldset>            
            
                    <fieldset id="but_step-4" class="">
                      <div class="col-xs-12">
                        <div class="col-md-12">
					  	  <div class="mt-3 mb-2">
    		  				<label><strong>Terminierung ausw&auml;hlen</strong></label>
        			  	  </div>
                          <div class="form-group custom-font">
                            <label class="control-label">Bescheid von</label>
							<input style="font-family:verdana;font-size:16px" type="text" class="form-control" data-toggle="datetimepicker" data-target="#datetimepicker_bescheid_von" 
							name="datetimepicker_bescheid_von" id="datetimepicker_bescheid_von" value="<?php echo DateTime::createFromFormat('Y-m-d', $row['von'])->format('d.m.Y'); ?>" required>
                          </div>
                          <div class="form-group custom-font">
                            <label class="control-label">Bescheid bis</label>
							<input style="font-family:verdana;font-size:16px" type="text" class="form-control" data-toggle="datetimepicker" data-target="#datetimepicker_bescheid_bis" 
							name="datetimepicker_bescheid_bis" id="datetimepicker_bescheid_bis" value="<?php echo DateTime::createFromFormat('Y-m-d', $row['bis'])->format('d.m.Y'); ?>" required>
                          </div>
                        </div>
                        <div class="col-md-12">
                        	<button class="btn btn-outline-success prevBtn btn-sm pull-left prev" type="button" id="btn_but_step_4_prev">zur&uuml;ck</button>
							<button class="btn btn-outline-success nextBtn btn-sm pull-right next" type="button" id="btn_but_step_4_next">vor</button>
                        </div>
                      </div>
                    </fieldset>
                    
                    <fieldset id="but_step-5" class="">
                      <div class="col-xs-12">
                        <div class="col-md-12" style="padding-bottom:10px;">
					  	    <div class="mt-3 mb-2">
    		  				  <label><strong>Anteilsart ausw&auml;hlen</strong></label>
        			  	    </div>              
    			  	    	<div class="form-group" style="padding-bottom:-5px;">
                                <select class="form-control" id="anteilsart" name="anteilsart" required>
                                  <option value=""/>
                                  <?php
                                  if ($row['eigenanteil_proEssen'] == 1) {
                                      echo "<option value='1' selected>pro Essen</option>";
                                  } else {
                                      echo "<option value='1'>pro Essen</option>";
                                  }
                                  
                                  if ($row['eigenanteil_proMonat'] == 1) {
                                      echo "<option value='2' selected>pro Monat</option>";
                                  } else {
                                      echo "<option value='2'>pro Monat</option>";
                                  }
                                  ?>
                                </select>
                        	</div>
                        </div>
                        <div class="col-md-12">
                        	<button class="btn btn-outline-success prevBtn btn-sm pull-left prev" type="button" id="btn_but_step_5_prev">zur&uuml;ck</button>
							<button class="btn btn-outline-success nextBtn btn-sm pull-right next" type="button" id="btn_but_step_5_next">vor</button>
                        </div>
                      </div>
                    </fieldset>                     
            
                    <fieldset id="but_step-6" class="">
                      <div class="col-xs-12">
                        <div class="col-md-12">
					  	  <div class="mt-3 mb-2">
    		  				<label><strong>Anteilsbetrag eingeben</strong></label>
        			  	  </div>                          
                          <div class="form-group">
                            <input class="form-control" id="anteilsbetrag" name="anteilsbetrag" required value="<?php echo $row['eigenanteil']; ?>"/>
                          </div>            
                        </div>
                        <div class="col-md-12">
                        	<button class="btn btn-outline-success prevBtn btn-sm pull-left prev" type="button" id="btn_but_step_6_prev">zur&uuml;ck</button>
							<button class="btn btn-outline-success nextBtn btn-sm pull-right next" type="button" id="btn_but_step_6_next">vor</button>
                        </div>                        
                      </div>
                    </fieldset>            
            
                    <fieldset id="but_step-7" class="">
                      <div class="col-xs-12">
                        <div class="col-md-12">
					  	  <div class="mt-3 mb-2">
    		  				<label><strong>Debitorennummer eingeben</strong></label>
        			  	  </div>                          
                          <div class="form-group">
                            <input class="form-control" id="debitorennummer" name="debitorennummer" required value="<?php echo $row['debitorennummer']; ?>"/>
                          </div>            
                        </div>
                        <div class="col-md-12">
                        	<button class="btn btn-outline-success prevBtn btn-sm pull-left prev" type="button" id="btn_but_step_7_prev">zur&uuml;ck</button>
                        	<button class="btn btn-outline-success nextBtn btn-sm pull-right next" type="button" id="btn_but_step_6_next">vor</button>
                        </div>                        
                      </div>
                    </fieldset>

                    <fieldset id="but_step-8" class="">
                      <div class="col-xs-12">
                        <div class="col-md-12">
					  	  <p>Der BUT wurde angelegt. Zum Abschluss klicken Sie bitte auf speichern.</p>                               
                        </div>
                        <div class="col-md-12">
                        	<button class="btn btn-outline-success prevBtn btn-sm pull-left prev" type="button" id="btn_but_step_7_prev">zur&uuml;ck</button>
                        </div>                        
                      </div>
                    </fieldset>                    
                    <div class="text-right my-5">							  	
    				  	<a href="but.php" class="btn btn-primary btn-custom">Zurück</a>
    				  	<button id="submit" name="submit" class="btn btn-primary btn-custom">Speichern</button>
    			  	</div>					
    			</form>
			</div>
    	</div>
  	</div>
  	<div class="navbar_bottom">
        <a href="#" class="navbar_bottom_link_left">Impressum</a>
        <a href="#" class="navbar_bottom_link_right">Datenschutz</a>
    </div>   
</div>

<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<!-- <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment-with-locales.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/js/tempusdominus-bootstrap-4.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.18/r-2.2.2/datatables.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/additional-methods.min.js"></script>
<script src="../../../js/but/edit_but.js"></script>
<script src="../../../js/sidenav.js"></script>
</body>
</html>