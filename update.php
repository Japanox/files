<?php
$servername = "xxxxxxx";
$username = "xxxxxxx";
$password = "xxxxxx";
$dbname = "xxxxxxxxx";

// Cria a conexão com o banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Erro na conexão com o banco de dados: " . $conn->connect_error);
}

// ID do registro que você deseja atualizar
$id = 1; // Substitua 1 pelo ID do registro que você deseja atualizar

// Novos valores para os campos que você deseja atualizar
$novoNome = "NovoNome";
$novoNumero = "NovoNumero";

// Query SQL para a atualização
$sql = "UPDATE contatos SET nome='$novoNome', numero='$novoNumero' WHERE id=$id";

if ($conn->query($sql) === TRUE) {
    echo "Registro atualizado com sucesso!";
} else {
    echo "Erro na atualização: " . $conn->error;
}

$conn->close();
?>
