<?php

$url = 'https://www.sprinta.com.br/group/getMembers/fcp/';

$page = file_get_contents($url);
$sprinta = json_decode($page, true);

$pages = (int)$sprinta['num_pages'];

for($i=0;$i<=$pages;$i++)
{
    $result = file_get_contents($url.$i);
    $result = json_decode($result, true);

    $row = count($result['result']);

    for($c=0;$c<$row;$c++)
    {
        $results['results'][] = $result['result'][$c];
    }
}
$total = count($results['results']);

$results['total'] =  $total;

echo "<pre>";
echo json_encode($results);
echo "</pre>";