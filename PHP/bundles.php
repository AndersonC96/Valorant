<?php
    error_reporting(E_ERROR | E_PARSE);
?>
<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Valorant | Bundles</title>
        <link rel="shortcut icon" href="../images/favicon.png"/>
        <embed name="myMusic" loop="true" hidden="true" src="./music.mp3">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="../css/table.css" />
        <style>
            .navbar-brand{
                font-weight: bold;
                color: #ff4655 !important;
            }
            .navbar-nav .nav-link{
                color: #ffffff !important;
                margin-right: 10px;
            }
            .navbar-nav .nav-link.active{
                color: #ff4655 !important;
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
        </style>
    </head>
    <body>
        <?php
            $url = 'https://valorant-api.com/v1/bundles?language=pt-BR';
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $resultado = json_decode(curl_exec($ch));
        ?>
        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
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
                                <a class="nav-link" href="../specs.php">Specs</a>
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
            <h2 class="mt-4">Bundles</h2>
            <div class="row">
                <?php
                    foreach($resultado->data as $bundle){
                        $id = str_replace("https://valorant-api.com/v1/bundles/", "", $bundle->uuid);
                ?>
                <div class="col-md-3 mb-4">
                    <div class="card" style="width: 18rem;">
                        <img class="card-img-top" src="<?php echo $bundle->displayIcon; ?>" alt="Imagem do bundle <?php echo $bundle->displayName; ?>">
                        <div class="card-body">
                            <h5 class="card-title" style="color: #ff4655"><?php echo $bundle->displayName; ?></h5>
                        </div>
                    </div>
                </div>
                <?php
                    }
                ?>
            </div>
        </div>
    </body>
</html>