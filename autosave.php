<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Coleta os dados do formulário
    $nome = $_POST["nome"];
    $numero = $_POST["numero"];
    
    // Verifica se há dados armazenados localmente no localStorage
    if (isset($_POST["offlineData"])) {
        // Os dados foram enviados localmente, recupere-os
        $offlineData = json_decode($_POST["offlineData"], true);
        $nome = $offlineData["nome"];
        $numero = $offlineData["numero"];
    }
    
    // Faça o processamento dos dados ou salve-os no banco de dados
    // Substitua esta parte pelo código que você precisa para salvar os dados
    
    // Se desejar, você pode responder com uma mensagem de sucesso ou outro dado
    echo "Dados salvos com sucesso!";
} else {
    // Acesso direto a este arquivo não é permitido
    echo "Acesso negado.";
}
?>
