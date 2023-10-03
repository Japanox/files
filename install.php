<?php
$servername = "xxxxxxx";
$username = "xxxxx";
$password = "xxxxxxx";
$dbname = "xxxxxxx";

// Cria a conexão com o banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Erro na conexão com o banco de dados: " . $conn->connect_error);
}

// SQL para criar a tabela contatos
$sql = "CREATE TABLE contatos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    numero VARCHAR(20) NOT NULL
)";

if ($conn->query($sql) === TRUE) {
    echo "Tabela 'contatos' criada com sucesso!";
} else {
    echo "Erro ao criar a tabela: " . $conn->error;
}

$conn->close();
?>
