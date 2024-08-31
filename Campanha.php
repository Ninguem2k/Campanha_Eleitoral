<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados da Votação</title>
    <link rel="stylesheet" type="text/css" href="./CSS/campanha.css">
</head>

<body>
    <?php include("./PHP/Campanha.php"); ?>
    <?php include("./VIEWS/navbar.php"); ?>


    <table>
        <thead>
            <tr>
                <th>Nome da Campanha</th>
                <th>CEP</th>
                <th>Data de Início</th>
                <th>Data de Término</th>
                <th>Quantidade de Cadeiras</th>
            </tr>
        </thead>
        <tbody>
            <?php
            echo "<tr>";
            echo "<td>{$campanha['nome_campanha']}</td>";
            echo "<td>{$campanha['cep']}</td>";
            echo "<td>{$campanha['data_inicio']}</td>";
            echo "<td>{$campanha['data_termino']}</td>";
            echo "<td>{$campanha['quantidade_cadeiras']}</td>";
            echo "</tr>";
            ?>
        </tbody>
    </table>

    <h1>Resultados da Votação</h1>

    <h3>Prefeito</h3>
    <table>
        <thead>
            <tr>
                <th>Prefeito</th>
                <th>Votos</th>
                <th>Ganhou</th>
                <!-- <th>Segundo Turno</th> -->
            </tr>
        </thead>
        <tbody>
            <?php
            $primeiro = $prefeitos[0] ?? null;
            $segundo = $prefeitos[1] ?? null;
            foreach ($prefeitos as $prefeito) {
                $ganhou = ($prefeito['votos'] > ($totalVotosPrefeito / 2)) ? 'Sim' : 'Não';
                $segundoTurno = $primeiro && $segundo ? (($segundo['votos'] > ($totalVotosPrefeito / 2)) ? 'Não' : 'Sim') : 'Não';

                echo "<tr>
                        <td>{$prefeito['prefeito']}</td>
                        <td>{$prefeito['votos']}</td>
                        <td>{$ganhou}</td>
                      </tr>";
            }
            ?>
            <!-- <td>{$segundoTurno}</td> -->
        </tbody>
    </table>

    <h3>Vereador</h3>
    <table id="electionTable">
        <thead>
            <tr>
                <th onclick="sortTable(0)">Partido</th>
                <th onclick="sortTable(1)">Candidato</th>
                <th onclick="sortTable(2)">Votos Totais</th>
                <th onclick="sortTable(3)">Legenda do Partido</th>
                <th onclick="sortTable(4)">Eleito</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $candidatosEleitos = [];

            foreach ($candidatos as $partido => $listaCandidatos) {
                $legenda = $partidos[$partido];
                $vagasPorPartido = $resultadoFinal[$partido] ?? 0;
                $eleitos = 0;

                foreach ($listaCandidatos as $candidato) {
                    $eleito = ($eleitos < $vagasPorPartido && $candidato['votos'] >= (0.10 * $quocienteEleitoral)) ? 'Sim' : 'Não';

                    if ($eleito === 'Sim') {
                        $eleitos++;
                    }

                    echo "<tr>
                            <td>{$partido}</td>
                            <td>{$candidato['vereador']}</td>
                            <td>{$candidato['votos']}</td>
                            <td>{$legenda}</td>
                            <td>{$eleito}</td>
                          </tr>";

                    if ($eleito === 'Sim') {
                        $candidatosEleitos[] = $candidato['vereador'];
                    }
                }
            }
            ?>
        </tbody>
    </table>



    <footer>
    
        <p><strong>Votação Proporcional :</strong> A partir de 2020, o sistema de votação proporcional para vereadores no Brasil passou a funcionar da seguinte maneira:

            Fim das Coligações Proporcionais: Partidos não podem mais formar coligações para eleições proporcionais. Cada partido concorre individualmente, e os votos são contabilizados exclusivamente para o partido.

            Quociente Eleitoral: O total de votos válidos (excluídos os nulos e em branco) é dividido pelo número de cadeiras disponíveis no legislativo municipal. Esse cálculo determina quantos votos são necessários para que um partido tenha direito a eleger um vereador.

            Quociente Partidário: O número de votos válidos que um partido recebe é dividido pelo quociente eleitoral. O resultado indica quantas cadeiras o partido conquistou.

            Distribuição das Cadeiras: As cadeiras são distribuídas com base no quociente partidário. Se ainda houver cadeiras sobrando, elas são distribuídas entre os partidos que tiveram as maiores sobras de votos.

            Cláusula de Desempenho Individual: Para ser eleito, um candidato deve ter no mínimo 10% do quociente eleitoral, mesmo que seu partido tenha conquistado cadeiras <?PHP echo("<a href='../VIEWS/Estaticas.php?id={$campanha['id']}'>.</a>") ?> </p>
    </footer>

</body>

</html>