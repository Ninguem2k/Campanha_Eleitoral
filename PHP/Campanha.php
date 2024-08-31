<?php
include_once("./PHP/conn.php");

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

    try {
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
                Partido, VotosCandidato DESC
        ";
    
        $stmt = $conn->prepare($sql);
        $stmt->execute();
    
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

            $candidatos[$partido][] = [
                'vereador' => $vereador,
                'votos' => $votosCandidato
            ];
    
            $totalVotos += $votosCandidato;
        }
    
        $numCadeiras = $campanha['quantidade_cadeiras'];  

        $quocienteEleitoral = $totalVotos / $numCadeiras;
    
        $quocientePartidario = [];
        foreach ($partidos as $partido => $votos) {
            $quocientePartidario[$partido] = floor($votos / $quocienteEleitoral);
        }
    
        $quocienteEleitoral = round($quocienteEleitoral, 2);
    
        $sobras = [];
        foreach ($partidos as $partido => $votos) {
            $votosArredondados = round($votos, 2);
    
            $sobras[$partido] = $votosArredondados - floor($votosArredondados / $quocienteEleitoral) * $quocienteEleitoral;
        }
    
        // Para depuração: exibir o resultado das sobras
        // foreach ($sobras as $partido => $sobra) {
        //     echo "Partido: $partido - Sobra: " . number_format($sobra, 2) . "<br>";
        // }
    
        arsort($quocientePartidario);
        arsort($sobras);
    
        $cadeirasAdicionais = $numCadeiras - array_sum($quocientePartidario);
    
        $resultadoFinal = [];
        foreach ($quocientePartidario as $partido => $cadeiras) {
            $resultadoFinal[$partido] = $cadeiras;
        }
    
        $partidosEleitos = [];
        foreach ($sobras as $partido => $sobra) {
            if (isset($resultadoFinal[$partido])) {
                $resultadoFinal[$partido] += min($cadeirasAdicionais, $sobra);
            }
            if ($cadeirasAdicionais > 0) {
                $cadeirasAdicionais--;
            }
        }
    
        $sqlPrefeito = "
            SELECT 
                PREFEITO,
                COUNT(*) AS VotosPrefeito
            FROM 
                VOTOS
            WHERE
                IDCAMPANHA =  ".$campanha['id']."
            GROUP BY 
                PREFEITO
            ORDER BY 
                VotosPrefeito DESC
        ";
    
        $stmt = $conn->prepare($sqlPrefeito);
        $stmt->execute();
    
        $prefeitos = [];
        $totalVotosPrefeito = 0;
    
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $prefeito = $row['PREFEITO'];
            $votosPrefeito = $row['VotosPrefeito'];
    
            $prefeitos[] = [
                'prefeito' => $prefeito,
                'votos' => $votosPrefeito
            ];
    
            $totalVotosPrefeito += $votosPrefeito;
        }
    
        $primeiro = $prefeitos[0] ?? null;
        $segundo = $prefeitos[1] ?? null;
        $segundoTurno = false;
    
        if ($primeiro && $totalVotosPrefeito > 0) {
            $quocienteEleitoralPrefeito = $totalVotosPrefeito / 2;
            if ($segundo && $segundo['votos'] > ($totalVotosPrefeito / 2)) {
                $segundoTurno = false;
            } else {
                $segundoTurno = true;
            }
        }
    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
    }
    
} else {
    echo "<p>ID da campanha não fornecido.</p>";
    exit;
}

