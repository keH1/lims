<?php
$restore_file  = __DIR__ . "/db.gz";
$server_name   = "localhost";
$username      = "root";
$password      = "root";
$database_name = "test_world_copy";

$cmd = "mysql -h {$server_name} -u {$username} -p{$password} {$database_name} < $restore_file";
exec($cmd);