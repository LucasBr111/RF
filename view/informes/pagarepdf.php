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

$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetMargins(20, 20, 20);

$duenho_nombre = strtoupper(DUENHO_SISTEMA);
$duenho_ci = CI_DUENHO;

// Iteramos sobre cada cuota para generar un pagaré individual
foreach($pagares as $p) {
    $pdf->AddPage();
    
    // Preparación de datos dinámicos
    $monto_letras = strtoupper(NumeroALetras::convertir($p->monto, ''));
    $monto_num = number_format($p->monto, 0, ',', '.');
    $vencimiento = date("d/m/Y", strtotime($p->fecha_vencimiento));
    $emision = date("d/m/Y", strtotime($p->fecha_emision));
    $num_cuota = str_pad($p->numero_cuota, 2, "0", STR_PAD_LEFT);
    $total_cuotas = str_pad($p->cant_cuotas, 2, "0", STR_PAD_LEFT);

    // Lógica dinámica para Co-deudor y Cónyuges
    $nombre_conyuge = !empty($p->conyuge_nombre) ? strtoupper($p->conyuge_nombre) : "***************************";
    $ci_conyuge = !empty($p->conyuge_ci) ? $p->conyuge_ci : "***********";
    
    $nombre_codeudor = !empty($p->codeudor_nombre) ? strtoupper($p->codeudor_nombre) : "**********************************";
    $ci_codeudor = !empty($p->codeudor_ci) ? $p->codeudor_ci : "***********";

    $html = <<<EOF
    <style>
        .documento { font-family: courier; font-size: 8.5pt; line-height: 1.5; }
        .titulo { text-align: center; font-size: 14pt; font-weight: bold; }
        .tabla-cabecera { margin-bottom: 10px; }
        .texto-legal { text-align: justify; }
        .datos-cliente { margin-top: 15px; }
    </style>

    <div class="documento">
        <div class="titulo">PAGARE A LA ORDEN</div>
        <br>
        <table width="100%" class="tabla-cabecera">
            <tr>
                <td width="50%">Pagaré N°: {$num_cuota}/{$total_cuotas}</td>
                <td width="50%" align="right">Por Gs.: {$monto_num}.-</td>
            </tr>
            <tr>
                <td width="50%">Fecha de Emisión: {$emision}</td>
                <td width="50%" align="right">Fecha de Vencimiento: {$vencimiento}</td>
            </tr>
        </table>
<br>
        <div class="texto-legal" style="text-align: justify">
            El día de vencimiento de este documento, pagaré(mos) a la orden, solidariamente, libre de gastos y sin protesto alguno a <b>{$duenho_nombre}</b> en su domicilio de CIUDAD DEL ESTE la suma de guaraníes <b>{$monto_letras}</b>, por igual valor recibido a mi(nuestra) satisfacción, comprometiendo mis(nuestros) bienes propios, gananciales y/o reservados\n
            La falta de pago de este pagaré en fecha de su vencimiento, me(nos) constituirá en mora de pleno derecho y producirá el decaimiento de los plazos de todas mis(nuestras) demás obligaciones para con el acreedor tomándolas íntegramente exigibles sin necesidad de protesto, ni requerimiento extra judicial o judicial alguno, en cuyo caso la totalidad de la deuda devengará un interés moratorio del 3,0% mensual y un interés punitorio del 3,0% mensual desde el día del vencimiento hasta el del efectivo pago.
            El simple vencimiento establecerá la mora, autorizando a la consulta como a su inclusión en la base de datos de Informconf conforme a lo establecido en la Ley 1682, como tambien para que se pueda proveer la información a terceros interesados. A todos los efectos legales, me(nos) someto(emos) a la jurisdicción de los juzgados y tribunales de la ciudad de TODO EL PAIS........., constituyéndome(nos) con domicilio especial en ALTO PARANA...................................................\n

        </div>

        <table width="100%" class="datos-cliente" cellpadding="2">
            <tr>
                <td width="55%">DEUDOR: <b>{$p->cliente_nombre}</b></td>
                <td width="45%">CONYUGE: {$nombre_conyuge}</td>
            </tr>
            <tr>
                <td width="55%">Documento/Identidad N°: {$p->cliente_ci}</td>
                <td width="45%">Documento/Identidad N°: {$ci_conyuge}</td>
            </tr>
        </table>

        <br><br>
        <table width="100%">
            <tr>
                <td width="50%">FIRMA: ..................................</td>
                <td width="50%">FIRMA: ..................................</td>
            </tr>
        </table>

        <br><br>
        <table width="100%" cellpadding="2">
            <tr>
                <td width="55%">CO DEUDOR: <b>{$nombre_codeudor}</b></td>
                <td width="45%">CONYUGE: ***************************</td>
            </tr>
            <tr>
                <td width="55%">Documento/Identidad N°: {$ci_codeudor}</td>
                <td width="45%">Documento/Identidad N°: ***********</td>
            </tr>
        </table>
    </div>
EOF;

    $pdf->writeHTML($html, true, false, true, false, '');
}
ob_end_clean();
$pdf->Output("Pagares_Venta_{$id_venta}.pdf", 'I');