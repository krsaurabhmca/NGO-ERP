<?php
require_once 'config/database.php';
$pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
$stmt = $pdo->query("SELECT uuid FROM job_applications WHERE status='hired' LIMIT 1");
$uuid = $stmt->fetchColumn();

// Now get the HTML output of offer_letter_pdf.php
session_start();
$_SESSION['user_id'] = 1;
$_GET['uuid'] = $uuid;

ob_start();
include('public/offer_letter_pdf.php');
$html = ob_get_clean();

file_put_contents('output.html', $html);
echo "HTML written to output.html for UUID: $uuid\n";
