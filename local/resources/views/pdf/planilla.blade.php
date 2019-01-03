<!DOCTYPE html>
<html lang="en">
    <head>
        <title></title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body>

        <img src="{!! public_path().'/img/zonfipca.png'!!}" width="50px;" height="50px;">

        @foreach($datos as $dato)

            <table style="border: 1px solid; border-collapse: collapse; width: 100%;">
                
                <tr style="border: 1px solid;">
                    <td colspan="6" style="border: 1px solid; text-align:center;"> 
                        Datos Personales
                    </td>
                </tr>

                <tr style="border: 1px solid;">
                    <td colspan="2" style="border: 1px solid;">
                        Apellidos:
                    </td>
                    <td colspan="2" style="border: 1px solid;">
                        Nombres:
                    </td>
                    <td colspan="2" style="border: 1px solid;">
                        C.I:
                    </td>
                </tr>

                <tr style="border: 1px solid;">
                    <td colspan="1" style="border: 1px solid;">
                        Fecha de nac:
                    </td>
                    <td colspan="1" style="border: 1px solid;">
                        RIF:
                    </td>
                    <td colspan="4" style="border: 1px solid;">
                        Lugar fecha de nacimiento:
                    </td>
                </tr> 

                <tr style="border: 1px solid;">
                    <td colspan="5" style="border: 1px solid;">
                        Direccion de habitacion:
                    </td>
                    <td colspan="1" style="border: 1px solid;">
                        telefono HAB:
                    </td>
                </tr>  

                <tr style="border: 1px solid;">
                    <td colspan="5" style="border: 1px solid;">
                        
                    </td>
                    <td colspan="1" style="border: 1px solid;">
                        telefono movil:
                    </td>
                </tr>

                <tr style="border: 1px solid;">
                    <td colspan="2" style="border: 1px solid;">
                        Estado:
                    </td>
                    <td colspan="2" style="border: 1px solid;">
                        Municipio:
                    </td>
                    <td colspan="2" style="border: 1px solid;">
                        Parroquia:
                    </td>
                </tr>       

                <tr style="border: 1px solid;">
                    <td colspan="6" style="border: 1px solid;">
                        Direccion de emergencia:
                    </td>
                </tr>

                <tr style="border: 1px solid;">
                    <td colspan="2" style="border: 1px solid;">
                        Estado civil:
                    </td>
                    <td colspan="2" style="border: 1px solid;">
                        Nacionalidad:
                    </td>
                    <td colspan="2" style="border: 1px solid;">
                        Correo Electronico:
                    </td>
                </tr>

                <tr style="border: 1px solid;">
                    <td colspan="2" style="border: 1px solid;">
                        Profesion:
                    </td>
                    <td colspan="4" style="border: 1px solid;">
                        Nivel de instruccion:
                        ninguno |&nbsp;&nbsp;&nbsp;  | &nbsp;&nbsp;&nbsp; primaria |&nbsp;&nbsp;&nbsp;  | &nbsp;&nbsp;&nbsp; bachiller |&nbsp;&nbsp;&nbsp;  |
                    </td>
                </tr> 

                <tr style="border: 1px solid;">
                    <td colspan="6" style="border: 1px solid;">
                        tecnico superior |&nbsp;&nbsp;&nbsp;  |&nbsp;&nbsp; universitario |&nbsp;&nbsp;&nbsp;  |&nbsp;&nbsp; especializacion |&nbsp;&nbsp;&nbsp;  | &nbsp;&nbsp; maestria |&nbsp;&nbsp;&nbsp;  | &nbsp;&nbsp; doctorado |&nbsp;&nbsp;&nbsp;  | &nbsp;&nbsp; phd |&nbsp;&nbsp;&nbsp;  |
                    </td>
                </tr>     

            </table>
        @endforeach

    </body>
</html>