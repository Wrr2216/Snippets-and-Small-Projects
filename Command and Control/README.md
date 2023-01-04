These scripts allow communication from a remote device to the server which is hosting the control.php and control_admin.php files. 
** Control.php and control_admin.php both communicate to a MySQL server that has the following schema:

```
CREATE TABLE `devices` (
  `id` int NOT NULL,
  `command` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `status` int DEFAULT '0',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `agency` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `mac` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `last_heartbeat` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
```


The bash script should be setup on a linux device and a schedule in crontab set accordingly. I have this set for 5 minute increments.

Feel free to modify to your needs. 
