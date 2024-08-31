<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estatísticas da Votação</title>
    <link rel="stylesheet" type="text/css" href="../CSS/navbar.css">
</head>
<body>
    <?php include("../PHP/Estaticas.php"); ?> 
    <?php include("../VIEWS/navbar.php"); ?> 

    <h1>Estatísticas da Votação</h1>

    <h3>Top 10 Candidatos Mais Votados</h3>
    <table>
        <thead>
            <tr>
                <th>Posição</th>
                <th>Vereador</th>
                <th>Partido</th>
                <th>Votos</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($topCandidatos as $index => $candidato) {
                echo "<tr>
                        <td>" . ($index + 1) . "</td>
                        <td>{$candidato['vereador']}</td>
                        <td>{$candidato['partido']}</td>
                        <td>{$candidato['votos']}</td>
                      </tr>";
            }
            ?>
        </tbody>
    </table>

    <h3>Total de Votos por Partido</h3>
    <table>
        <thead>
            <tr>
                <th>Partido</th>
                <th>Total de Votos</th>
                <th>Número de Candidatos</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($partidos as $partido => $votos) {
                $numCandidatos = count(array_filter($candidatos, function ($candidato) use ($partido) {
                    return $candidato['partido'] === $partido;
                }));
                echo "<tr>
                        <td>{$partido}</td>
                        <td>{$votos}</td>
                        <td>{$numCandidatos}</td>
                      </tr>";
            }
            ?>
        </tbody>
    </table>

    <div class="summary">
        <h3>Resumo da Votação</h3>
        <p>Total de Votos: <?php echo $totalVotos; ?></p>
        <p>Número de Partidos: <?php echo count($partidos); ?></p>
        <p>Número de Candidatos: <?php echo count($candidatos); ?></p>
    </div>

</body>
</html>
