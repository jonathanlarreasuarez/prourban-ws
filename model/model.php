<?php

include($_SERVER["DOCUMENT_ROOT"] . '/prourban-ws/db/conexion.php');

function Consultar($sql, $mensajeError) {
	$db = new conexion();
	$result = $db->consulta($sql);
	$num = $db->encontradas($result);

	$respuesta->datos = [];
	$respuesta->mensaje = "";
	$respuesta->codigo = "";

	if ($num != 0) {

		for ($i=0; $i < $num; $i++) {
			$respuesta->datos[] = mysql_fetch_array($result);
		}

		$respuesta->mensaje = "Ok";
		$respuesta->codigo = 1;
	} else {
		$respuesta->mensaje = $mensajeError;
		$respuesta->codigo = 0;
	}

	return json_encode($respuesta);
}

function Procesar($sql) {
	$db = new conexion();
	$result = $db->consulta($sql);

	$respuesta->datos = [];
	$respuesta->mensaje = "";
	$respuesta->codigo = "";

	if ($result) {

		for ($i=0; $i < $num; $i++) {
			$respuesta->datos[] = mysql_fetch_array($result);
		}

		$respuesta->mensaje = "Registro actualizado!";
		$respuesta->codigo = 1;
	} else {
		$respuesta->mensaje = "Datos inválidos!";
		$respuesta->codigo = 0;
	}

	return json_encode($respuesta);
}


function Autenticacion($usuario, $clave) {

	//obtiene el id del usuario
	$sql = "SELECT a.id, a.nombre_usuario, b.primer_nombre, b.primer_apellido FROM usuario a
			INNER JOIN persona b ON b.id = a.persona_id
			WHERE a.nombre_usuario = '$usuario' AND a.clave = '$clave'";

	$db = new conexion();
	$result = $db->consulta($sql);
	$num = $db->encontradas($result);

	$respuesta->datos = [];
	$respuesta->mensaje = "";
	$respuesta->codigo = "";

	if ($num != 0) {

		for ($i=0; $i < $num; $i++) {
			$respuesta->datos[] = mysql_fetch_array($result);
		}

		$respuesta->mensaje = "Ok";
		$respuesta->codigo = 1;
	} else {
		$respuesta->mensaje = "Datos inválidos!";
		$respuesta->codigo = 0;
	}

	return json_encode($respuesta);

}

function CargaMenu($usuario_id) {
	$sql = "SELECT modul.id, modul.descripcion, modul.iconclass, opc.nombre, opc.url from opciones opc
			INNER JOIN moduloxopcionxrol mopcrol ON mopcrol.opciones_id = opc.id
			INNER JOIN usuarioxrol usurol ON usurol.id = mopcrol.usuarioxrol_id
			INNER JOIN usuario usu ON usu.id = usurol.usuario_id
			INNER JOIN modulos modul ON modul.id = mopcrol.modulos_id
			WHERE usu.id = '$usuario_id'";

	$db = new conexion();
	$resul = $db->consulta($sql);
	$num = $db->encontradas($resul);

	if ($num != 0) {
		for ($i = 0; $i < $num; $i++) {
			$filas = mysql_fetch_array($resul);
			$datos.= $filas[0];
			$datos.= '|';
			$datos.= $filas[1];
			$datos.= '|';
			$datos.= $filas[2];
			$datos.= '|';
			$datos.= $filas[3];
			$datos.= '|';
			$datos.= $filas[4];
			$datos.= '|';
		}
		return $datos;
	} else {
		return "no";
	}
}


//	PROVEEDOR
//	Devuelve lista de proveedores
function ListaProveedores() {

	//obtiene el id del usuario
	$sql = "SELECT * FROM proveedor";

	$db = new conexion();
	$result = $db->consulta($sql);
	$num = $db->encontradas($result);

	$respuesta->datos = [];
	$respuesta->mensaje = "";
	$respuesta->codigo = "";

	if ($num != 0) {

		for ($i=0; $i < $num; $i++) {
			$respuesta->datos[] = mysql_fetch_array($result);
		}

		$respuesta->mensaje = "Ok";
		$respuesta->codigo = 1;
	} else {
		$respuesta->mensaje = "No existen registros de proveedores!";
		$respuesta->codigo = 0;
	}

	return json_encode($respuesta);
}

//	inserta un proveedor
function InsertarProveedor($descripcion, $ruc) {
	$sql = "INSERT INTO proveedor (descripcion, ruc)
			VALUES ('$descripcion', '$ruc')";

	$db = new conexion();
	$result = $db->consulta($sql);

	$respuesta->datos = [];
	$respuesta->mensaje = "";
	$respuesta->codigo = "";

	if ($result) {

		for ($i=0; $i < $num; $i++) {
			$respuesta->datos[] = mysql_fetch_array($result);
		}

		$respuesta->mensaje = "Ok";
		$respuesta->codigo = 1;
	} else {
		$respuesta->mensaje = "Datos inválidos!";
		$respuesta->codigo = 0;
	}

	return json_encode($respuesta);
}

function BuscarProveedor($id) {

	$sql = "SELECT * FROM proveedor WHERE id = $id";

	$db = new conexion();
	$result = $db->consulta($sql);
	$num = $db->encontradas($result);

	$respuesta->datos = [];
	$respuesta->mensaje = "";
	$respuesta->codigo = "";

	if ($num != 0) {

		for ($i=0; $i < $num; $i++) {
			$respuesta->datos[] = mysql_fetch_array($result);
		}

		$respuesta->mensaje = "Ok";
		$respuesta->codigo = 1;
	} else {
		$respuesta->mensaje = "Ha ocurrido un error!";
		$respuesta->codigo = 0;
	}

	return json_encode($respuesta);
}

function ModificarProveedor($id, $descripcion, $ruc) {

	$sql = "UPDATE proveedor SET descripcion = '$descripcion', ruc = '$ruc' WHERE proveedor.id = $id";

	$db = new conexion();
	$result = $db->consulta($sql);

	$respuesta->datos = [];
	$respuesta->mensaje = "";
	$respuesta->codigo = "";

	if ($result) {

		for ($i=0; $i < $num; $i++) {
			$respuesta->datos[] = mysql_fetch_array($result);
		}

		$respuesta->mensaje = "Registro actualizado!";
		$respuesta->codigo = 1;
	} else {
		$respuesta->mensaje = "Datos inválidos!";
		$respuesta->codigo = 0;
	}

	return json_encode($respuesta);
}

function EliminarProveedor($id) {
	$sql = "DELETE FROM proveedor WHERE id=$id";

	$db = new conexion();
	$result = $db->consulta($sql);

	$respuesta->datos = [];
	$respuesta->mensaje = "";
	$respuesta->codigo = "";

	if ($result) {

		for ($i=0; $i < $num; $i++) {
			$respuesta->datos[] = mysql_fetch_array($result);
		}

		$respuesta->mensaje = "Registro eliminado con éxito!";
		$respuesta->codigo = 1;
	} else {
		$respuesta->mensaje = "Ha ocurrido un error!";
		$respuesta->codigo = 0;
	}

	return json_encode($respuesta);
}


//---- CUENTAXCOBRAR ----//
function ListaCuentaxcobrar($nombrexBuscar) {

	if ($nombrexBuscar === "nulo") {
		$sql = "SELECT cuentaxcobrar.id, persona.primer_nombre, persona.primer_apellido, persona.cedula, cuentaxcobrar.fecha_maxima_pago, cuentaxcobrar.estado
				FROM cuentaxcobrar
				INNER JOIN usuario ON usuario.id = cuentaxcobrar.usuario_id
				INNER JOIN persona ON persona.id = usuario.persona_id
				WHERE cuentaxcobrar.estado='pendiente'";
	}else {
		$sql = "SELECT usuario.id, persona.primer_nombre, persona.primer_apellido, cuentaxcobrar.fecha_maxima_pago, cuentaxcobrar.estado
				FROM cuentaxcobrar
				INNER JOIN usuario ON usuario.id = cuentaxcobrar.usuario_id
				INNER JOIN persona ON persona.id = usuario.persona_id
				where CONCAT (persona.primer_nombre, ' ' ,persona.primer_apellido) like '%$nombrexBuscar%' AND cuentaxcobrar.estado = 'pendiente'";
	}

	$db = new conexion();
	$result = $db->consulta($sql);
	$num = $db->encontradas($result);

	$respuesta->datos = [];
	$respuesta->mensaje = "";
	$respuesta->codigo = "";

	if ($num != 0) {
		for ($i=0; $i < $num; $i++) {
			$respuesta->datos[] = mysql_fetch_array($result);
		}

		$respuesta->mensaje = "Ok";
		$respuesta->codigo = 1;
	} else {
		$respuesta->mensaje = "No existen registros de cuentas por pagar!";
		$respuesta->codigo = 0;
	}

	return json_encode($respuesta);
}

function BuscarCuentaxcobrar($id) {

	$sql = "SELECT * FROM cuentaxcobrar WHERE id = $id";

	$db = new conexion();
	$result = $db->consulta($sql);
	$num = $db->encontradas($result);

	$respuesta->datos = [];
	$respuesta->mensaje = "";
	$respuesta->codigo = "";

	if ($num != 0) {

		for ($i=0; $i < $num; $i++) {
			$respuesta->datos[] = mysql_fetch_array($result);
		}

		$respuesta->mensaje = "Ok";
		$respuesta->codigo = 1;
	} else {
		$respuesta->mensaje = "Ha ocurrido un error!";
		$respuesta->codigo = 0;
	}

	return json_encode($respuesta);
}


//---- CUENTAXPAGAR ----//

function ListaCuentasxpagar() {

	//obtiene el id del usuario
<<<<<<< HEAD
	$sql = "SELECT a.id, a.descripcion, a.fecha, a.total, b.descripcion AS nombre_proveedor
			FROM cuentaxpagar a INNER JOIN proveedor b ON a.proveedor_id = b.id;";
=======
	$sql = "SELECT a.id, a.descripcion, a.fecha, a.total, b.descripcion AS nombre_proveedor 
			FROM cuentaxpagar a 
			INNER JOIN proveedor b ON a.proveedor_id = b.id
			WHERE a.estado = 'ACTIVO'";
>>>>>>> borrado logico de cuentas por pagar


	$db = new conexion();
	$result = $db->consulta($sql);
	$num = $db->encontradas($result);

	$respuesta->datos = [];
	$respuesta->mensaje = "";
	$respuesta->codigo = "";

	if ($num != 0) {
		for ($i=0; $i < $num; $i++) {
			$respuesta->datos[] = mysql_fetch_array($result);
		}

		$respuesta->mensaje = "Ok";
		$respuesta->codigo = 1;
	} else {
		$respuesta->mensaje = "No existen registros de cuentas por pagar!";
		$respuesta->codigo = 0;
	}

	return json_encode($respuesta);
}

function BuscarCuentaxpagar($id) {

	$sql = "SELECT a.*, b.descripcion AS nombre_proveedor FROM cuentaxpagar a
			INNER JOIN proveedor b on a.proveedor_id = b.id
			WHERE a.id = $id";

	$db = new conexion();
	$result = $db->consulta($sql);
	$num = $db->encontradas($result);

	$respuesta->datos = [];
	$respuesta->mensaje = "";
	$respuesta->codigo = "";

	if ($num != 0) {

		for ($i=0; $i < $num; $i++) {
			$respuesta->datos[] = mysql_fetch_array($result);
		}

		$respuesta->mensaje = "Ok";
		$respuesta->codigo = 1;
	} else {
		$respuesta->mensaje = "Ha ocurrido un error!";
		$respuesta->codigo = 0;
	}

	return json_encode($respuesta);
}

function ModificarCuentaxpagar($id, $descripcion, $fecha, $total, $numero_referencia, $proveedor_id) {

	$sql = "UPDATE cuentaxpagar SET descripcion = '$descripcion', fecha = '$fecha', total = '$total',
				numero_referencia = '$numero_referencia', proveedor_id = '$proveedor_id'
			WHERE cuentaxpagar.id = $id";

	$db = new conexion();
	$result = $db->consulta($sql);

	$respuesta->datos = [];
	$respuesta->mensaje = "";
	$respuesta->codigo = "";

	if ($result) {

		for ($i=0; $i < $num; $i++) {
			$respuesta->datos[] = mysql_fetch_array($result);
		}

		$respuesta->mensaje = "Registro actualizado!";
		$respuesta->codigo = 1;
	} else {
		$respuesta->mensaje = "Datos inválidos!";
		$respuesta->codigo = 0;
	}

	return json_encode($respuesta);
}

function InsertarCuentaxpagar($descripcion, $fecha, $total, $numero_referencia, $proveedor_id) {

	$sql = "INSERT INTO cuentaxpagar (descripcion, fecha, total, numero_referencia, proveedor_id)
			VALUES ('$descripcion', '$fecha', '$total', '$numero_referencia', '$proveedor_id')";

	$db = new conexion();
	$result = $db->consulta($sql);

	$respuesta->datos = [];
	$respuesta->mensaje = "";
	$respuesta->codigo = "";

	if ($result) {

		for ($i=0; $i < $num; $i++) {
			$respuesta->datos[] = mysql_fetch_array($result);
		}

		$respuesta->mensaje = "Registro actualizado!";
		$respuesta->codigo = 1;
	} else {
		$respuesta->mensaje = "Datos inválidos!";
		$respuesta->codigo = 0;
	}

	return json_encode($respuesta);
}

function EliminarCuentaxpagar($id) {
	$sql = "UPDATE cuentaxpagar SET estado = 'INACTIVO' WHERE id = $id";

	$db = new conexion();
	$result = $db->consulta($sql);

	$respuesta->datos = [];
	$respuesta->mensaje = "";
	$respuesta->codigo = "";

	if ($result) {

		for ($i=0; $i < $num; $i++) {
			$respuesta->datos[] = mysql_fetch_array($result);
		}

		$respuesta->mensaje = "Registro eliminado con éxito!";
		$respuesta->codigo = 1;
	} else {
		$respuesta->mensaje = "Ha ocurrido un error!";
		$respuesta->codigo = 0;
	}

	return json_encode($respuesta);
}


//---- RESERVA ----//

function ListaPreReservas() {

	//obtiene el id del usuario
	$sql = "SELECT reserva.id, reserva.fecha_solicitud, reserva.fecha_reserva, concat(persona.primer_nombre, ' ', persona.primer_apellido) AS nombre,
				   persona.cedula, area.descripcion
			FROM reserva INNER JOIN usuario ON reserva.usuario_id = usuario.id
			INNER JOIN persona on usuario.persona_id = persona.id
			INNER JOIN area ON reserva.area_id = area.id
			WHERE reserva.estado = 'Pre-reservado'";

	$db = new conexion();
	$result = $db->consulta($sql);
	$num = $db->encontradas($result);

	$respuesta->datos = [];
	$respuesta->mensaje = "";
	$respuesta->codigo = "";

	if ($num != 0) {
		for ($i=0; $i < $num; $i++) {
			$respuesta->datos[] = mysql_fetch_array($result);
		}

		$respuesta->mensaje = "Ok";
		$respuesta->codigo = 1;
	} else {
		$respuesta->mensaje = "No existen reservas pendientes!";
		$respuesta->codigo = 0;
	}

	return json_encode($respuesta);
}

function BuscarPreReserva($id) {
	$sql = "SELECT reserva.id, usuario.id, concat(persona.primer_nombre, ' ', persona.primer_apellido) AS nombre,
				   persona.cedula, concat('Manzana ', inmueble.manzana, ', villa ', inmueble.numero_villa) AS direccion, area.descripcion
			FROM reserva 
			INNER JOIN usuario ON usuario.id = reserva.usuario_id 
			INNER JOIN persona ON persona.id = usuario.persona_id 
			INNER JOIN area ON reserva.area_id = area.id
			INNER JOIN inmueble ON usuario.id = inmueble.id WHERE reserva.id = $id";

	$mensajeError = "No se encontró la reserva!";

	return Consultar($sql, $mensajeError);
}

function PagarReserva($id) {
	$sql = "UPDATE reserva SET estado = 'Reservado'
			WHERE id = $id";

	$mensajeError = "No se ha podido realizar el pago de la reserva";

	$result = Procesar($sql, $mensajeError);

	return $result;
}


//---- FACTURA ----//

function CabeceraFactura($id) {

	//obtiene el id de la cuentaXcobrar
	$sql = "SELECT cuentaxcobrar.id, usuario.id, concat(persona.primer_nombre, ' ', persona.primer_apellido) AS nombre,
				   persona.cedula, concat('Manzana ', inmueble.manzana, ', villa ', inmueble.numero_villa) AS direccion
			FROM cuentaxcobrar
			INNER JOIN usuario ON usuario.id = cuentaxcobrar.usuario_id
			INNER JOIN persona ON persona.id = usuario.persona_id
			INNER JOIN inmueble ON usuario.id = inmueble.id WHERE cuentaxcobrar.id = $id";

	$db = new conexion();
	$result = $db->consulta($sql);
	$num = $db->encontradas($result);

	$respuesta->datos = [];
	$respuesta->mensaje = "";
	$respuesta->codigo = "";

	if ($num != 0) {
		for ($i=0; $i < $num; $i++) {
			$respuesta->datos[] = mysql_fetch_array($result);
		}

		$respuesta->mensaje = "Ok";
		$respuesta->codigo = 1;
	} else {
		$respuesta->mensaje = "No existen registros";
		$respuesta->codigo = 0;
	}

	return json_encode($respuesta);
}

function DetalleFactura($id) {

	//obtiene el id de la cuentaXcobrar
	$sql = "SELECT * FROM `conceptopago` WHERE id = '$id'";

	$db = new conexion();
	$result = $db->consulta($sql);
	$num = $db->encontradas($result);

	$respuesta->datos = [];
	$respuesta->mensaje = "";
	$respuesta->codigo = "";

	if ($num != 0) {
		for ($i=0; $i < $num; $i++) {
			$respuesta->datos[] = mysql_fetch_array($result);
		}

		$respuesta->mensaje = "Ok";
		$respuesta->codigo = 1;
	} else {
		$respuesta->mensaje = "No existen registros";
		$respuesta->codigo = 0;
	}

	return json_encode($respuesta);
}

function ListaCabeceraFactura() {

	//obtiene el id del usuario
	$sql = "SELECT cuentaxcobrar.id, usuario.id, persona.primer_nombre, persona.primer_apellido, persona.cedula,
				   inmueble.manzana, inmueble.numero_villa FROM cuentaxcobrar
			INNER JOIN usuario ON usuario.id = cuentaxcobrar.usuario_id
			INNER JOIN persona ON persona.id = usuario.persona_id
			INNER JOIN inmueble ON usuario.id = inmueble.id";


	$db = new conexion();
	$result = $db->consulta($sql);
	$num = $db->encontradas($result);

	$respuesta->datos = [];
	$respuesta->mensaje = "";
	$respuesta->codigo = "";

	if ($num != 0) {
		for ($i=0; $i < $num; $i++) {
			$respuesta->datos[] = mysql_fetch_array($result);
		}

		$respuesta->mensaje = "Ok";
		$respuesta->codigo = 1;
	} else {
		$respuesta->mensaje = "No existen registros de Cabecera Factura!";
		$respuesta->codigo = 0;
	}

	return json_encode($respuesta);
}

function PagarFactura($id) {

	$sql = "UPDATE cuentaxcobrar SET estado = 'pagado'
			WHERE cuentaxcobrar.id = $id";

	$db = new conexion();
	$result = $db->consulta($sql);

	$respuesta->datos = [];
	$respuesta->mensaje = "";
	$respuesta->codigo = "";

	if ($result) {

		for ($i=0; $i < $num; $i++) {
			$respuesta->datos[] = mysql_fetch_array($result);
		}

		$respuesta->mensaje = "Registro actualizado!";
		$respuesta->codigo = 1;
	} else {
		$respuesta->mensaje = "Datos inválidos!";
		$respuesta->codigo = 0;
	}

	return json_encode($respuesta);
}

function GuardarCabeceraFactura($fecha_factura, $numero_factura, $subtotal, $iva, $total, $formapago_id, $usuario_id) {

	$sql = "INSERT INTO `factura` (`fecha_factura`, `numero_factura`, `subtotal`, `iva`, `total`, `formapago_id`, `usuario_id`)
					VALUES ('$fecha_factura', '$numero_factura', '$subtotal', '$iva', '$total','$formapago_id' , '$usuario_id');";

	$sqlNew = "SELECT id FROM `factura` ORDER BY id DESC LIMIT 1;";

	$db = new conexion();
	$result = $db->consulta($sql);
	$resultNew = $db->consulta($sqlNew);
	$num = $db->encontradas($resultNew);


	$respuesta->datos = [];
	$respuesta->mensaje = "";
	$respuesta->codigo = "";

	if ($resultNew) {

		for ($i=0; $i < $num; $i++) {
			$respuesta->datos[] = mysql_fetch_array($resultNew);
		}

		$respuesta->mensaje = "Registro actualizado!";
		$respuesta->codigo = 1;
	} else {
		$respuesta->mensaje = "Datos inválidos!";
		$respuesta->codigo = 0;
	}

	return json_encode($respuesta);
}

function GuardarDetalleFactura($valor, $conceptopago_id, $factura_id) {

	$sql = "INSERT INTO `facturadetalle` (`valor`, `estado`, `conceptopago_id`, `impuesto_id`, `factura_id`)
					VALUES ('$valor', 'pagado', '$conceptopago_id', '1', '$factura_id');";


	$db = new conexion();
	$result = $db->consulta($sql);
	$num = $db->encontradas($result);


	$respuesta->datos = [];
	$respuesta->mensaje = "";
	$respuesta->codigo = "";

	if ($result) {

		for ($i=0; $i < $num; $i++) {
			$respuesta->datos[] = mysql_fetch_array($result);
		}

		$respuesta->mensaje = "Registro actualizado!";
		$respuesta->codigo = 1;
	} else {
		$respuesta->mensaje = "Datos inválidos!";
		$respuesta->codigo = 0;
	}

	return json_encode($respuesta);
}
?>
