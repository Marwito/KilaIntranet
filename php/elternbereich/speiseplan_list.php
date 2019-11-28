<!doctype html>
<html lang="de">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
<link rel="stylesheet" href="../../css/datatables.min.css">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
<link rel="stylesheet" href="../../css/styles.css">
<link rel="stylesheet" href="../../css/layout.css">
<title>Elternbereich</title>
</head>
<body>
    <?php 
        require_once('../utilities/navigation.php');
    ?>
<nav class="navbar navbar-expand-lg navbar-light nav_backgroundImage" id="myNavbar">
	<div class="container-fluid">
		<div class="navbar-header">
			<a class="navbar-brand" href="#"><i class="fas fa-align-justify" onclick="openNav()"></i></a>
		</div>
        <?php 
            require_once('../utilities/header_navigation.php');
        ?>
	</div>
</nav>
<div id="main" style="padding:0; margin:0;">
	<div class="container-fluid" style="background-color:#f6f5f5;">
		<div class="row">
			<div class="col-lg-12">
				<div class="col-sm-11 offset-sm-1" style="padding-top:70px; padding-bottom:70px;">
					<h2><b>Elternbereich</b></h2>
				</div>
			</div>
		</div>
	</div>
	<div class="container-fluid" style="padding-top:60px;">
        <div class="row">
        	<div class="col-sm-10 offset-sm-1">
        		<div class="card">
        	  		<div class="card-body">
        				<div class="tab-content" id="myTabContent">
        					<div class="tab-pane fade show active" id="speiseplan" role="tabpanel" aria-labelledby="speiseplan-tab">
                                
<?php
require_once('../utilities/db_connection.php');
$database = new DatabaseConnection();
$conn = $database->getConn();
$sql = "SELECT * FROM keis2_speiseplan";    //@TODO nur speiseplan der eigenen einrichtung sehen, benutzer braucht einrichtungs attribut
$result = $conn->query($sql);
?>
						<div class="row mt-3">		
						    <div class="col-sm-12">
								<table id="tab1" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
							    	<thead>
									    <tr>
									    	<th></th>
									    	<th scope="col" style="text-align:center">Datum</th>
										    <th scope="col">Hauptgericht</th>
										    <th scope="col">Beilage</th>
										    <th scope="col">Nachspeise</th>
								    	</tr>
							  		</thead>
							  		<tbody>			
<?php
if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
?>
										<tr id="<?php echo $row['id']; ?>">
											<td></td>
									        <td style="text-align:center"> <?php echo $row['datum']; ?> </td>
									        <td> <?php echo $row['hauptgericht']; ?> </td>
									        <td> <?php echo $row['beilage']; ?> </td>
									        <td> <?php echo $row['nachspeise']; ?> </td>
			      						</tr>
<?php 
	}
}
$database->closeConnection();
?>	
									</tbody>
								</table>
							</div>
						</div>
                                
    						</div>
    					</div>
    				</div>
    			</div>		
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
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.18/r-2.2.2/datatables.min.js"></script>
<script src="../../js/sidenav.js"></script>
<script src="../../js/elternbereich/speiseplan_list.js"></script>
</body>
</html>