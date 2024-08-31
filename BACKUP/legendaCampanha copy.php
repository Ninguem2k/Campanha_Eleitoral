<?php
// Configuração da conexão com o banco de dados usando PDO
$dsn = 'mysql:host=localhost;dbname=campanha2024riachinho;charset=utf8';
$username = 'root';
$password = '99771431';

try {
    $conn = new PDO($dsn, $username, $password);
    // Configura o modo de erro para exceções
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // SQL para buscar e agrupar os votos por partido e candidato
    $sql = "
        SELECT 
            VEREADOR,
            SUBSTRING_INDEX(SUBSTRING_INDEX(VEREADOR, '-', 2), '-', -1) AS Partido, 
            COUNT(*) AS VotosCandidato
        FROM 
            VOTOS2 
        GROUP BY 
            VEREADOR, Partido
        ORDER BY 
            Partido, VotosCandidato DESC
    ";

    $stmt = $conn->prepare($sql);
    $stmt->execute();

    // Array para armazenar os resultados por partido
    $partidos = [];
    $candidatos = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $partido = $row['Partido'];
        $votosCandidato = $row['VotosCandidato'];
        $vereador = $row['VEREADOR'];

        // Soma os votos por partido (legenda)
        if (!isset($partidos[$partido])) {
            $partidos[$partido] = 0;
        }
        $partidos[$partido] += $votosCandidato;

        // Armazena os candidatos e seus votos
        $candidatos[$partido][] = [
            'vereador' => $vereador,
            'votos' => $votosCandidato
        ];
    }
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados da Eleição</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }

        h1,
        h3 {
            text-align: center;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: #fff;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 12px;
            text-align: center;
        }

        th {
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }

        footer {
            text-align: center;
            margin-top: 20px;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
        }
    </style>
    <script>
        // Função para ordenar a tabela
        function sortTable(n) {
            var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
            table = document.getElementById("electionTable");
            switching = true;
            dir = "asc";
            while (switching) {
                switching = false;
                rows = table.rows;
                for (i = 1; i < (rows.length - 1); i++) {
                    shouldSwitch = false;
                    x = rows[i].getElementsByTagName("TD")[n];
                    y = rows[i + 1].getElementsByTagName("TD")[n];
                    if (dir == "asc") {
                        if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                            shouldSwitch = true;
                            break;
                        }
                    } else if (dir == "desc") {
                        if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                            shouldSwitch = true;
                            break;
                        }
                    }
                }
                if (shouldSwitch) {
                    rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                    switching = true;
                    switchcount++;
                } else {
                    if (switchcount == 0 && dir == "asc") {
                        dir = "desc";
                        switching = true;
                    }
                }
            }
        }
    </script>
</head>

<body>

    <h1>Resultados da Eleição</h1>
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
            foreach ($candidatos as $partido => $listaCandidatos) {
                $legenda = $partidos[$partido];

                // Calcular o quociente eleitoral (exemplo simplificado)
                $totalVotos = array_sum($partidos);
                $quocienteEleitoral = 1000; // Exemplo de quociente eleitoral (deve ser ajustado conforme a eleição)

                // Determinar o número de vagas por partido
                $vagasPorPartido = floor($legenda / $quocienteEleitoral);

                $eleitos = 0;

                foreach ($listaCandidatos as $candidato) {
                    $eleito = ($eleitos < $vagasPorPartido) ? 'Sim' : 'Não';

                    echo "<tr>
                            <td>{$partido}</td>
                            <td>{$candidato['vereador']}</td>
                            <td>{$candidato['votos']}</td>
                            <td>{$legenda}</td>
                            <td>{$eleito}</td>
                          </tr>";

                    if ($eleito == 'Sim') {
                        $eleitos++;
                    }
                }
            }
            ?>
        </tbody>
    </table>

    <footer>
        <p><strong> Votação Proporcional :</strong> A partir de 2020, o sistema de votação proporcional para vereadores no Brasil passou a funcionar da seguinte maneira:

            Fim das Coligações Proporcionais: Partidos não podem mais formar coligações para eleições proporcionais. Cada partido concorre individualmente, e os votos são contabilizados exclusivamente para o partido.

            Quociente Eleitoral: O total de votos válidos (excluídos os nulos e em branco) é dividido pelo número de cadeiras disponíveis no legislativo municipal. Esse cálculo determina quantos votos são necessários para que um partido tenha direito a eleger um vereador.

            Quociente Partidário: O número de votos válidos que um partido recebe é dividido pelo quociente eleitoral. O resultado indica quantas cadeiras o partido conquistou.

            Distribuição das Cadeiras: As cadeiras são distribuídas com base no quociente partidário. Se ainda houver cadeiras sobrando, elas são distribuídas entre os partidos que tiveram as maiores sobras de votos.

            Cláusula de Desempenho Individual: Para ser eleito, um candidato deve ter no mínimo 10% do quociente eleitoral, mesmo que seu partido tenha conquistado cadeiras.</p>
    </footer>

</body>

</html>