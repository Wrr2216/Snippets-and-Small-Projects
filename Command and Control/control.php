<?php

// Handle the incoming request for action or heartbeat from bash scripts on client devices
// This is the only file that should be called from the client devices

$action = $_GET['action'];
$password = $_GET['password'];
$id = $_GET['id'];
$ip = $_GET['ip'];
$mac = $_GET['mac'];



if ($password != '******') {
    die('Invalid password');
}

// Database configuration
$db_config = array(
    'host' => '******',
    'user' => '******',
    'password' => '******',
    'database' => '******'
);

// Connect to the database
$db_con = new mysqli($db_config['host'], $db_config['user'], $db_config['password'], $db_config['database']);

// Check sql connection
if($db_con->connect_errno > 0){
    die('Unable to connect to database [' . $db_con->connect_error . ']');
}

// Update the heartbeat for the device
if ($action == 'heartbeat') {
    updateHeartbeat($mac);
}

// Check for a command for the device
if($action == 'check'){
    // Check to ensure device exists
    if(!deviceExists($mac)){
        createDevice($mac, 'Unknown');
    }

    $command = checkForCommand($mac);
    if ($command) {
        echo $command;
    } else {
        echo 'none';
    }
}

function deviceExists($mac){
    global $db_con;
    $result = $db_con->query("SELECT * FROM devices WHERE mac = '$mac'");

    if($result->num_rows > 0){
        return true;
    } else {
        return false;
    }
}

function createDevice($mac, $agency) {
    global $db_con;
    $db_con->query("INSERT INTO devices (mac, agency, timestamp, last_heartbeat) VALUES ('$mac', '$agency', NOW(), NOW())");
}

function updateHeartbeat($mac) {
    global $db_con;
    $result = $db_con->query("UPDATE devices SET last_heartbeat = NOW() WHERE mac = '$mac'");
    echo $result;
}

function checkForCommand($mac) {
    global $db_con;
    $result = $db_con->query("SELECT * FROM devices WHERE mac = '$mac' AND status = '0'");

    if($result->num_rows > 0){
        $row = $result->fetch_assoc();
        $command = $row['command'];
        $db_con->query("UPDATE devices SET status = '1' WHERE mac = '$mac'");
        $db_con->query("UPDATE devices SET command = '' WHERE mac = '$mac'");
        return $command;
    } else {
        return false;
    }
}

?>