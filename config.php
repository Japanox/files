<?php
// Configurações do banco de dados
$servername = "localhost";
$username = "xxxxxx";
$password = "xxxxxx";
$dbname = "xxxxxx";

// Configurações do PHPMailer
$smtpHost = 'smtp.gmail.com';
$smtpUsername = 'xxxxxx';
$smtpPassword = 'xxxxxxx'; // Sua senha do Gmail
$smtpPort = 587; // Porta SMTP do Gmail
$smtpEncryption = 'tls'; // Use 'tls' ou 'ssl'

// Crie uma conexão com o banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifique a conexão
if ($conn->connect_error) {
    die("Erro na conexão com o banco de dados: " . $conn->connect_error);
}
?>
