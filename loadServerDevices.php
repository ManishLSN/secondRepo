<?php
//include("../config/config.php");

$connect = mysql_connect('localhost','root','uno');
if(!$connect){	die('Could not connect to MySQL: ' . mysql_error()); }
$cid =mysql_select_db('hubcp',$connect);

$query = "SELECT s.id as serverid, s.serverName, s.createdBy as serverCreatedBy, d.name as deviceName, d.createdOn as deviceCreatedDate, d.createdBy as deviceCreatedBy from servers as s join hub.devices as d on d.serverId = s.id where s.id not in (1,6,35,64,84,169,213,246,247,287,289,288,290,244) and d.isActive = 1";
$rs = mysql_query($query, $connect);

function getClientName($connect,$clientId){
	if($clientId){
		$query1 = "SELECT ifnull(CONCAT(c.firstName, ' ', c.lastName), '--') as clientName from clients as c where c.id = $clientId";
		$clientName = mysql_query($query1, $connect);
		if($clientName){
			$clientNameRow = mysql_fetch_assoc($clientName);
			return $clientNameRow['clientName'];
		}else{
			return "--";
		}
	}
}

function getUserName($connect,$userId){
	if($userId){
		$userName = 0;
		$query2 = "SELECT ifnull(CONCAT(u.firstName, ' ', u.lastName), '--') as userName from hub.users as u where u.id = $userId";
		$userName = mysql_query($query2, $connect);
		if($userName){
			$userRow = mysql_fetch_assoc($userName);
			return $userRow['userName'];
		}
	}
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Server Report</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css">
    <script type="text/javascript" src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
	<style> .container {
				width: 1363px;
				margin: 0 auto;
				margin-top: 20px;
			}
	</style>
</head>
<body>
<div class="container">
<table id="example" class="display" width="100%" cellspacing="0">
	<thead>
		<tr>
			<th>Server Name</th>
			<th>Server Created by</th>
			<th>Device Name</th>
			<th>Device Created date</th>
			<th>Device Created by</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<th>Server Name</th>
			<th>Server Created by</th>
			<th>Device Name</th>
			<th>Device Created date</th>
			<th>Device Created by</th>
		</tr>
	</tfoot>
	<tbody>
	
	<?php
	while($row = mysql_fetch_assoc($rs)){
		extract($row);
		echo "<tr>";
			echo "<td data-id=\"$serverid\">$serverName</td>";
			echo "<td>"; echo getClientName($connect, $serverCreatedBy); echo "</td>";
			echo "<td>$deviceName</td>";
			echo "<td>$deviceCreatedDate</td>";
			echo "<td>"; echo getUserName($connect, $deviceCreatedBy); echo "</td>";
		echo "</tr>";
	}
	?>
	</tbody>
</table>
</div>
	<script>
      $(document).ready(function() {
			$('#example').DataTable({"iDisplayLength": 50});
		} );
    </script>
  </body>
</html>