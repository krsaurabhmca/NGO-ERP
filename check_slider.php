<?php
require 'config/config.php';
require 'app/core/Database.php';
$db = \App\Core\Database::getInstance();
$stmt = $db->query("SELECT * FROM cms_media WHERE category='slider'");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
