<?php
require __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

try {
    // Verifica se o pedido é uma solicitação de sincronização (comandos pendentes)
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['commands'])) {
        $commands = json_decode($_POST['commands'], true);

        // Processa e armazena os comandos no banco de dados
        foreach ($commands as $command) {
            $nome = $command['nome'];
            $numero = $command['numero'];

            // Inclua o arquivo de configuração
            require_once(__DIR__ . '/config.php');

            // Conecta-se ao banco de dados
            $conn = new mysqli($servername, $username, $password, $dbname);

            // Verifica a conexão
            if ($conn->connect_error) {
                throw new Exception("Erro na conexão com o banco de dados: " . $conn->connect_error);
            }

            // Prepara a consulta SQL para inserção de dados
            $sql = "INSERT INTO contatos (nome, numero) VALUES (?, ?)";

            // Prepara a instrução
            $stmt = $conn->prepare($sql);

            // Verifica se a preparação da instrução foi bem-sucedida
            if (!$stmt) {
                throw new Exception("Erro na preparação da consulta: " . $conn->error);
            }

            // Define os valores dos parâmetros e seus tipos
            $stmt->bind_param("ss", $nome, $numero);

            // Executa a consulta preparada
            if (!$stmt->execute()) {
                throw new Exception("Erro ao inserir os dados no banco de dados: " . $stmt->error);
            }

            $stmt->close();

            // Diretório base onde os relatórios serão salvos
            $baseDir = 'relatorios';

            // Diretório com base no número
            $relatorioDir = "$baseDir/$numero";

            // Verifica se o diretório com base no número existe, se não, cria-o
            if (!is_dir($relatorioDir)) {
                if (!mkdir($relatorioDir, 0777, true)) {
                    throw new Exception("Erro ao criar o diretório: $relatorioDir");
                }
                chmod($relatorioDir, 0777); // Adicione esta linha
            }

            // Nome do arquivo PDF (com base no nome)
            $pdfFileName = "$relatorioDir/$nome.pdf";

            // Crie um objeto TCPDF
            require_once(__DIR__ . '/vendor/tecnickcom/tcpdf/tcpdf.php');
            $pdf = new TCPDF('L', 'mm', 'A4'); // 'L' define a orientação como horizontal

            // Adicione uma página ao PDF
            $pdf->AddPage();

            // Adicione seu conteúdo ao PDF
            $pdf->SetFont('Helvetica', 'B', 16);
            $pdf->Cell(0, 10, 'Formulário de Contato', 0, 1, 'C');
            $pdf->SetFont('Helvetica', '', 12);
            $pdf->Cell(0, 10, "Nome: $nome", 0, 1);
            $pdf->Cell(0, 10, "Número: $numero", 0, 1);

            // Adicione uma tabela
            $pdf->SetFont('Helvetica', 'B', 12);
            $pdf->Cell(0, 10, 'Tabela de Exemplo', 0, 1, 'C');

            // Defina as configurações da tabela
            $tbl = <<<EOD
<table border="0" cellspacing="0" cellpadding="4">
    <tr>
        <td width="70" style="border: 1px dotted #000000;">Nome:</td>
        <td style="border: 1px dotted #000000;">&nbsp;</td>
    </tr>
    <tr>
        <td width="70" style="border: 1px dotted #000000;">Número:</td>
        <td style="border: 1px dotted #000000;">&nbsp;</td>
    </tr>
</table>
EOD;

            // Adicione a tabela ao PDF
            $pdf->writeHTML($tbl, true, false, false, false, '');

            // Salve o PDF no servidor
            if ($pdf->Output($pdfFileName, 'F') === false) {
                echo "Erro ao gerar e salvar o PDF em: $pdfFileName";
            } else {
                // Configuração do PHPMailer
                $mail = new PHPMailer(true);

                // Configurações do servidor SMTP
                $mail->isSMTP();
                $mail->Host = 'xxxxxx';
                $mail->SMTPAuth = true;
                $mail->Username = 'xxxxxx';
                $mail->Password = 'xxxxx';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port = 465;

                // Configurações de envio de e-mail
                $mail->setFrom('xxxxxx', 'Seu Nome');
                $mail->addAddress('xxxxxxx', 'Destinatário');

                // Anexe o arquivo PDF ao e-mail
                $mail->addAttachment($pdfFileName);

                // Conteúdo do e-mail
                $mail->isHTML(true);
                $mail->Subject = "Novo contato: $nome";
                $mail->Body = "Nome: $nome<br>Número: $numero";

                // Envie o e-mail
                if ($mail->send()) {
                    echo "Registro inserido com sucesso e email enviado com o arquivo PDF!";
                } else {
                    throw new Exception("Erro ao enviar o email: " . $mail->ErrorInfo);
                }
            }
        }
    } else {
        // O aplicativo está offline, não faça nada aqui, os dados já estão armazenados localmente
        echo "O aplicativo está offline. Os dados foram armazenados localmente e serão enviados quando online.";
    }
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
    if (isset($stmt)) {
        $stmt->close();
    }
    if (isset($conn)) {
        $conn->close();
    }
}
?>
