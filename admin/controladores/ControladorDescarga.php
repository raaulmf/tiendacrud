<?php

// Incluimos los ficheros que ncesitamos
require_once $_SERVER['DOCUMENT_ROOT'] . "/tienda/admin/dirs.php";
require_once CONTROLLER_PATH . "ControladorProducto.php";
require_once MODEL_PATH . "Producto.php";
require_once VENDOR_PATH . "autoload.php";
use Spipu\Html2Pdf\HTML2PDF;


/**
 * Controlador de descargas
 */
class ControladorDescarga
{

    // Configuración del servidor
    private $fichero;

    // Variable instancia para Singleton
    static private $instancia = null;

    // constructor--> Private por el patrón Singleton
    private function __construct()
    {
        //echo "Conector creado";
    }

    /**
     * Patrón Singleton. Ontiene una instancia del Controlador de Descargas
     * @return instancia de conexion
     */
    public static function getControlador()
    {
        if (self::$instancia == null) {
            self::$instancia = new ControladorDescarga();
        }
        return self::$instancia;
    }

    public function descargarXMLProd()
    {
        $this->fichero = "productos.xml";
        $lista = $controlador = ControladorProducto::getControlador();
        $lista = $controlador->listarProductos("", "");
        $doc = new DOMDocument('1.0', 'UTF-8');
        $productos = $doc->createElement('productos');

        foreach ($lista as $a) {
            $producto = $doc->createElement('producto');
            
            $producto->appendChild($doc->createElement('nombre', $a->getNombre()));
            $producto->appendChild($doc->createElement('tipo', $a->getTipo()));
            $producto->appendChild($doc->createElement('marca', $a->getMarca()));
            $producto->appendChild($doc->createElement('precio', $a->getPrecio()));
            $producto->appendChild($doc->createElement('unidades', $a->getUnidades()));
            $producto->appendChild($doc->createElement('imagen', $a->getImagen()));

            //Insertamos
            $productos->appendChild($producto);
        }

        $doc->appendChild($productos);
        header('Content-type: application/xml');
        echo $doc->saveXML();

        exit;
    }
   
    public function descargarPDFProd(){
        $spro ='<h2 class="pull-left">Catálogo</h2>';
        $lista = $controlador = ControladorProducto::getControlador();
        $lista = $controlador->listarProductos("", "");
        if (!is_null($lista) && count($lista) > 0) {
            $spro.="<table class='table table-bordered table-striped'>";
            $spro.="<thead>";
            $spro.="<tr>";
            $spro.="<th>Nombre</th>";
            $spro.="<th>Tipo</th>";
            $spro.="<th>Marca</th>";
            //$sal.="<th>Contraseña</th>";
            $spro.="<th>Precio</th>";
            $spro.="<th>Unidades</th>";
            $spro.="<th>Imagen</th>";
            $spro.="</tr>";
            $spro.="</thead>";
            $spro.="<tbody>";
            // Recorremos los registros encontrados
            foreach ($lista as $producto) {
                // Pintamos cada fila
                $spro.="<tr>";
                $spro.="<td>" . $producto->getNombre() . "</td>";
                $spro.="<td>" . $producto->getTipo() . "</td>";
                $spro.="<td>" . $producto->getMarca() . "</td>";
                //$sal.="<td>" . str_repeat("*",strlen($alumno->getPassword())) . "</td>";
                $spro.="<td>" . $producto->getPrecio() . "</td>";
                $spro.="<td>" . $producto->getUnidades() . "</td>";
                // Para sacar una imagen hay que decirle el directprio real donde está
                $spro.="<td><img src='".$_SERVER['DOCUMENT_ROOT'] . "/tienda/admin/producto/imagen_producto/".$producto->getImagen()."'  style='max-width: 12mm; max-height: 12mm'></td>";
                $spro.="</tr>";
            }
            $spro.="</tbody>";
            $spro.="</table>";
        } else {
            // Si no hay nada seleccionado
            $spro.="<p class='lead'><em>No se ha encontrado productos.</em></p>";
        }
        //https://github.com/spipu/html2pdf/blob/master/doc/basic.md
        $pdf=new HTML2PDF('R','A5','es','true','UTF-8');
        $pdf->writeHTML($spro);
        $pdf->output('listado.pdf');

    }
}
