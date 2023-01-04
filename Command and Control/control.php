<?php

// Script created by Logan Miller on 1/3/2023

// Handle the incoming request for action or heartbeat from bash scripts on client devices
// This is the only file that should be called from the client devices

$action = $_GET['action'];
$password = $_GET['password'];
$id = $_GET['id'];



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
    updateHeartbeat($id);
}

// Check for a command for the device
if($action == 'check'){
    $command = checkForCommand($id);
    if ($command) {
        echo $command;
    } else {
        echo 'none';
    }
}

function deviceExists($id){
    global $db_con;
    $result = $db_con->query("SELECT * FROM devices WHERE id = '$id'");

    if($result->num_rows > 0){
        return true;
    } else {
        return false;
    }
}

function createDevice($id, $agency) {
    global $db_con;
    $db_con->query("INSERT INTO devices (id, agency, timestamp, last_heartbeat) VALUES ('$id', '$agency', NOW(), NOW())");
}

function updateHeartbeat($id) {
    global $db_con;
    $result = $db_con->query("UPDATE devices SET last_heartbeat = NOW() WHERE id = '$id'");
    echo $result;
}

function checkForCommand($id) {
    global $db_con;
    $result = $db_con->query("SELECT * FROM devices WHERE id = '$id' AND status = '0'");

    if($result->num_rows > 0){
        $row = $result->fetch_assoc();
        $command = $row['command'];
        $db_con->query("UPDATE devices SET status = '1' WHERE id = '$id'");
        $db_con->query("UPDATE devices SET command = '' WHERE id = '$id'");
        return $command;
    } else {
        return false;
    }
}

?>
