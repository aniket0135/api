<?php
require_once('Config/database.php');
$sql = "CREATE TABLE IF NOT EXISTS `users`(
id INT(11) AUTO_INCREMENT PRIMARY KEY,
firstname VARCHAR(256) NOT NULL,
lastname VARCHAR(256) NOT NULL,
email VARCHAR(256) NOT NULL,
password VARCHAR(2048) NOT NULL,
created datetime NOT NULL,
modified timestamp NOT NULL
)";

if (mysqli_query(Database::$conn, $sql)) {
    echo "Table users created successfully";
} else {
    //echo "Error creating table: " . mysqli_error($conn);
}
?>
