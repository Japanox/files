<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Pré-visualização</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="css/styles.css">
</head>
<body>
    <h1>Pré-visualização</h1>
    <p>Por favor, reveja os dados:</p>

    <form action="process.php" method="post">
        <?php
        if (isset($_POST['nome']) && isset($_POST['numero'])) {
            $nome = $_POST['nome'];
            $numero = $_POST['numero'];

            // Exibir os dados na pré-visualização
            echo "<label for='nome'>Nome:</label>";
            echo "<input type='text' id='nome' name='nome' value='$nome' readonly>";

            echo "<label for='numero'>Número:</label>";
            echo "<input type='text' id='numero' name='numero' value='$numero' readonly>";
        }
        ?>
        <br>
        <input type="submit" value="Enviar">
        <input type="button" value="Editar" onclick="history.go(-1);">
    </form>
</body>
</html>
