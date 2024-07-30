<?php
    error_reporting(E_ERROR | E_PARSE);
    $url = 'https://valorant-api.com/v1/version';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $resultado = json_decode(curl_exec($ch));
?>
<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Valorant | Versão</title>
        <link rel="shortcut icon" href="../images/favicon.png"/>
        <embed name="myMusic" loop="true" hidden="true" src="./music.mp3">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="../css/table.css" />
        <style>
            body{
                font-family: 'Montserrat', sans-serif;
                background-color: #0F1923;
                color: #E5E5E5;
            }
            .navbar{
                background-color: #ff4655;
            }
            .navbar-brand, .nav-link{
                color: #E5E5E5 !important;
            }
            .navbar-nav .nav-link.active{
                color: #E5E5E5 !important;
                font-weight: bold;
            }
            .dropdown-menu{
                background-color: #1c1c1c;
                border: none;
            }
            .dropdown-menu .dropdown-item{
                color: #ffffff;
            }
            .dropdown-menu .dropdown-item:hover{
                background-color: #ff4655;
                color: #ffffff;
            }
            .table{
                background-color: #1F2A37;
                color: #E5E5E5;
                border: none;
            }
            .table th{
                color: #ff4655;
                background-color: #1F2A37;
            }
            .footer{
                background-color: #0F1923;
                padding: 20px 0;
                text-align: center;
                color: #E5E5E5;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <nav class="navbar navbar-expand-lg">
                <div class="container-fluid">
                    <a class="navbar-brand" href="#">Valorant API</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="../index.php">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="agents.php">Agentes</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="buddies.php">Companheiros</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="bundles.php">Bundles</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="ceremonies.php">Cerimônias</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="content_tiers.php">Níveis de conteúdo</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="currencies.php">Moedas</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="events.php">Eventos</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="gamemodes.php">Modos de jogo</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="../specs.php">Specs</a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Mais</a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="gear.php">Equipamento</a></li>
                                    <li><a class="dropdown-item" href="maps.php">Mapas</a></li>
                                    <li><a class="dropdown-item" href="player_cards.php">Cartas de jogador</a></li>
                                    <li><a class="dropdown-item" href="player_titles.php">Títulos dos Jogadores</a></li>
                                    <li><a class="dropdown-item" href="seasons.php">Temporadas</a></li>
                                    <li><a class="dropdown-item" href="sprays.php">Sprays</a></li>
                                    <li><a class="dropdown-item" href="themes.php">Temas</a></li>
                                    <li><a class="dropdown-item" href="weapons.php">Armas</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="version.php">Versão</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <h2 class="mt-4">Versão</h2>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Branch</th>
                            <th>Versão</th>
                            <th>Versão da build</th>
                            <th>Data de lançamento</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            echo "<tr>";
                            echo "<td>" . $resultado->data->branch . "</td>";
                            echo "<td>" . $resultado->data->version . "</td>";
                            echo "<td>" . $resultado->data->buildVersion . "</td>";
                            echo "<td>" . date('d/m/Y', strtotime($resultado->data->buildDate)) . "</td>";
                            echo "</tr>";
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <footer class="footer">
            <div class="container">
                <p>&copy; 2024 Valorant. All rights reserved.</p>
            </div>
        </footer>
    </body>
</html>