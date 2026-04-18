<?php
// Database configuration
$host = 'smdelfin-carbon-db-server.mysql.database.azure.com';
$db   = 'carbon_tracker_db';
$user = 'smdelfin';
$pass = 'cmsc-207';
$port = 3306;

// Path to the SSL certificate you downloaded
$ssl_cert = __DIR__ . "/DigiCertGlobalRootG2.crt.pem";

// DSN (Data Source Name)
$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::MYSQL_ATTR_SSL_CA       => $ssl_cert, // Required for Azure SSL
    PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false, // Set to false if issues occur
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
     // echo "Connected successfully to Azure MySQL!"; 
} catch (\PDOException $e) {
     throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
?>