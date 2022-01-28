<?php
    $url = 'https://valorant-api.com/v1/agents?language=pt-BR';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $resultado = json_decode(curl_exec($ch));
    //var_dump ($resultado);
    foreach($resultado->data as $agente){
        echo "Nome: " . $agente->displayName . "<br>";
        echo "Descrição: " . $agente->description . "<br>";
        echo "Nome de desenvolvimento: " . $agente->developerName . "<br>";
    }
?>