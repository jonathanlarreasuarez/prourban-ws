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

//Seguridad---------------------------------------------

//	Obtiene un usuario
$server->register("Autenticacion",
			array('usuario' => 'xsd:string', 'clave' => 'xsd:string'),
			array('respuesta' => 'xsd:string'), $ns);

//	Lista de usuarios recibe usuario y contraseña para validar logueo

//	Lista de opciones
$server->register("ListaOpciones",
			array('rol_id' => 'xsd:string'),
			array('respuesta' => 'xsd:string'), $ns);

$server->register("ListaOpcionesRol",
      array('rol_id' => 'xsd:string'),
      array('respuesta' => 'xsd:string'), $ns);

      $server->register("getOpciones",
      array(),
      array('respuesta' => 'xsd:string'), $ns);

//	Busca rol
$server->register("BuscarRol",
			array('id' => 'xsd:string'),
			array('respuesta' => 'xsd:string'), $ns);

//	Modifica rol
$server->register("AsignarOpciones",
			array('id' => 'xsd:string', 'rol_id' => 'xsd:string', 'opcion_id' => 'xsd:string', 'estado' => 'xsd:string'),
			array('respuesta' => 'xsd:string'), $ns);

//	Crea rol
$server->register("InsertarRol",
			array('descripcion' => 'xsd:string'),
			array('respuesta' => 'xsd:string'), $ns);
//	Modifica rol
$server->register("ModificarRol",
			array('id' => 'xsd:string', 'descripcion' => 'xsd:string'),
			array('respuesta' => 'xsd:string'), $ns);
//	Elimina rol
$server->register("EliminarRol",
			array('id' => 'xsd:string'),
			array('respuesta' => 'xsd:string'), $ns);

//	Lista de Rol
$server->register("ListaRol",
			array(),
			array('respuesta' => 'xsd:string'), $ns);



$server->register("BuscarUsuario",
      array('id' => 'xsd:string'),
      array('respuesta' => 'xsd:string'), $ns);

//	Crea rol
$server->register("InsertarUsuario",
      array('nombre_usuario' => 'xsd:string','clave' => 'xsd:string'),
      array('respuesta' => 'xsd:string'), $ns);
      //	Modifica rol
$server->register("ModificarUsuario",
      array('id' => 'xsd:string', 'nombre_usuario' => 'xsd:string', 'clave' => 'xsd:string'),
      array('respuesta' => 'xsd:string'), $ns);
//	Elimina rol
$server->register("EliminarUsuario",
      array('id' => 'xsd:string'),
      array('respuesta' => 'xsd:string'), $ns);


$server->register("ListaUsuario",
      array(),
      array('respuesta' => 'xsd:string'), $ns);

$server->register("ListaPersona",
      array(),
	array('respuesta' => 'xsd:string'), $ns);
	
//Seguridad---------------------------------------------

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


//	Lista de deudas
$server->register("ListaDeudasUsuarios",
			array('nombrexBuscar' => 'xsd:string'),
			array('respuesta' => 'xsd:string'), $ns);

$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
$server->service($HTTP_RAW_POST_DATA);

?>
