<?php
/**
 * Created by PhpStorm.
 * User: Nicolai Furtado
 * Date: 16/06/2021
 * Time: 08:56
 */
require "connection.php";

$sql = "SELECT * FROM news";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
?>


<html>
<head>
    <title>DESAFIO: forseti-code-challenge - Nicolai Furtado</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">
</head>
<body>
<div class="container-fluid">

    <div class="text-center">
        <h1>DESAFIO: forseti-code-challenge</h1>
        <hr>
    </div>

    <h3>Últimas Buscas</h3>
    <a href="serach.php"><button type="button" class="btn btn-outline-primary"><i class="fas fa-search"></i> Buscar</button></a>
    <?php if(count($data) != 0){ ?>
        <a href="clean.php"><button type="button" class="btn btn-outline-danger"><i class="fas fa-trash"></i> Limpar Banco de Dados</button></a>
    <?php } ?>
    <br><br>
    <?php if(count($data) == 0){ ?>
        <h2><span class="badge bg-secondary">Ainda não foi armazenado nenhum dado. Clique em buscar!</span></h2>
    <?php } else { ?>

        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong>Dados encontrados!</strong> Logo abaixo na tabela, está sendo apresentado as notícias das primeiras 5 páginas do link <a href="https://www.gov.br/compras/pt-br/acesso-a-informacao/noticias" target="_blank">https://www.gov.br/compras/pt-br/acesso-a-informacao/noticias</a>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>

        <table class="table table-striped table-bordered" id="dataTable">
            <thead>
            <tr>
                <th scope="col">Id</th>
                <th scope="col">Notícia</th>
                <th scope="col">Data e Hora</th>
                <th scope="col">Hash</th>
                <th scope="col">Página</th>
            </tr>

            </thead>
            <tbody>
            <?php
                foreach($data as $d){
                $dateTimeBr = explode(" ", $d['dateTime']);
                $timeBr = $dateTimeBr[1];
                $dateManiBr = explode("-", $dateTimeBr[0]);
                $dateBr = $dateManiBr[2]."/".$dateManiBr[1]."/".$dateManiBr[0];
            ?>
                <tr>
                    <th scope="row"><?=$d['id']?></th>
                    <td><a href="<?=$d['url']?>" target="_blank"><?=$d['title']?></a></td>
                    <td><?=$dateBr?> - <?=$timeBr?></td>
                    <td><?=$d['hash']?></td>
                    <td class="text-center"><?=$d['page']?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    <?php } ?>

</div>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>

<script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable();
    } );
</script>
</html>