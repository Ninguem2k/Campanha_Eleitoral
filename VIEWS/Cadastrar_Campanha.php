<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Campanha</title>
    <link rel="stylesheet" type="text/css" href="../CSS/cadastrar_campanha.css">

</head>
<body>
    <?php include("../VIEWS/navbar.php"); ?> 
    <h1>Cadastro de Campanha</h1>
    <form method="post" action="../PHP/Cadastrar_Campanha.php" enctype="multipart/form-data">

        <label for="nome_cidade">Nome da Cidade:</label>
        <input type="text" id="nome_cidade" name="nome_cidade" required>

        <label for="cep">CEP da Cidade:</label>
        <input type="text" id="cep" name="cep" required>

        <label for="nome_campanha">Nome da Campanha:</label>
        <input type="text" id="nome_campanha" name="nome_campanha" required>

        <label for="data_inicio">Data de Início:</label>
        <input type="date" id="data_inicio" name="data_inicio" required>

        <label for="data_termino">Data de Término:</label>
        <input type="date" id="data_termino" name="data_termino" required>

        <label for="quantidade_cadeiras">Quantidade de Cadeiras:</label>
        <input type="number" id="quantidade_cadeiras" name="quantidade_cadeiras" required>

        <label for="arquivo_csv">Importar Arquivo CSV:</label>
        <input type="file" id="arquivo_csv" name="arquivo_csv" accept=".csv" required>

        <input type="submit" value="Cadastrar Campanha">
    </form>

</body>
</html>
