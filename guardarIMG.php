<html>
<head>
<title>Procesa una subida de archivos </title>
<meta charset="UTF-8">
</head>
<?php
// se incluyen los códigos de error que produce la subida de archivos en PHPP
// Posibles errores de subida
$codigosErrorSubida= [ 
    0 => 'Subida correcta',
    1 => 'El tamaño del archivo excede el admitido por el servidor',  // directiva upload_max_filesize en php.ini
    2 => 'El tamaño del archivo excede el admitido por el cliente',  // directiva MAX_FILE_SIZE en el formulario HTML
    3 => 'El archivo no se pudo subir completamente',
    4 => 'No se seleccionó ningún archivo para ser subido',
    6 => 'No existe un directorio temporal donde subir el archivo',
    7 => 'No se pudo guardar el archivo en disco',  // permisos
    8 => 'Una extensión PHP evito la subida del archivo'  // extensión PHP
]; 
$mensaje = '';

$info = new SplFileInfo($_FILES['archivo1']['name']);//saco la extension
$extension=($info->getExtension());
echo $extension;
// si no se reciben el directorio de alojamiento y el archivo, se descarta el proceso
if ((! isset($_FILES['archivo1']['name'])) or (! isset($_REQUEST['directorio']))) {
    $mensaje .= 'ERROR: No se indicó el archivo y/o no se indicó el directorio';
} else 
{   if($_FILES['archivo1']['size']<200000 and ($_FILES['archivo1']['size'] + $_FILES['archivo2']['size'])<300000 ){
        
        if($extension=="png" or $extension=="jpg"){
                      
               
       if(file_exists($_REQUEST['directorio']."/".$_FILES['archivo1']['name'])){
            echo "El archivo no se puede subir, ya existe uno.";
        }
        else{
        // se reciben el directorio de alojamiento y el archivo
    $directorioSubida = $_REQUEST['directorio']; // debe permitir la escritua para Apache
    // Información sobre el archivo subido
    $nombreFichero   =   $_FILES['archivo1']['name'];
    $tipoFichero     =   $_FILES['archivo1']['type'];
    $tamanioFichero  =   $_FILES['archivo1']['size'];
    $temporalFichero =   $_FILES['archivo1']['tmp_name'];
    $errorFichero    =   $_FILES['archivo1']['error'];

    $mensaje .= 'Intentando subir el archivo: ' . ' <br />';
    $mensaje .= "- Nombre: $nombreFichero" . ' <br />';
    $mensaje .= '- Tamaño: ' . ($tamanioFichero / 1024) . ' KB <br />';
    $mensaje .= "- Tipo: $tipoFichero" . ' <br />' ;
    $mensaje .= "- Nombre archivo temporal: $temporalFichero" . ' <br />';
    $mensaje .= "- Código de estado: $errorFichero" . ' <br />';
    
    $mensaje .= '<br />RESULTADO<br />';

    // Obtengo el código de error de la operación, 0 si todo ha ido bien
    if ($errorFichero > 0) {
        $mensaje .= "Se a producido el error: $errorFichero:" 
                     . $codigosErrorSubida[$errorFichero] . ' <br />';
    } else { // subida correcta del temporal
        // si es un directorio y tengo permisos     
         if ( is_dir($directorioSubida) && is_writable ($directorioSubida)) { 
            //Intento mover el archivo temporal al directorio indicado
            if (move_uploaded_file($temporalFichero,  $directorioSubida .'/'. $nombreFichero) == true) {
                $mensaje .= 'Archivo guardado en: ' . $directorioSubida .'/'. $nombreFichero . ' <br />';
            } else {
                $mensaje .= 'ERROR: Archivo no guardado correctamente <br />';
            }
        } else {
            $mensaje .= 'ERROR: No es un directorio correcto o no se tiene permiso de escritura <br />';
        }
    }
}
}
        else{
            echo "Extension incorrecta";
        }
}
else{
    echo "Se ha superado el tamaño maximo";
}
    }
?>


<body>
<?php echo $mensaje; ?>
<br />
	<a href="guardarIMG.html">Volver a la página de subida</a>
</body>
</html>
