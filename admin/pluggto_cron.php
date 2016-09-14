<?php

$url = 'mydomain.com.br'; //sem barra no final

$data = file_get_contents($url . '/index.php?route=api/pluggto/getProductsActives');

$dataAsArray = json_decode($data);

foreach ($dataAsArray->rows as $i => $data)
{
    $response = file_get_contents($url . '/index.php?route=api/pluggto/forceSyncProduct&product_id=' . $data->product_id);

     var_dump($url . '/index.php?route=api/pluggto/forceSyncProduct&product_id=' . $data->product_id);
}

echo '<pre>';print_r($dataAsArray);exit;
