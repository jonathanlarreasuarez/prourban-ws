<?php

include($_SERVER["DOCUMENT_ROOT"] . '/prourban-ws/db/conexion.php');

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

	$file = fopen("prourban.log", "a");
	fwrite($file, $sql);
	fclose($file);

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


//	Devuelve lista de gastos
function ListaDeudasUsuarios() {

	//obtiene el id del usuario
	$sql = "SELECT usuario.id, persona.primer_nombre, persona.primer_apellido, cuentaxcobrar.fecha_maxima_pago, cuentaxcobrar.estado FROM cuentaxcobrar INNER JOIN usuario ON usuario.id = cuentaxcobrar.usuario_id INNER JOIN persona ON persona.id = usuario.persona_id";

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

?>