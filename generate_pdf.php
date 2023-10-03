<?php
require_once('tc-lib-pdf/src/Tcpdf.php'); // Caminho correto para o arquivo tcpdf.php

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Coleta os dados do formulário
    $nome = $_POST['nome'];
    $numero = $_POST['numero'];

    // Crie um objeto TCPDF
    $pdf = new TCPDF();

    // Adicione uma página ao PDF
    $pdf->AddPage();

    // Adicione seu conteúdo ao PDF
    $pdf->SetFont('Helvetica', 'B', 16);
    $pdf->Cell(0, 10, 'Formulário de Contato', 0, 1, 'C');
    $pdf->SetFont('Helvetica', '', 12);
    $pdf->Cell(0, 10, "Nome: $nome", 0, 1);
    $pdf->Cell(0, 10, "Número: $numero", 0, 1);

    // Diretório onde você deseja salvar o PDF
    $directory = "relatorios/$numero"; // Substitua pela lógica desejada

    // Verifique se o diretório existe, senão, crie-o
    if (!is_dir($directory)) {
        mkdir($directory, 0777, true);
    }

    // Nome do arquivo PDF
    $pdfFileName = "$directory/relatorio.pdf";

    // Salve o PDF no servidor
    $pdf->Output($pdfFileName, 'F');

    // Redirecione de volta para a página do formulário
    header('Location: index.html'); // Substitua pelo nome da sua página de formulário
} else {
    // Acesso direto a este arquivo não é permitido
    echo "Acesso negado.";
}
?>

