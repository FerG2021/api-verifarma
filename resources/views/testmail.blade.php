<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Presupuestación - ModulAr SRL</title>
    </head>
    <body>
        <h4>Buenos días, <b>{{ $objEnviarMail ['nombreProveedor'] }}</b></h4>

        <p>Mediante la presente quien suscribe, ALVARO ERNESTO CARABAJAL. En carácter de Encargado de Compras de la Firma ModulAr SRL CUIT 30711555575 se dirige a usted y por su intermedio a la firma que representa; a los fines de solicitarle tenga bien participar de proceso de cotización de los siguientes materiales:</p>

        {{-- {{ $objEnviarMail ['productos'] }} --}}

        <table>
            <tr>
                <td><strong>Producto</strong></td>
                <td>-</td>
                <td><strong>Un. medida</strong></td>
                <td>-</td>
                <td><strong>Cantidad</strong></td>
                <td>-</td>
                <td><strong>Observaciones</strong></td>
            </tr>

            @foreach ($objEnviarMail ['productos'] as $producto)
                <tr>
                    <td>{{ $producto['nombre'] }}</td>
                    <td>-</td>
                    <td>{{ $producto['unidadMedida'] }}</td>
                    <td>-</td>
                    <td>{{ $producto['cantidad'] }}</td>
                    <td>-</td>
                    <td>{{ $producto['observaciones'] }}</td>
                </tr> 
            @endforeach
            
        </table>


        <p>Se solicita que dicha cotización indique:</p>
        <ul>
            <li>Precio</li>
            <li>Precio Pagado con Valores y plazo de los mismos.</li>
            <li>Precio pagado mediante transferencia bancaria.</li>
            <li>Disponibilidad de los productos.</li>
            <li>% de IVA del producto</li>
            <li>Descuentos o bonificaciones</li>
            <li>Especificar si los descuentos o bonificaciones son por compra total o si se hacen parciales se mantiene el descuento / Bonificación.</li>
            <li>Lugar de Entrega de los materiales (Si es puesto en obra o retirado de su depósito)</li>
            <li>Mantenimiento de la Cotización.</li>
            <li> En caso de tener algún producto alternativo al solicitado por favor expresarlo haciendo referencia al mismo</li>
        </ul>

        <p>Podrá realizar la carga de los precios de cada uno de los productos desde nuestra web. </p>
        
        {{-- <p></p> --}}

        <h3><b>Para ingresar deberá hacer <a href="{{env('APP_WEB')}}/login?user={{$objEnviarMail['mailProveedor']}}&password={{$objEnviarMail['contrasenaProveedor']}}&proveedorID={{$objEnviarMail['proveedorID']}}&presupuestacionID={{$objEnviarMail['presupuestacionID']}}&fechaLimiteCarga={{$objEnviarMail['fechaLimiteCarga']}}">click aquí</a></b></h3>

        <img src="data:image/jpeg;base64,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" />

        <h4>Atte.</h4>
        <h3>ALVARO ERNESTO CARABAJAL</h3>
        <h4>Oficina Compras</h4>

        <h3><b>Güemes Nº70 | (4200) Santiago del Estero, Argentina</b></h3>
        <h3><b>Cel. 385-5953463</b></h3>
        <h3><b>Sitio web: <a href="http://modularsrl.com.ar">modularsrl.com.ar</a> </b></h3>
        {{-- <p>Esta empresa cuenta con servicio de auditoría de compras. Ante cualquier inquietud o insatisfacción, por favor comuníquese con Nicolás Bernardi al 011-53231155.</p> --}}
    </body>
</html>

{{-- Hola {{ $data }}, los correos con Gmail funcionan --}}