@extends('layouts.politicsPromo')
@section('content-sucess')
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Políticas de privacidad</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
        }

        .main-container {
            padding: 50px 50px;
            display: flex;
            justify-content: center;
        }

        .container {
            max-width: 1000px;
        }

        .text {
            font-size: 20px;
            text-align: justify;
            line-height: 1.3;
        }
    </style>
</head>

<body style="background-color: #fafafa; margin: 0; padding: 0; -webkit-text-size-adjust: none; text-size-adjust: none;">
    <div class="main-container">
        <div class="container">
            <div style="text-align: center;">
                <h1>ALLPA WARMI POLÍTICAS DE PRIVACIDAD</h1>
            </div>
            <div>
                <p class="text">
                ALLPA WARMI te informa sobre su Política de Privacidad respecto del tratamiento y protección de los datos de carácter personal de los usuarios y clientes que puedan ser recabados por la navegación o contratación de servicios a través de esta aplicación.

En este sentido, ALLPA WARMI garantiza el cumplimiento de la normativa vigente en materia de protección de datos personales, reflejada en la Ley Orgánica 3/2018, de 5 de diciembre, de Protección de Datos Personales y de Garantía de Derechos Digitales (LOPD GDD). Cumple también con el Reglamento (UE) 2016/679 del Parlamento Europeo y del Consejo de 27 de abril de 2016 relativo a la protección de las personas físicas (RGPD).

El uso de ALLPA WARMI implica la aceptación de esta Política de Privacidad.
                </p>
            </div>
            
        </div>

    </div>
</body>
            
@endsection
