<?php
require_once 'vendor/autoload.php'; // Certifique-se de que o caminho para o autoload.php esteja correto

// Crie um objeto TCPDF
$pdf = new TCPDF();

// Adicione uma página ao PDF
$pdf->AddPage();

// Defina a fonte e o tamanho do texto
$pdf->SetFont('Helvetica', 'B', 16);

// Adicione um título ao PDF
$pdf->Cell(0, 10, 'Meu Primeiro PDF', 0, 1, 'C');

// Defina a fonte e o tamanho do texto para o conteúdo
$pdf->SetFont('Helvetica', '', 12);

// Adicione algum conteúdo ao PDF
$pdf->Cell(0, 10, 'Este é o conteúdo do meu PDF.', 0, 1);

// Saída do PDF (mostrar na tela ou salvar em um arquivo)
$pdf->Output('meu_pdf.pdf', 'D'); // 'D' significa que o PDF será baixado pelo navegador

// Lembre-se de que você pode personalizar ainda mais o conteúdo do PDF de acordo com suas necessidades.
?>
