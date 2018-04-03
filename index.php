<?php

error_reporting(0);

if ($_GET['event'] > 0) {

    $event = $_GET['event'];

    $urlParticipantes = file_get_contents("https://api.sprinta.com.br/events/athletes/{$event}/0/1000000");
    $urlCategories = file_get_contents("https://api.sprinta.com.br/events/categories/{$event}");
    $urlFederados = file_get_contents('http://silvioney.com.br/sprinta/federados.php');

    $dataParticipantes = json_decode($urlParticipantes, true);
    $dataFederados = json_decode($urlFederados, true);
    $dataCategorias = json_decode($urlCategories, true);

    $resCategorias = $dataCategorias;
    $resFederados = $dataFederados['results'];
    $resParticipantes = $dataParticipantes['result'];

    foreach ($resCategorias AS $categorias) {
        $categoria[$categorias['id']] = $categorias['description'];
    }

    foreach ($resFederados as $result) {
        $arrayFederados[] = strtolower($result['name']);
    }

    foreach ($resParticipantes as $result) {
        $participantes[] = [
            'nome' => $result['user_name'],
            'federado' => in_array(strtolower($result['user_name']), $arrayFederados) ? 'Sim' : 'Não',
            'confirmado' => $result['confirmed'] == 1 ? 'Sim' : 'Não',
            'categoria' => $categoria[$result['category']]
        ];
    }

}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate"/>
    <meta http-equiv="Pragma" content="no-cache"/>
    <meta http-equiv="Expires" content="0"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css"/>
</head>
<body>

<div class="container mt-3">
    <form action="" method="get" class="d-print-none">
        <div class="row">
            <div class="col-md-3">
                <input type="text" class="form-control" name="event" value="<?= $event > 0 ? $event : '' ?>"
                       placeholder="event hash" required/>
            </div>
            <div class="col-md-3">
                <button class="btn btn-success">Acessar</button>
            </div>
        </div>
    </form>

    <div class="row">
        <div class="col-md-12">
            <table data-order='[[ 3, "asc" ]]' class="table table-striped table-hover table-bordered">
                <thead>
                <tr>
                    <th>NOME</th>
                    <th>CATEGORIA</th>
                    <th>CONFIRMADO</th>
                    <th>FEDERADO</th>
                </tr>
                </thead>
                <tbody>
                <? foreach ($participantes as $participante) { ?>
                    <tr>
                        <td><?= ucwords(strtolower($participante['nome'])) ?></td>
                        <td><?= $participante['categoria'] ?></td>
                        <td><?= $participante['confirmado'] ?></td>
                        <td><?= $participante['federado'] ?></td>
                    </tr>
                <? } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
        crossorigin="anonymous"></script>
<script src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="//cdn.datatables.net/select/1.2.5/js/dataTables.select.min.js"></script>

<script>
    $(document).ready(function () {
        $('table').DataTable({
            paging: false,
            select: true
        });

        $('#DataTables_Table_0_filter').addClass('d-print-none');
    });
</script>
</body>
</html>