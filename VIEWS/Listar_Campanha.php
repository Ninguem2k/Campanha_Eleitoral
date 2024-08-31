<?php
    include("../PHP/conn.php");  
    include("../PHP/Listar_Campanha.php");  
    include("../VIEWS/navbar.php");  
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Campanhas</title>
    <link rel="stylesheet" type="text/css" href="../CSS/listar_campanha.css">
</head>

<body>

    <h1>Lista de Campanhas</h1>
    <table>
        <thead>
            <tr>
                <th>Nome da Campanha</th>
                <th>CEP</th>
                <th>Data de Início</th>
                <th>Data de Término</th>
                <th>Quantidade de Cadeiras</th>
                <th>Detalhar</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($campanhas) {
                foreach ($campanhas as $campanha) {
                    echo "<tr>";
                    echo "<td>{$campanha['nome_campanha']}</td>";
                    echo "<td>{$campanha['cep']}</td>";
                    echo "<td>{$campanha['data_inicio']}</td>";
                    echo "<td>{$campanha['data_termino']}</td>";
                    echo "<td>{$campanha['quantidade_cadeiras']}</td>";
                    echo "<td><a href='../Campanha.php?id={$campanha['id']}'>Abrir</a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>Nenhuma campanha encontrada.</td></tr>";
            }
            ?>
        </tbody>
    </table>

</body>

</html>