<?php
    $db_server = "localhost";
    $db_username = "root";
    $db_password = "";
    $db_database = "EP-08";

    $db_connection = mysqli_connect($db_server, $db_username, $db_password, $db_database) or die(mysqli_connect_error());
    mysqli_set_charset($db_connection, "utf8");
?>