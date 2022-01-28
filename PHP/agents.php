<?php
    error_reporting(E_ERROR | E_PARSE);
?>
<html>
    <head>
        <title>Valorant | Agentes</title>
        <link rel="shortcut icon" href="./favicon.png"/>
        <embed name="myMusic" loop="true" hidden="true" src="./music.mp3">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="css/table.css" />
    </head>
    <body>
        <?php
            $url = 'https://valorant-api.com/v1/agents?language=pt-BR';
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $resultado = json_decode(curl_exec($ch));
            //var_dump ($resultado);
            /*foreach($resultado->data as $agente){
                echo "Nome: " . $agente->displayName . "<br>";
                echo "Descrição: " . $agente->description . "<br>";
                echo "Nome de desenvolvimento: " . $agente->developerName . "<br>";
            }*/
        ?>
        <div class="container">
            <ul class="nav nav-tabs">
                <li><a href="./index.php">Home</a></li>
                <li class="active"><a href="agents.php">Agentes</a></li></li>
                <li><a href="buddies.php">Companheiros</a></li>
                <li><a href="bundles.php">Bundles</a></li>
                <li><a href="ceremonies.php">Cerimônias</a></li>
                <li><a href="competitive_tiers.php">Níveis competitivos</a></li>
                <li><a href="content_tiers.php">Níveis de conteúdo</a></li>
                <li><a href="contracts.php">Contratos</a></li>
                <li><a href="currencies.php">Moedas</a></li>
                <li><a href="events.php">Eventos</a></li>
                <li><a href="gamemodes.php">Modos de jogo</a></li>
                <li><a href="gear.php">Equipamento</a></li>
                <li><a href="maps.php">Mapas</a></li>
                <li><a href="player_cards.php">Cartas de jogador</a></li>
                <li><a href="player_titles.php">Títulos dos Jogadores</a></li>
                <li><a href="seasons.php">Temporadas</a></li>
                <li><a href="sprays.php">Sprays</a></li>
                <li><a href="themes.php">Temas</a></li>
                <li><a href="weapons.php">Armas</a></li>
                <li><a href="version.php">Versão</a></li>
                <li><a href="specs.php">Specs</a></li>
            </ul>
            <h2>Agentes</h2>
            <div class="table-bordered">
                <table class="table">
                    <thead>
                        <td></td>
                        <td>Nome</td>
                        <td>Descrição</td>
                        <td>Características</td>
                        <td>Papel</td>
                        <td>Habilidades</td>
                    </thead>
                    <?php
                        foreach($resultado->data as $agente){
                            echo "<tr>";
                            echo "<td>"."<img src=$agente->killfeedPortrait><img>"."</td>";
                            echo "<td>"."$agente->displayName"."</td>";
                            echo "<td>".$agente->description."</td>";
                            echo "<td>".$agente->characterTags[0]."</td>";
                            echo "<td>".$agente->role->displayName."</td>";
                            echo "<td>".$agente->abilities[0]->displayName."</td>";
                            echo "</tr>";
                        }
                    ?>
                </table>
            </div>
        </div>
    </body>
</html>