<?php
    $url = 'https://valorant-api.com/v1/bundles?language=pt-BR';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $resultado = json_decode(curl_exec($ch));
    //var_dump ($resultado);
    foreach($resultado->data as $agente){
        echo "Nome: " . $agente->displayNameSubText . "<br>";
        echo "Descrição: " . $agente->extraDescription . "<br>";
        echo "Descrição 2: " . $agente->promoDescription . "<br>";
        echo "Descrição 3: " . $agente->useAdditionalContext . "<br>";
        echo "<tr></tr>" . "<br>";
        /*echo "Nome: " . $agente->abilities[1]->displayName . "<br>";
        echo "Descrição: " . $agente->abilities[1]->description . "<br>";
        echo "<tr></tr>" . "<br>";
        echo "Nome: " . $agente->abilities[2]->displayName . "<br>";
        echo "Descrição: " . $agente->abilities[2]->description . "<br>";
        echo "<tr></tr>" . "<br>";
        echo "Nome: " . $agente->abilities[3]->displayName . "<br>";
        echo "Descrição: " . $agente->abilities[3]->description . "<br>";
        echo "<tr></tr>" . "<br>";*/
        //echo "Nome de desenvolvimento: " . $agente->developerName . "<br>";
    }
?>