<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
       .pagenum:before {
    content: counter(page);
}
      </style>
</head>

<body>
    <div style="text-align: center;">
        <text style="font-size: 25px; font-family: Arial, Helvetica, sans-serif">Constancia de auditoria</text><br>
        <text style="font-size: 18px; font-family: Arial, Helvetica, sans-serif; margin-top: 25px;">Nº 02179/23</text>
    </div>
    
    <div style="margin-top: 25px;font-size: 18px; font-family: Arial, Helvetica, sans-serif; margin-top: 25px;">
        <text>GELEENCL. por solicitud de {{$provider->company->comercial_name}}, ha llevado a cabo el proceso de auditoria al proveedor:</text>
    </div>
    <div style="text-align: center; margin-top: 20px;">
        <text style="font-size: 20px; font-family: Arial, Helvetica, sans-serif;">{{$provider->legal_name}}</text>
    </div>
    <div style="text-align: center; margin-top: 20px;">
        <text style="font-size: 15px; font-family: Arial, Helvetica, sans-serif;">RUC: {{$provider->ruc}}</text><br>
        <text style="font-size: 15px; font-family: Arial, Helvetica, sans-serif;">{{$provider->typeProvider->name}}</text><br>
        <text style="font-size: 15px; font-family: Arial, Helvetica, sans-serif;">Quito - Ecuador</text>
    </div>
    <div style="text-align: center" style="margin-left: 10%; font-family: Arial, Helvetica, sans-serif; margin-top: 20px">
    <table style="width: 90%; border-collapse: collapse;"> 
        <thead> 
            <tr style="background-color: #4682B4; 
            color: #ffffff; 
            text-align: center;  padding: 12px 15px;
            ">
                <th style=" border: 1px solid black;">Aspecto</th>
                <th style=" border: 1px solid black;">Ponderado</th> 
                <th style=" border: 1px solid black;">Puntaje Parcial</th> 
                <th style=" border: 1px solid black;">Valor obtenido</th> 
            </tr> 
        </thead> 
        <tbody> 
           
            @foreach($sectiones as $section)
            <tr> 
                <td style=" border: 1px solid black;">{{$section['section']}}</td> 
                <td style=" border: 1px solid black; text-align: center;">{{$section['puntaje']}}</td> 
                <td style=" border: 1px solid black; text-align: center;">{{$section['parcial']}}</td> 
                <td style=" border: 1px solid black; text-align: center;">{{$section['valor']}}</td> 
            </tr>
            @endforeach
         
            
        </tbody> 
    </table>
    </div>
    <div style="text-align: center" style="margin-left: 20%; font-family: Arial, Helvetica, sans-serif; margin-top: 20px">
        <table style="width: 80%; border-collapse: collapse;">
            <tr style="text-align: center;">
                <td style=" border: 1px solid black;">Total</td>
                <td style=" border: 1px solid black;">{{$provider->qualification}}</td>
               
                <td style=" border: 1px solid black;">Nivel</td>
                @if ($provider->qualification >= 90)
                <td style=" border: 1px solid black;">A</td>
                @endif
                @if ($provider->qualification < 90 && $provider->qualification >= 70)
                <td style=" border: 1px solid black;">B</td>
                @endif
                @if ($provider->qualification < 70)
                <td style=" border: 1px solid black;">C</td>
                @endif
                
            </tr>
        </table>
    </div>
    <div style="margin-top: 15px;">
        <div style="float: left; width: 40%;">
            <text style="font-size: 12px; font-family: Arial, Helvetica, sans-serif;">Período de Validez Del: {{$fecha_actual}} al {{$fecha_anio}} </text><br>
            <text style="font-size: 12px; font-family: Arial, Helvetica, sans-serif;">Emisión 2- REN</text><br>
        </div>
        <div style="margin-left: 60%; width: 50%;">
            <text style="font-size: 12px; font-family: Arial, Helvetica, sans-serif;">Autorizado por </text><br>
            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('uploads' . $url))) }}"><br>
            <text style="font-size: 12px; font-family: Arial, Helvetica, sans-serif;">{{$datosFirma->nombres}}</text><br>
            <text style="font-size: 12px; font-family: Arial, Helvetica, sans-serif;">{{$datosFirma->cargo}}</text><br>
            <text style="font-size: 12px; font-family: Arial, Helvetica, sans-serif;">GELEENCL</text>
        </div>
    </div>
    <div style="margin-top: 15px; font-family: Arial, Helvetica, sans-serif;">
    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            <td style=" border: 1px solid black;">CONDICIONES DE EMISIÓN</td>
        </tr>
        <tr>
            <td style=" border: 1px solid black;">
                <text>
                    1. Información consignada en la presente constancia es un resumen del informe de resultados y fiel reflejo de nuestros hallazgos en el lugar y fecha de la auditoria.
                </text><br>
                <text>
                    2. El alcance de la presente constancia se extiende exclusivamente a la actividad evaluada. Esta auditor a y sus resultados no constituyen
                    un vinculo contractual con {{$provider->company->comercial_name}}    
                </text><br>
                <text>
                    3. La responsabllldad de nuestra empresa se extiende a garantizar únicamente que el proveedor ha sido auditado de acuedo al procedimiento establecido por {{$provider->company->comercial_name}}. GELEENCL. no asume responsabllldad alguna si elproveedor ralla en algún producto o servicio.que fue objeto de la auditoria
  
                </text>
                </td>
        </tr>

    </table>
    </div>
    <div style="margin-top: 10px;">
        <text style="font-size: 12px; font-family: Arial, Helvetica, sans-serif;">La calificación aprobatoria es de 75%.</text>
    </div>
    <div style="margin-top: 10px; text-align: center;">
        <text style="font-size: 12px; font-family: Arial, Helvetica, sans-serif;">OL 285 2967n8</text>
    </div>
    
    <div style="margin-top: 15px; font-family: Arial, Helvetica, sans-serif;">
        <table style="width: 100%; border-collapse: collapse;">
            <tr style="text-align: center;">
                <td style=" border: 1px solid black;">La presente constancia reposa en la base de dalos de GELEENCL y los resultados están conforme a la auditoría solicitada por el cliente</td>
            </tr>
            <tr>
                <table style="width: 100%; border-collapse: collapse;">
                    <tr style="text-align: center;">
                        <td style=" border: 1px solid black;">
                            CÓDIGO VERIFICADOR
                        </td>
                        <td style=" border: 1px solid black;">
                            ORDEN DE iNSPECCIÓN
                        </td>
                        <td style=" border: 1px solid black;">
                            FECHA PUBLICACIÓN
                        </td>
                        <td style=" border: 1px solid black;">
                            PÁGINA
                        </td>
                    </tr>
                    <tr style="text-align: center;">
                        <td style=" border: 1px solid black;">
                            SGSOl 520232850029671207202
                        </td>
                        <td style=" border: 1px solid black;">
                            OL28S-2967
                        </td>
                        <td style=" border: 1px solid black;">
                            {{$fecha_actual}}
                        </td>
                        <td style=" border: 1px solid black;">
                            <span class="pagenum"></span>

                        </td>
                    </tr>
                </table>
            </tr>
        
        </table>
        </div>

</body>
</html>