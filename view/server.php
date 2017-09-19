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



$server->register("BuscarUsuarioSeg",
array('id' => 'xsd:string'),
array('respuesta' => 'xsd:string'), $ns);

//	Crea rol
$server->register("InsertarUsuarioSeg",
array('nombre_usuario' => 'xsd:string','clave' => 'xsd:string'),
array('respuesta' => 'xsd:string'), $ns);
//	Modifica rol
$server->register("ModificarUsuarioSeg",
array('id' => 'xsd:string', 'nombre_usuario' => 'xsd:string', 'clave' => 'xsd:string'),
array('respuesta' => 'xsd:string'), $ns);
//	Elimina rol
$server->register("EliminarUsuarioSeg",
array('id' => 'xsd:string'),
array('respuesta' => 'xsd:string'), $ns);


$server->register("ListaUsuario",
array(),
array('respuesta' => 'xsd:string'), $ns);

$server->register("ListaPersona",
array(),
array('respuesta' => 'xsd:string'), $ns);

//Seguridad---------------------------------------------


//	PROVEEDOR
$server->register("ListaProveedores",
			array(),
			array('respuesta' => 'xsd:string'), $ns);

$server->register("InsertarProveedor",
			array('descripcion' => 'xsd:string', 'ruc' => 'xsd:string'),
			array('respuesta' => 'xsd:string'), $ns);

$server->register("ModificarProveedor",
			array('id' => 'xsd:string', 'descripcion' => 'xsd:string', 'ruc' => 'xsd:string'),
			array('respuesta' => 'xsd:string'), $ns);

$server->register("EliminarProveedor",
			array('id' => 'xsd:string'),
			array('respuesta' => 'xsd:string'), $ns);

$server->register("BuscarProveedor",
			array('id' => 'xsd:string'),
			array('respuesta' => 'xsd:string'), $ns);


//	CUENTAXPAGAR
$server->register("ListaCuentasxpagar",
			array(),
			array('respuesta' => 'xsd:string'), $ns);

$server->register("BuscarCuentaxpagar",
			array('id' => 'xsd:string'),
			array('respuesta' => 'xsd:string'), $ns);

$server->register("InsertarCuentaxpagar",
			array('descripcion' => 'xsd:string', 'fecha' => 'xsd:string',
				  'total' => 'xsd:string', 'numero_referencia' => 'xsd:string',
				  'proveedor_id' => 'xsd:string'),
			array('respuesta' => 'xsd:string'), $ns);

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

$server->register("ModificarCuentaxcobrar",
			array('id' => 'xsd:string'),
			array('respuesta' => 'xsd:string'), $ns);


//	FACTURA
$server->register("CabeceraFactura",
			array('id' => 'xsd:string'),
			array('respuesta' => 'xsd:string'), $ns);

$server->register("DetalleFactura",
			array('id' => 'xsd:string'),
			array('respuesta' => 'xsd:string'), $ns);

$server->register("ListaCabeceraFactura",
			array(),
			array('respuesta' => 'xsd:string'), $ns);

$server->register("PagarFactura",
			array('id' => 'xsd:string'),
			array('respuesta' => 'xsd:string'), $ns);

$server->register("GuardarCabeceraFactura",
			array('fecha_factura' => 'xsd:string', 'numero_factura' => 'xsd:string', 'subtotal' => 'xsd:string', 'iva' => 'xsd:string', 'total' => 'xsd:string', 'formapago_id' => 'xsd:string', 'usuario_id' => 'xsd:string' ),
			array('respuesta' => 'xsd:string'), $ns);

$server->register("GuardarDetalleFactura",
			array('valor' => 'xsd:string', 'conceptopago_id' => 'xsd:string', 'factura_id' => 'xsd:string'),
			array('respuesta' => 'xsd:string'), $ns);

$server->register("GuardarAsiento",
			array('fecha' => 'xsd:string', 'valor' => 'xsd:string', 'conceptoPago' => 'xsd:string', 'factura_id' => 'xsd:string'),
			array('respuesta' => 'xsd:string'), $ns);

//	RESERVA
$server->register("ListaPreReservas",
			array(),
			array('respuesta' => 'xsd:string'), $ns);

$server->register("PagarReserva",
			array('id' => 'xsd:string'),
			array('respuesta' => 'xsd:string'), $ns);

$server->register("BuscarPreReserva",
			array('id' => 'xsd:string'),
			array('respuesta' => 'xsd:string'), $ns);

// ASIENTOS
$server->register("ListaCuentas",
			array(),
			array('respuesta' => 'xsd:string'), $ns);

$server->register("ListaAsientoDebito",
			array(),
			array('respuesta' => 'xsd:string'), $ns);

$server->register("ListaAsientoCredito",
			array(),
			array('respuesta' => 'xsd:string'), $ns);

//Andres
$server->register("ListaUsuarios",
			array(),
			array('respuesta' => 'xsd:string'), $ns);

$server->register("ListaUsuariose",
			array(),
			array('respuesta' => 'xsd:string'), $ns);

$server->register("InsertarUsuario",
			array('cedula' => 'xsd:string', 'primer_nombre' => 'xsd:string', 'segundo_nombre' => 'xsd:string', 'primer_apellido' => 'xsd:string', 'segundo_apellido' => 'xsd:string', 'telefono' => 'xsd:string', 'correo' => 'xsd:string', 'nombre_usuario' => 'xsd:string', 'clave' => 'xsd:string'),
			array('respuesta' => 'xsd:string'), $ns);

$server->register("BuscarUsuario",
			array('id' => 'xsd:string'),
			array('respuesta' => 'xsd:string'), $ns);

$server->register("ModificarUsuario",
			array('id' => 'xsd:string', 'descripcion' => 'xsd:string', 'valor' => 'xsd:string', 'estado' => 'xsd:string'),
			array('respuesta' => 'xsd:string'), $ns);

//	Activa parámetros
$server->register("ActivarUsuario",
			array('id' => 'xsd:string'),
			array('respuesta' => 'xsd:string'), $ns);

$server->register("EliminarUsuario",
			array('id' => 'xsd:string'),
			array('respuesta' => 'xsd:string'), $ns);





//Areas
$server->register("ListaAreas",
			array(),
			array('respuesta' => 'xsd:string'), $ns);

$server->register("ListaAreasInactivas",
			array(),
			array('respuesta' => 'xsd:string'), $ns);

$server->register("InsertarArea",
			array('descripcion' => 'xsd:string', 'valor' => 'xsd:string', 'estado' => 'xsd:string'),
			array('respuesta' => 'xsd:string'), $ns);

$server->register("BuscarArea",
			array('id' => 'xsd:string'),
			array('respuesta' => 'xsd:string'), $ns);

$server->register("ModificarArea",
			array('id' => 'xsd:string', 'descripcion' => 'xsd:string', 'valor' => 'xsd:string', 'estado' => 'xsd:string'),
			array('respuesta' => 'xsd:string'), $ns);

$server->register("EliminarArea",
			array('id' => 'xsd:string'),
			array('respuesta' => 'xsd:string'), $ns);

$server->register("CambiarEstadoArea",
			array('id' => 'xsd:string'),
			array('respuesta' => 'xsd:string'), $ns);



//Inmuebles

$server->register("ListaInmuebles",
			array(),
			array('respuesta' => 'xsd:string'), $ns);

$server->register("InsertarInmueble",
			array('manzana' => 'xsd:string','numero_villa' => 'xsd:string','numero_cuartos' => 'xsd:string','numero_banios' => 'xsd:string','usuario_id' => 'xsd:string'),
			array('respuesta' => 'xsd:string'), $ns);

$server->register("ModificarInmueble",
			array('id' => 'xsd:string','manzana' => 'xsd:string','numero_villa' => 'xsd:string','numero_cuartos' => 'xsd:string','numero_banios' => 'xsd:string','usuario_id' => 'xsd:string'),
			array('respuesta' => 'xsd:string'), $ns);

$server->register("EliminarInmueble",
			array('id' => 'xsd:string'),
			array('respuesta' => 'xsd:string'), $ns);

$server->register("BuscarInmueble",
			array('id' => 'xsd:string'),
			array('respuesta' => 'xsd:string'), $ns);

$server->register("CambiarEstadoInmueble",
			array('id' => 'xsd:string','estado' => 'xsd:string'),
			array('respuesta' => 'xsd:string'), $ns);



//Horario Mantenimiento

$server->register("ListaHorariosmantenimiento",
			array(),
			array('respuesta' => 'xsd:string'), $ns);

$server->register("InsertarHorariosmantenimiento",
			array('dias' => 'xsd:string','desde' => 'xsd:string','hasta' => 'xsd:string','area' => 'xsd:string','estado' => 'xsd:string'),
			array('respuesta' => 'xsd:string'), $ns);

$server->register("ModificarHorariosmantenimiento",
                        array('id' => 'xsd:string','dias' => 'xsd:string','desde' => 'xsd:string','hasta' => 'xsd:string','area' => 'xsd:string','estado' => 'xsd:string'),
			array('respuesta' => 'xsd:string'), $ns);

$server->register("EliminarHorariosmantenimiento",
			array('id' => 'xsd:string'),
			array('respuesta' => 'xsd:string'), $ns);

$server->register("BuscarHorariosmantenimiento",
			array('id' => 'xsd:string'),
			array('respuesta' => 'xsd:string'), $ns);

$server->register("CambiarEstadoHorariosmantenimiento",
			array('id' => 'xsd:string','estado' => 'xsd:string'),
			array('respuesta' => 'xsd:string'), $ns);



//Horario Atención

$server->register("ListaHorariosatencion",
			array(),
			array('respuesta' => 'xsd:string'), $ns);

$server->register("InsertarHorariosatencion",
			array('dias' => 'xsd:string','desde' => 'xsd:string','hasta' => 'xsd:string','comida' => 'xsd:string'),
			array('respuesta' => 'xsd:string'), $ns);

$server->register("ModificarHorariosatencion",
		        array('dias' => 'xsd:string','desde' => 'xsd:string','hasta' => 'xsd:string','comida' => 'xsd:string','id' => 'xsd:string'),
			array('respuesta' => 'xsd:string'), $ns);

$server->register("EliminarHorariosatencion",
			array('id' => 'xsd:string'),
			array('respuesta' => 'xsd:string'), $ns);

$server->register("BuscarHorariosatencion",
			array('id' => 'xsd:string'),
			array('respuesta' => 'xsd:string'), $ns);

$server->register("CambiarEstadoHorariosatencion",
			array('id' => 'xsd:string','estado' => 'xsd:string'),
			array('respuesta' => 'xsd:string'), $ns);



//PARTE DE LORENA
//Parametros
$server->register("ListaParametros",
			array(),
			array('respuesta' => 'xsd:string'), $ns);

//	Lista de usuarios Eliminados
$server->register("ListaParametrose",
			array(),
			array('respuesta' => 'xsd:string'), $ns);

$server->register("InsertarParametro",
			array('descripcion' => 'xsd:string', 'valor' => 'xsd:string', 'estado' => 'xsd:string'),
			array('respuesta' => 'xsd:string'), $ns);

$server->register("BuscarParametro",
			array('id' => 'xsd:string'),
			array('respuesta' => 'xsd:string'), $ns);

$server->register("ModificarParametro",
			array('id' => 'xsd:string', 'descripcion' => 'xsd:string', 'valor' => 'xsd:string', 'estado' => 'xsd:string'),
			array('respuesta' => 'xsd:string'), $ns);

//	Activa parámetros
$server->register("ActivarParametro",
			array('id' => 'xsd:string'),
			array('respuesta' => 'xsd:string'), $ns);

$server->register("EliminarParametro",
			array('id' => 'xsd:string'),
			array('respuesta' => 'xsd:string'), $ns);



//Concepto de pagos
$server->register("ListaConceptopagos",
			array(),
			array('respuesta' => 'xsd:string'), $ns);

//	Lista de usuarios Eliminados
$server->register("ListaConceptopagose",
			array(),
			array('respuesta' => 'xsd:string'), $ns);

$server->register("InsertarConceptopago",
			array('descripcion' => 'xsd:string', 'estado' => 'xsd:string'),
			array('respuesta' => 'xsd:string'), $ns);

$server->register("BuscarConceptopago",
			array('id' => 'xsd:string'),
			array('respuesta' => 'xsd:string'), $ns);

$server->register("ModificarConceptopago",
			array('id' => 'xsd:string', 'descripcion' => 'xsd:string', 'estado' => 'xsd:string'),
			array('respuesta' => 'xsd:string'), $ns);
//	Activa parámetros
$server->register("ActivarConceptopago",
			array('id' => 'xsd:string'),
			array('respuesta' => 'xsd:string'), $ns);

$server->register("EliminarConceptopago",
			array('id' => 'xsd:string'),
			array('respuesta' => 'xsd:string'), $ns);

$server->register("CambiarEstadoConceptopago",
			array('id' => 'xsd:string','estado' => 'xsd:string'),
			array('respuesta' => 'xsd:string'), $ns);


//Formas de pago
$server->register("ListaFormapagos",
			array(),
			array('respuesta' => 'xsd:string'), $ns);
//	Lista de usuarios Eliminados
$server->register("ListaFormapagose",
			array(),
			array('respuesta' => 'xsd:string'), $ns);

$server->register("InsertarFormapago",
			array('descripcion' => 'xsd:string', 'estado' => 'xsd:string'),
			array('respuesta' => 'xsd:string'), $ns);

$server->register("BuscarFormapago",
			array('id' => 'xsd:string'),
			array('respuesta' => 'xsd:string'), $ns);

$server->register("ModificarFormapago",
			array('id' => 'xsd:string', 'descripcion' => 'xsd:string', 'estado' => 'xsd:string'),
			array('respuesta' => 'xsd:string'), $ns);
//	Activa parámetros
$server->register("ActivarFormapago",
			array('id' => 'xsd:string'),
			array('respuesta' => 'xsd:string'), $ns);

$server->register("EliminarFormapago",
			array('id' => 'xsd:string'),
			array('respuesta' => 'xsd:string'), $ns);

$server->register("CambiarEstadoFormapago",
			array('id' => 'xsd:string','estado' => 'xsd:string'),
			array('respuesta' => 'xsd:string'), $ns);

//FIN PARTE DE LORENA

/*********************** Modulo Reserva ****************/
//	Lista de Reservas Admin
$server->register("listaReserva",
			array(),
			array('respuesta' => 'xsd:string'), $ns);

$server->register("estadoReserva",
			array('id' => 'xsd:string'),
			array('respuesta' => 'xsd:string'), $ns);

$server->register("listarAreas",
			array(),
			array('respuesta' => 'xsd:string'), $ns);

$server->register("listarAreasAdmin",
			array(),
			array('respuesta' => 'xsd:string'), $ns);


$server->register("listaReservaAceptada",
			array(),
			array('respuesta' => 'xsd:string'), $ns);


$server->register("CancelarPreReserva",
			array('id' => 'xsd:string'),
			array('respuesta' => 'xsd:string'), $ns);


$server->register("insertarReserva",
			array('fecha' => 'xsd:string', ' desde' => 'xsd:string', 'hasta' => 'xsd:string', 'area' => 'xsd:string','id' => 'xsd:string' ),
			array('respuesta' => 'xsd:string'), $ns);



$server->register("insertarHoraMantenimiento",
			array('fecha_inicio' => 'xsd:string','fecha_fin' => 'xsd:string', ' desde' => 'xsd:string', 'hasta' => 'xsd:string','area' => 'xsd:string' ),
			array('respuesta' => 'xsd:string'), $ns);



$server->register("eliminacionAutomatica",
			array('valor' => 'xsd:string'),
			array('respuesta' => 'xsd:string'), $ns);


    $server->register("guardarHora",
			array($valor),
			array('respuesta' => 'xsd:string'), $ns);


/*********************** Fin Modulo Reserva ****************/

$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
$server->service($HTTP_RAW_POST_DATA);

?>
