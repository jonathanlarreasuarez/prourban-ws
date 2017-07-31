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

//	Guarda proveedor
function CrearProveedor($descripcion, $ruc) {

	//obtiene el id del usuario
	$sql = "INSERT INTO proveedor (descripcion, ruc) 
			VALUES ('".$descripcion."', '".$ruc."')";

	$db = new conexion();
	$result = $db->consulta($sql);
	//$num = $db->encontradas($result);

	$file = fopen("prourban.log", "a");
	fwrite($file, $sql);
	fclose($file);

	$respuesta->datos = [];
	$respuesta->mensaje = "";
	$respuesta->codigo = "";

	if ($result) {

		// for ($i=0; $i < $num; $i++) {
		// 	$respuesta->datos[] = mysql_fetch_array($result);
		// }

		$respuesta->datos[] = $result;

		$respuesta->mensaje = "Ok";
		$respuesta->codigo = 1;
	} else {
		$respuesta->mensaje = "No se ha podido guardar el registro";
		$respuesta->codigo = 0;
	}

	return json_encode($respuesta);
	
}

?>