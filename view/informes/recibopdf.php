<?php 
class NumeroALetras
{
    private static $UNIDADES = [
        '',
        'UN ',
        'DOS ',
        'TRES ',
        'CUATRO ',
        'CINCO ',
        'SEIS ',
        'SIETE ',
        'OCHO ',
        'NUEVE ',
        'DIEZ ',
        'ONCE ',
        'DOCE ',
        'TRECE ',
        'CATORCE ',
        'QUINCE ',
        'DIECISEIS ',
        'DIECISIETE ',
        'DIECIOCHO ',
        'DIECINUEVE ',
        'VEINTE '
    ];

    private static $DECENAS = [
        'VENTI',
        'TREINTA ',
        'CUARENTA ',
        'CINCUENTA ',
        'SESENTA ',
        'SETENTA ',
        'OCHENTA ',
        'NOVENTA ',
        'CIEN '
    ];

    private static $CENTENAS = [
        'CIENTO ',
        'DOSCIENTOS ',
        'TRESCIENTOS ',
        'CUATROCIENTOS ',
        'QUINIENTOS ',
        'SEISCIENTOS ',
        'SETECIENTOS ',
        'OCHOCIENTOS ',
        'NOVECIENTOS '
    ];

    public static function convertir($number, $moneda = '', $centimos = '', $forzarCentimos = false)
    {
        $converted = '';
        $decimales = '';

        if (($number < 0) || ($number > 999999999)) {
            return 'No es posible convertir el numero a letras';
        }

        $div_decimales = explode('.',$number);

        if(count($div_decimales) > 1){
            $number = $div_decimales[0];
            $decNumberStr = (string) $div_decimales[1];
            if(strlen($decNumberStr) == 2){
                $decNumberStrFill = str_pad($decNumberStr, 9, '0', STR_PAD_LEFT);
                $decCientos = substr($decNumberStrFill, 6);
                $decimales = self::convertGroup($decCientos);
            }
        }
        else if (count($div_decimales) == 1 && $forzarCentimos){
            $decimales = 'CERO ';
        }

        $numberStr = (string) $number;
        $numberStrFill = str_pad($numberStr, 9, '0', STR_PAD_LEFT);
        $millones = substr($numberStrFill, 0, 3);
        $miles = substr($numberStrFill, 3, 3);
        $cientos = substr($numberStrFill, 6);

        if (intval($millones) > 0) {
            if ($millones == '001') {
                $converted .= 'UN MILLON ';
            } else if (intval($millones) > 0) {
                $converted .= sprintf('%sMILLONES ', self::convertGroup($millones));
            }
        }

        if (intval($miles) > 0) {
            if ($miles == '001') {
                $converted .= 'MIL ';
            } else if (intval($miles) > 0) {
                $converted .= sprintf('%sMIL ', self::convertGroup($miles));
            }
        }

        if (intval($cientos) > 0) {
            if ($cientos == '001') {
                $converted .= 'UN ';
            } else if (intval($cientos) > 0) {
                $converted .= sprintf('%s ', self::convertGroup($cientos));
            }
        }

        if(empty($decimales)){
            $valor_convertido = $converted . strtoupper($moneda);
        } else {
            $valor_convertido = $converted . strtoupper($moneda) . ' CON ' . $decimales . ' ' . strtoupper($centimos);
        }

        return $valor_convertido;
    }

    private static function convertGroup($n)
    {
        $output = '';

        if ($n == '100') {
            $output = "CIEN ";
        } else if ($n[0] !== '0') {
            $output = self::$CENTENAS[$n[0] - 1];   
        }

        $k = intval(substr($n,1));

        if ($k <= 20) {
            $output .= self::$UNIDADES[$k];
        } else {
            if(($k > 30) && ($n[2] !== '0')) {
                $output .= sprintf('%sY %s', self::$DECENAS[intval($n[1]) - 2], self::$UNIDADES[intval($n[2])]);
            } else {
                $output .= sprintf('%s%s', self::$DECENAS[intval($n[1]) - 2], self::$UNIDADES[intval($n[2])]);
            }
        }

        return $output;
    }
}
// Esto busca la carpeta plugins desde la raíz de tu proyecto
require_once("./assets/plugins/tcpdf2/tcpdf.php");

// Tamaño personalizado: 210mm ancho x 100mm alto (estilo talonario)
$pdf = new TCPDF('L', 'mm', array(210, 100), true, 'UTF-8', false);

$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
// Ajustamos un poco los márgenes (Izquierda, Arriba, Derecha)
$pdf->SetMargins(10, 8, 10); 
$pdf->SetAutoPageBreak(TRUE, 5); // Evita el salto de página si hay 5mm de sobra
$pdf->AddPage();

// Formateo de datos
$monto_letras = NumeroALetras::convertir($v->monto_cuota, 'Guaraníes');
$monto_num = number_format($v->monto_cuota, 0, ',', '.');

// Fecha en español
$meses = ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
$fecha_hoy = date("d") . " de " . $meses[date("n") - 1] . " del " . date("Y");

$html = <<<EOF
<style>
    /* CSS simplificado y optimizado para el motor de TCPDF */
    .recibo-wrapper {
        border: 2px solid #2c3e50;
        background-color: #fafafa;
    }
    .titulo-central {
        font-size: 14px;
        font-weight: bold;
        color: #2c3e50;
        text-align: center;
    }
    .monto-box {
        background-color: #ecf0f1;
        color: #2c3e50;
        font-size: 14px;
        font-weight: bold;
        text-align: center;
        border: 1px solid #bdc3c7;
    }
    .num-recibo {
        font-size: 14px;
        font-weight: bold;
        color: #c0392b; /* Color rojo sutil para el N° de recibo */
        text-align: right;
    }
    .fecha {
        font-size: 10px;
        color: #555;
        text-align: right;
    }
    .label {
        font-size: 11px;
        font-weight: bold;
        color: #333;
    }
    .content-text {
        font-size: 12px;
        color: #111;
        border-bottom: 1px dashed #7f8c8d; /* Línea punteada para los datos */
    }
    .firma-linea {
        border-top: 1px solid #333;
        font-size: 10px;
        text-align: center;
        color: #555;
    }
</style>

<table width="100%" class="recibo-wrapper" cellpadding="5">
    <tr>
        <td>
            <table width="100%" cellpadding="2">
                <tr>
                    <td width="30%"><img src="assets/img/logo.png" width="110"></td>
                    <td width="40%" class="titulo-central"><br><br>RECIBO OFICIAL</td>
                    <td width="30%">
                        <table width="100%">
                            <tr><td class="num-recibo">N° {$v->id_cuota}</td></tr>
                            <tr><td class="monto-box">Gs. {$monto_num}</td></tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="fecha">Ciudad del Este, {$fecha_hoy}</td>
                </tr>
            </table>

            <table width="100%" cellpadding="6">
                <tr>
                    <td width="20%" class="label">Recibí(mos) de:</td>
                    <td width="80%" class="content-text">{$v->cliente_nombre}</td>
                </tr>
                <tr>
                    <td width="20%" class="label">La suma de:</td>
                    <td width="80%" class="content-text">{$monto_letras}</td>
                </tr>
                <tr>
                    <td width="20%" class="label">En concepto de:</td>
                    <td width="80%" class="content-text">Pago de Cuota {$v->numero}/{$v->cant_cuotas} por el Vehículo: {$v->modelo_nombre} ({$v->anho})</td>
                </tr>
            </table>

           

            <table width="100%" cellpadding="0">
                <tr>
                    <td width="65%"></td>
                    <td width="35%" align="center">
                        <br><br><br><br> <table width="100%">
                            <tr><td class="firma-linea">Firma y Sello Autorizado</td></tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
EOF;

// Escribimos el HTML en el PDF
$pdf->writeHTML($html, true, false, false, false, '');

ob_end_clean();
$pdf->Output("Recibo_{$v->id_cuota}.pdf", 'I');