<?php

    define("DBHOST", "localhost");
    define("DBUSER", "root");
    define("DBPASS", "");
    define("DBNAME", "php_crud");

    $conn = new mysqli(DBHOST, DBUSER, DBPASS, DBNAME);

    if(!$conn) {
        die("Connection Failed: " . $conn->connect_error());
    }

?>