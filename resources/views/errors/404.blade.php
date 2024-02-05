<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Página não encontrada</title>
    <link rel="shortcut icon" href="{{ URL::asset('/css/icone.png') }}">
    <link href="{{ asset('css/404.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.google.com/specimen/Jost?sidebar.open&selection.family=Jost" type='text/css'>
</head>

<body class="bg-purple">
        
    <div class="stars">
        
        <div class="central-body">
            <p class="image-404">404</p>
            <p class="text-404">OPS!!!</p>
               <p class="text-404"><b>PÁGINA INEXISTENTE</b></p>
            <a href="{{ route('home')}}" class="btn-go-home"><b>Voltar para o começo</b></a>
        </div>
        <div class="objects">
            <img class="object_rocket" src="http://salehriaz.com/404Page/img/rocket.svg" width="40px">
            <div class="earth-moon">
                <img class="object_earth" src="http://salehriaz.com/404Page/img/earth.svg" width="100px">
                <img class="object_moon" src="http://salehriaz.com/404Page/img/moon.svg" width="80px">
            </div>
            <div class="box_astronaut">
                <img class="object_astronaut" src="http://salehriaz.com/404Page/img/astronaut.svg" width="140px">
            </div>
        </div>
        <div class="glowing_stars">
            <div class="star"></div>
            <div class="star"></div>
            <div class="star"></div>
            <div class="star"></div>
            <div class="star"></div>

        </div>

    </div>
</body>
</html>