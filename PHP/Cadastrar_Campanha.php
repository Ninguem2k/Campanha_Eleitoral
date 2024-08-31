<?php
include("../PHP/conn.php");
try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $cep = $_POST['cep'];
        $nomeCampanha = $_POST['nome_campanha'];
        $dataInicio = $_POST['data_inicio'];
        $dataTermino = $_POST['data_termino'];
        $quantidadeCadeiras = $_POST['quantidade_cadeiras'];

        $sql = "INSERT INTO campanhas (cep, nome_campanha, data_inicio, data_termino, quantidade_cadeiras)
                VALUES (:cep, :nome_campanha, :data_inicio, :data_termino, :quantidade_cadeiras)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':cep', $cep);
        $stmt->bindParam(':nome_campanha', $nomeCampanha);
        $stmt->bindParam(':data_inicio', $dataInicio);
        $stmt->bindParam(':data_termino', $dataTermino);
        $stmt->bindParam(':quantidade_cadeiras', $quantidadeCadeiras);
        $stmt->execute();

        $idCampanha = $conn->lastInsertId();
        var_dump($idCampanha);

        if (isset($_FILES['arquivo_csv']) && $_FILES['arquivo_csv']['error'] == 0) {
            $csvFile = $_FILES['arquivo_csv']['tmp_name'];

            if (($handle = fopen($csvFile, 'r')) !== FALSE) {
                fgetcsv($handle, 1000, ',');

                $sqlVotos = "INSERT INTO votos (DATA, PREFEITO, VEREADOR, IDCAMPANHA) 
                             VALUES (:data, :prefeito, :vereador, :idCampanha)";
                $stmtVotos = $conn->prepare($sqlVotos);

                while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
                    $stmtVotos->bindParam(':data', $data[0]);
                    $stmtVotos->bindParam(':prefeito', $data[1]);
                    $stmtVotos->bindParam(':vereador', $data[2]);
                    $stmtVotos->bindParam(':idCampanha', $idCampanha);
                    $stmtVotos->execute();
                }

                fclose($handle);
                echo "<p>Campanha e votos cadastrados com sucesso!</p>";
            } else {
                echo "<p>Erro ao abrir o arquivo CSV.</p>";
            }
        } else {
            echo "<p>Erro ao fazer upload do arquivo CSV.</p>";
        }
    }
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
