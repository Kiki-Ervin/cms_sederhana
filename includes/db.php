<?php
require_once __DIR__ . '/../config/database.php';

function getDBConnection() {
    global $conn;
    return $conn;
}
?> 