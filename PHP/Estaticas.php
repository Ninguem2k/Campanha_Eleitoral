<?php
include_once("../PHP/conn.php");

try {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        $sql = "SELECT id, nome_campanha, cep, data_inicio, data_termino, quantidade_cadeiras FROM campanhas WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $campanha = $stmt->fetch(PDO::FETCH_ASSOC);


        if (!$campanha) {
            echo "<p>Campanha não encontrada.</p>";
            exit;
        }

        $sql = "
            SELECT 
                VEREADOR,
                SUBSTRING_INDEX(SUBSTRING_INDEX(VEREADOR, '-', 2), '-', -1) AS Partido, 
                COUNT(*) AS VotosCandidato
            FROM 
                VOTOS
            WHERE
                IDCAMPANHA = ".$campanha['id']."
            GROUP BY 
                VEREADOR, Partido
            ORDER BY 
                VotosCandidato DESC
        ";

        $stmt = $conn->prepare($sql);
        $stmt->execute();

        // Array para armazenar os resultados por partido
        $partidos = [];
        $candidatos = [];
        $totalVotos = 0;

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $partido = $row['Partido'];
            $votosCandidato = $row['VotosCandidato'];
            $vereador = $row['VEREADOR'];

            if (!isset($partidos[$partido])) {
                $partidos[$partido] = 0;
            }
            $partidos[$partido] += $votosCandidato;

            $candidatos[] = [
                'partido' => $partido,
                'vereador' => $vereador,
                'votos' => $votosCandidato
            ];

            $totalVotos += $votosCandidato;
        }

        // Ordenar candidatos pelos votos para pegar os top 10
        usort($candidatos, function ($a, $b) {
            return $b['votos'] - $a['votos'];
        });

        $topCandidatos = array_slice($candidatos, 0, 10);

    } else {
        echo "<p>ID da campanha não fornecido.</p>";
        exit;
    }
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
