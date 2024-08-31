<?php

    try {
        // SQL para buscar todas as campanhas
        $sql = "SELECT id, nome_campanha, cep, data_inicio, data_termino, quantidade_cadeiras FROM campanhas";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        // Armazena os resultados
        $campanhas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
    }