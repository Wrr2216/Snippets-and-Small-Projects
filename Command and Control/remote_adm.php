<?php

include 'control.php';

$mac = $_GET['mac'];

# check if id or mac provided
if($mac == null){
    echo 'No authorized MAC provided';
    exit;
}

// Handle create device form
if ($_GET['action'] == 'create') {
    // Make sure device isnt already present with the same ID
    if(!deviceExists($mac)){
        echo 'Creating device';
        createDevice($mac, $_GET['agency']);
        header('Location: remote_adm.php');
    }
}

if($_GET['action'] == 'geturl'){
    $result = $db_con->query("SELECT url FROM devices WHERE mac = '$mac'");
    $row = $result->fetch_assoc();

    #if results are empty, echo unknown
    if($row['url'] == null){
        echo 'unknown';
    }else{
        echo $row['url'];
    }
}

if($_GET['action'] == 'createcommand'){
    $command = $_GET['command'];
    $db_con->query("UPDATE devices SET command = '$command' WHERE mac = '$mac'");
    header('Location: remote_adm.php');
}

if($_GET['action'] == 'showall'){
    if($result = mysqli_query($db_con, "SELECT * from devices"))
{
	$rows = array();
	while($r = mysqli_fetch_assoc($result)){
		$rows[] = $r;
	}

	header("Content-Type: application/json");
	echo json_encode($rows);
}
}


mysqli_close($db_con);
?>