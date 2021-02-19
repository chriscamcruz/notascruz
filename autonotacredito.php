<?php
//Se importan los archivos necesarios//
///////////////////////////////////////
ini_set('session.cache_limiter', '');
header('Expires: Thu, 19 Nov 1981 08:52:00 GMT');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: no-cache');
///////////////////////////////////////
//Se incluyen los archivos necesarios//
///////////////////////////////////////
include('adodb/adodb.inc.php');
include('adodb/adodb-pager.inc.php');
include('lee_base.php');
////////////////////////////
//Se configura la conexión//
////////////////////////////
putenv("ORACLE_HOME=/u01/app/oracle/product/11.2.0/xe/");
define ('ADODB_ASSOC_CASE' ,0);
define ('ADODB_FETCH_ASSOC',0);
$ADODB_ANSI_PADDING_OFF=1;
$base   = "cedides";
$motor  = "Oci8po";
$cstr   = "(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=192.168.0.45)(PORT=1521)) (CONNECT_DATA=(SID=CEDIDES)))";
$db     = ADONewConnection($motor);
$usu    = "informa";
$pass   = "inf130106";
$db->Connect($cstr,$usu,$pass);
////////////////////////

include ('facturan/pdf.php');
include ('trae_impre.php');
include ('trae_planti.php');
require('facturan/fpdf16/fpdf.php');
include ('webservices/NotaCreditoP.php');
require('barcodegen/class/BCGDrawing.php');
require('barcodegen/class/BCGgs1128.barcode.php');
include ('chequeo.php');

///////////////////////////
//Se leen todos los datos//
///////////////////////////

//ejecuta_query("delete from walista where transaccion = 'CTE' and t_pedido not in ('PET', 'PES', 'PEQ') and fecha >= to_date('01/02/2021', 'DD/MM/YYYY')");

$mat_nc = lee_todo("select a.numero from movih a left join movih_centro b on a.transaccion = b.transaccion and a.numero = b.numero left join logs_fe c on a.transaccion = c.transaccion and a.numero = c.numero where a.transaccion = 'NC' and a.numero > 19646 and b.cufe is null and rownum <= 30 order by a.numero desc ");

//////////////////////////////
       
    for($j=0; $j<count($mat_nc); $j++)
    {
        $consumo = generarlayout_nc( 'NC', $mat_nc[$j]['numero'], $mat_nc[$j]['numero'] );            
    }  

    if(2<1)
    {

        $asunto = "Error de envio en Notas Credito";
        $cuerpo = "";

        $enviar_correo = "call mailp('servidor.comersantander@comersantander.com','ingeniero.soporte@comersantander.com','','$asunto','$cuerpo')";
        ejecuta_query($enviar_correo);
    }
?>
