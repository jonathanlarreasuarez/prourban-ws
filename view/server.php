<?php

error_reporting("E_ALL");
ini_set("display_errors", 1);
if (isset($HTTP_RAW_POST_DATA)) {
    $input = $HTTP_RAW_POST_DATA;
} else {
    $input = implode("\r\n", file('php://input'));
}
require_once('../lib/nusoap.php');
require('../model/model.php');

$server = new soap_server;
$ns = $_SERVER["DOCUMENT_ROOT"] . "/prourban-ws/view/server.php";
$server->configurewsdl('ProurbanWSDL', $ns);

//	Obtiene un usuario
$server->register("Autenticacion",
			array('usuario' => 'xsd:string', 'clave' => 'xsd:string'),
			array('respuesta' => 'xsd:string'), $ns);

//	Lista de usuarios recibe usuario y contraseña para validar logueo
$server->register("CargaMenu",
			array('usuario_id' => 'xsd:string'),
			array('respuesta' => 'xsd:string'), $ns);


//	PROVEEDOR
//	Lista de proveedores
$server->register("ListaProveedores",
			array(),
			array('respuesta' => 'xsd:string'), $ns);

//	Crea proveedor
$server->register("InsertarProveedor",
			array('descripcion' => 'xsd:string', 'ruc' => 'xsd:string'),
			array('respuesta' => 'xsd:string'), $ns);

//	Modifica proveedor
$server->register("ModificarProveedor",
			array('id' => 'xsd:string', 'descripcion' => 'xsd:string', 'ruc' => 'xsd:string'),
			array('respuesta' => 'xsd:string'), $ns);

//	Elimina proveedor
$server->register("EliminarProveedor",
			array('id' => 'xsd:string'),
			array('respuesta' => 'xsd:string'), $ns);

//	Busca proveedor
$server->register("BuscarProveedor",
			array('id' => 'xsd:string'),
			array('respuesta' => 'xsd:string'), $ns);


//	CUENTAXPAGAR
//	Lista de cuentas por pagar
$server->register("ListaCuentasxpagar",
			array(),
			array('respuesta' => 'xsd:string'), $ns);

//	Busca cuenta por pagar
$server->register("BuscarCuentaxpagar",
			array('id' => 'xsd:string'),
			array('respuesta' => 'xsd:string'), $ns);

$server->register("InsertarCuentaxpagar",
			array('descripcion' => 'xsd:string', 'fecha' => 'xsd:string', 
				  'total' => 'xsd:string', 'numero_referencia' => 'xsd:string', 
				  'proveedor_id' => 'xsd:string'),
			array('respuesta' => 'xsd:string'), $ns);

//	Modifica cuenta por pagar
$server->register("ModificarCuentaxpagar",
			array('id' => 'xsd:string', 'descripcion' => 'xsd:string',
				'fecha' => 'xsd:string', 'total' => 'xsd:string',
				'numero_referencia' => 'xsd:string', 'proveedor_id' => 'xsd:string'),
			array('respuesta' => 'xsd:string'), $ns);

$server->register("EliminarCuentaxpagar",
			array('id' => 'xsd:string'),
			array('respuesta' => 'xsd:string'), $ns);


//	CUENTAXCOBRAR
$server->register("ListaCuentaxcobrar",
			array('nombrexBuscar' => 'xsd:string'),
			array('respuesta' => 'xsd:string'), $ns);

$server->register("BuscarCuentaxcobrar",
			array('id' => 'xsd:string'),
			array('respuesta' => 'xsd:string'), $ns);

//	FACTURA
$server->register("CabeceraFactura",
			array('id' => 'xsd:string'),
			array('respuesta' => 'xsd:string'), $ns);

$server->register("ListaCabeceraFactura",
			array(),
			array('respuesta' => 'xsd:string'), $ns);


//	RESERVA
//	Lista de pre-reservas
$server->register("ListaPreReservas",
			array(),
			array('respuesta' => 'xsd:string'), $ns);

$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
$server->service($HTTP_RAW_POST_DATA);

?>