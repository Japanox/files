<!DOCTYPE html>
<html>
<head>
    <title>Listagem de Relatórios</title>
</head>
<body>
    <h1>Listagem de Relatórios</h1>

    <?php
    // Consulte o diretório "relatorios" e liste todos os relatórios PDF encontrados
    $relatorios = glob("relatorios/*.pdf");

    if (empty($relatorios)) {
        echo "<p>Nenhum relatório encontrado.</p>";
    } else {
        // Ordenar a lista de relatórios por data de criação (do mais recente para o mais antigo)
        usort($relatorios, function($a, $b) {
            return filectime($b) - filectime($a);
        });

        // Número de relatórios por página
        $relatoriosPorPagina = 15;

        // Página atual (inicialmente 1)
        $paginaAtual = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;

        // Calcule o índice inicial e final dos relatórios para esta página
        $indiceInicial = ($paginaAtual - 1) * $relatoriosPorPagina;
        $indiceFinal = $indiceInicial + $relatoriosPorPagina - 1;

        // Exiba os relatórios desta página
        echo "<h2>Relatórios encontrados:</h2>";

        for ($i = $indiceInicial; $i <= $indiceFinal && $i < count($relatorios); $i++) {
            $relatorio = $relatorios[$i];
            $nome_relatorio = basename($relatorio);

            echo "<p>";
            echo "Nome do Relatório: $nome_relatorio ";
            echo "<a href='$relatorio' target='_blank'>Visualizar</a> | ";
            echo "<a href='$relatorio' download>Baixar</a>";
            echo "</p>";
        }

        // Exibir as opções de páginação
        $totalRelatorios = count($relatorios);
        $totalPaginas = ceil($totalRelatorios / $relatoriosPorPagina);

        echo "<div class='paginacao'>";
        if ($paginaAtual > 1) {
            echo "<a href='listagem_relatorios.php?pagina=" . ($paginaAtual - 1) . "'>Anterior</a> ";
        }

        for ($pagina = 1; $pagina <= $totalPaginas; $pagina++) {
            echo "<a href='listagem_relatorios.php?pagina=$pagina'>$pagina</a> ";
        }

        if ($paginaAtual < $totalPaginas) {
            echo "<a href='listagem_relatorios.php?pagina=" . ($paginaAtual + 1) . "'>Próxima</a>";
        }
        echo "</div>";
    }
    ?>

</body>
</html>
