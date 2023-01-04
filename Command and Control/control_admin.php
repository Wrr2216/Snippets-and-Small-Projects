<?php

// Script created by Logan Miller on 1/3/2023

include 'control.php';

$id = intval($_GET['id']);

// Handle create device form
if ($_GET['action'] == 'create') {
    // Make sure device isnt already present with the same ID
    if(!deviceExists($id)){
        echo 'Creating device';
        createDevice($id, $_GET['agency']);
        header('Location: admin.php?password=******');
    }
}

if($_GET['action'] == 'createCommand'){
    $command = $_GET['command'];
    $db_con->query("UPDATE devices SET command = '$command' WHERE id = '$id'");
    header('Location: admin.php?password=******');
}

?>

<html>

<head>
    <title>Admin</title>
</head>

<!-- Display the admin page -->
<?php if ($_GET['password'] == '******') { ?>
    <body>
        <h1>Admin</h1>

        <!-- Create device option -->
        <form action="admin.php" method="get">
            <input type="hidden" name="password" value="******">
            <input type="hidden" name="action" value="create">
            <input type="text" name="id" placeholder="ID">
            <input type="text" name="agency" placeholder="Agency">
            <input type="submit" value="Create Device">
        </form>

        <!-- Create a command -->
        <form action="admin.php" method="get">
            <input type="hidden" name="password" value="******">
            <input type="hidden" name="action" value="createCommand">
            <input type="text" name="id" placeholder="ID">
            <input type="text" name="command" placeholder="Command">
            <input type="submit" value="Create Command">
        </form>

        <h2>Devices</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Agency</th>
                <th>Status</th>
                <th>Last Heartbeat</th>
                <th>MAC</th>
            </tr>
            <?php
            $query = "SELECT * FROM devices";
            $result = mysqli_query($db_con, $query);
            while ($row = mysqli_fetch_array($result)) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['agency'] . "</td>";
                echo "<td>" . $row['status'] . "</td>";
                echo "<td>" . $row['mac'] . "</td>";
                echo "<td>" . $row['last_heartbeat'] . "</td>";
                echo "</tr>";
            }
            ?>
        </table>
        <h2>Commands</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Command</th>
                <th>Time</th>
            </tr>
            <?php
            $query = "SELECT * FROM devices";
            $result = mysqli_query($db_con, $query);

            while ($row = mysqli_fetch_array($result)) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['command'] . "</td>";
                echo "<td>" . $row['last_heartbeat'] . "</td>";
                echo "</tr>";
            }
            ?>
        </table>
    </body>

</html>
<? } ?>
