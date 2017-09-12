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


//Seguridad---------------------------------------------
function Autenticacion($usuario, $clave) {
	
		$sql = "SELECT usr.id, usr.nombre_usuario, per.primer_nombre, per.primer_apellido FROM usuario usr
				INNER JOIN persona per ON per.id = usr.persona_id
				WHERE usr.nombre_usuario = '$usuario' AND usr.clave = '$clave'";
	
	
	
		$db = new conexion();
		$resul = $db->consulta($sql);
		$num = $db->encontradas($resul);
	
		$respuesta->datos = [];
		$respuesta->datos_modulo = [];
		$respuesta->datos_opcion = [];
		$respuesta->mensaje = "";
		$respuesta->codigo = "";
		$id_usuario = "";
	
		if ($num != 0) {
	
			for ($i=0; $i < $num; $i++) {
	
				$data = mysql_fetch_array($resul);
	
				$id_usuario = $data[0];
	
				$respuesta->datos[] = $data;
			}
	
			$sql_modulo = "SELECT modul.id, modul.descripcion, modul.iconclass FROM modulo modul
				INNER JOIN opcion opc ON opc.modulo_id = modul.id
				INNER JOIN opcionxrol opcrol ON opcrol.opcion_id = opc.id
				INNER JOIN usuario usu ON usu.rol_id = opcrol.rol_id
				WHERE usu.id = '$id_usuario'
				GROUP BY modul.id ORDER BY modul.descripcion ASC";
	
			$resul_modul = $db->consulta($sql_modulo);
			$num_modul = $db->encontradas($resul_modul);
	
			if ($num_modul != 0) {
	
				for ($i=0; $i < $num_modul; $i++) {
					$respuesta->datos_modulo[] = mysql_fetch_array($resul_modul);
				}
	
			}
	
			$sql_opcion = "SELECT opc.id, opc.nombre, opc.url, opc.modulo_id FROM opcion opc
				INNER JOIN opcionxrol opcrol ON opcrol.opcion_id = opc.id
				INNER JOIN usuario usu ON usu.rol_id = opcrol.rol_id
				WHERE usu.id = '$id_usuario'";
	
			$resul_opc = $db->consulta($sql_opcion);
			$num_opc = $db->encontradas($resul_opc);
	
			if ($num_opc != 0) {
	
				for ($i=0; $i < $num_opc; $i++) {
					$respuesta->datos_opcion[] = mysql_fetch_array($resul_opc);
				}
	
			}
	
			$respuesta->mensaje = "ok";
			$respuesta->codigo = 1;
	
		} else {
	
			$respuesta->mensaje = "Usuario o password incorrecto.";
			$respuesta->codigo = 0;
	
		}
	
		return json_encode($respuesta);
	
	}
	
	//  Devuelve lista de usuarios
	function ListaUsuario() {
	
		$sql = "SELECT usu.*, CONCAT_WS(' ', per.primer_nombre, per.primer_apellido) as nombre_completo, usu.nombre_usuario, rol.descripcion FROM usuario usu
				INNER JOIN rol rol ON rol.id = usu.rol_id
				INNER JOIN persona per ON per.id = usu.persona_id";
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
			$respuesta->mensaje = "No existen registros de roles.";
			$respuesta->codigo = 0;
		}
		return json_encode($respuesta);
	}
	
	//  Devuelve lista de personas
	function ListaPersona() {
	
		$sql = "SELECT * FROM persona";
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
			$respuesta->mensaje = "No existen registros de personas.";
			$respuesta->codigo = 0;
		}
		return json_encode($respuesta);
	}
	
	//inserta nuevo usuario
	function InsertarUsuario($nombre_usuario,$clave,$persona_id,$rol_id) {
		$sql = "INSERT INTO usuario (nombre_usuario,clave,persona_id,rol_id)
				VALUES ('$nombre_usuario','$clave','$persona_id','$rol_id')";
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
			$respuesta->mensaje = "Datos no válidos.";
			$respuesta->codigo = 0;
		}
		return json_encode($respuesta);
	}
	
	//  Buscar Usuario
	function BuscarUsuario($id) {
		$sql = "SELECT * FROM usuario WHERE id = $id";
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
			$respuesta->mensaje = "Sin resultados.";
			$respuesta->codigo = 0;
		}
		return json_encode($respuesta);
	}
	
	// Modificar Usuario
	function ModificarUsuario($id, $nombre_usuario,$clave, $persona_id, $rol_id) {
		$sql = "UPDATE usuario SET nombre_usuario = '$nombre_usuario', clave = '$clave', persona_id = '$persona_id',rol_id = '$rol_id' WHERE id = $id";
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
			$respuesta->mensaje = "Registro actualizado.";
			$respuesta->codigo = 1;
		} else {
			$respuesta->mensaje = "Datos no válidos.";
			$respuesta->codigo = 0;
		}
		return json_encode($respuesta);
	}
	
	// Eliminar Usuario
	function EliminarUsuario($id) {
		$sql = "DELETE FROM usuario WHERE id=$id";
		$db = new conexion();
		$result = $db->consulta($sql);
		$respuesta->datos = [];
		$respuesta->mensaje = "";
		$respuesta->codigo = "";
		if ($result) {
			for ($i=0; $i < $num; $i++) {
				$respuesta->datos[] = mysql_fetch_array($result);
			}
			$respuesta->mensaje = "Registro eliminado con éxito.";
			$respuesta->codigo = 1;
		} else {
			$respuesta->mensaje = "Ha ocurrido un error.";
			$respuesta->codigo = 0;
		}
		return json_encode($respuesta);
	}
	
	
	
	//  Devuelve lista de opciones
	function ListaOpciones($rol_id) {
	
		$sql = "SELECT * FROM modulo";
		$db = new conexion();
		$result = $db->consulta($sql);
		$num = $db->encontradas($result);
		$respuesta->datos_modulo = [];
		$respuesta->datos_opcion = [];
		$respuesta->datos_opcionxrol = [];
		$respuesta->mensaje = "";
		$respuesta->codigo = "";
		if ($num != 0) {
			for ($i=0; $i < $num; $i++) {
				$respuesta->datos_modulo[] = mysql_fetch_array($result);
			}
	
			$sql = "SELECT * FROM opcion";
			$db = new conexion();
			$result = $db->consulta($sql);
			$num = $db->encontradas($result);
	
			if ($num != 0) {
				for ($i=0; $i < $num; $i++) {
					$respuesta->datos_opcion[] = mysql_fetch_array($result);
				}
			}
	
			$sql = "SELECT * FROM opcionxrol WHERE rol_id = $rol_id";
			$db = new conexion();
			$result = $db->consulta($sql);
			$num = $db->encontradas($result);
	
			if ($num != 0) {
				for ($i=0; $i < $num; $i++) {
					$respuesta->datos_opcionxrol[] = mysql_fetch_array($result);
				}
			}
	
			$respuesta->mensaje = "Ok";
			$respuesta->codigo = 1;
		} else {
			$respuesta->mensaje = "No existen registros de roles.";
			$respuesta->codigo = 0;
		}
		return json_encode($respuesta);
	}
	
	function getOpciones() {
	
		$sql = "SELECT * FROM modulo";
		$db = new conexion();
		$result = $db->consulta($sql);
		$num = $db->encontradas($result);
		$respuesta->datos_modulo = [];
		$respuesta->datos_opcion = [];
		$respuesta->datos_opcionxrol = [];
		$respuesta->mensaje = "";
		$respuesta->codigo = "";
		if ($num != 0) {
			for ($i=0; $i < $num; $i++) {
				$respuesta->datos_modulo[] = mysql_fetch_array($result);
			}
	
			$sql = "SELECT * FROM opcion";
			$db = new conexion();
			$result = $db->consulta($sql);
			$num = $db->encontradas($result);
	
			if ($num != 0) {
				for ($i=0; $i < $num; $i++) {
					$respuesta->datos_opcion[] = mysql_fetch_array($result);
				}
			}
	
			$sql = "SELECT * FROM opcionxrol";
			$db = new conexion();
			$result = $db->consulta($sql);
			$num = $db->encontradas($result);
	
			if ($num != 0) {
				for ($i=0; $i < $num; $i++) {
					$respuesta->datos_opcionxrol[] = mysql_fetch_array($result);
				}
			}
	
			$respuesta->mensaje = "Ok";
			$respuesta->codigo = 1;
		} else {
			$respuesta->mensaje = "No existen registros de roles.";
			$respuesta->codigo = 0;
		}
		return json_encode($respuesta);
	}
	
	
	function ListaOpcionesRol($rol_id) {
	
		$sql = "SELECT * FROM modulo";
		$db = new conexion();
		$result = $db->consulta($sql);
		$num = $db->encontradas($result);
		$respuesta->datos_modulo = [];
		$respuesta->datos_opcion = [];
		$respuesta->datos_opcionxrol = [];
		$respuesta->mensaje = "";
		$respuesta->codigo = "";
		if ($num != 0) {
			for ($i=0; $i < $num; $i++) {
				$respuesta->datos_modulo[] = mysql_fetch_array($result);
			}
	
			$sql = "SELECT * FROM opcion";
			$db = new conexion();
			$result = $db->consulta($sql);
			$num = $db->encontradas($result);
	
			if ($num != 0) {
				for ($i=0; $i < $num; $i++) {
					$respuesta->datos_opcion[] = mysql_fetch_array($result);
				}
			}
	
			$sql = "SELECT * FROM opcionxrol WHERE rol_id = $rol_id";
			$db = new conexion();
			$result = $db->consulta($sql);
			$num = $db->encontradas($result);
	
			if ($num != 0) {
				for ($i=0; $i < $num; $i++) {
					$respuesta->datos_opcionxrol[] = mysql_fetch_array($result);
				}
			}
	
			$respuesta->mensaje = "Ok";
			$respuesta->codigo = 1;
		} else {
			$respuesta->mensaje = "No existen registros de roles.";
			$respuesta->codigo = 0;
		}
		return json_encode($respuesta);
	}
	
	//  inserta nuevo rol
	function AsignarOpciones($id,$rol_id,$opcion_id,$estado) {
	
		if ($id == "") {
	
			$sql = "INSERT INTO opcionxrol (opcion_id, rol_id)
				VALUES ('$opcion_id', '$rol_id')";
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
				$respuesta->mensaje = "Datos no válidos.";
				$respuesta->codigo = 0;
			}
			return json_encode($respuesta);
	
		} else {
	
			if ($estado == "1") {
	
				$sql = "DELETE FROM opcionxrol WHERE id=$id";
				$db = new conexion();
				$result = $db->consulta($sql);
	
				if ($result) {
					for ($i=0; $i < $num; $i++) {
						$respuesta->datos[] = mysql_fetch_array($result);
					}
					$respuesta->mensaje = "Registro eliminado con éxito.";
					$respuesta->codigo = 1;
				} else {
					$respuesta->mensaje = "Ha ocurrido un error.";
					$respuesta->codigo = 0;
				}
				return json_encode($respuesta);
	
			}
	
		}
	
	
	}
	
	//  Devuelve lista de roles
	function ListaRol() {
	
		$sql = "SELECT * FROM rol where estado = 1";
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
			$respuesta->mensaje = "No existen registros de roles.";
			$respuesta->codigo = 0;
		}
		return json_encode($respuesta);
	}
	//  inserta nuevo rol
	function InsertarRol($descripcion) {
		$sql = "INSERT INTO rol (descripcion)
				VALUES ('$descripcion')";
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
			$respuesta->mensaje = "Datos no válidos.";
			$respuesta->codigo = 0;
		}
		return json_encode($respuesta);
	}
	
	//  Buscar rol
	function BuscarRol($id) {
		$sql = "SELECT * FROM rol WHERE id = $id";
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
			$respuesta->mensaje = "Sin resultados.";
			$respuesta->codigo = 0;
		}
		return json_encode($respuesta);
	}
	
	// Modificar Rol
	function ModificarRol($id, $descripcion) {
		$sql = "UPDATE rol SET descripcion = '$descripcion' WHERE id = $id";
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
			$respuesta->mensaje = "Registro actualizado.";
			$respuesta->codigo = 1;
		} else {
			$respuesta->mensaje = "Datos no válidos.";
			$respuesta->codigo = 0;
		}
		return json_encode($respuesta);
	}
	
	// Eliminar Rol
	function EliminarRol($id) {
		$sql = "UPDATE rol SET estado = '2' WHERE id = $id";
		$db = new conexion();
		$result = $db->consulta($sql);
		$respuesta->datos = [];
		$respuesta->mensaje = "";
		$respuesta->codigo = "";
		if ($result) {
			for ($i=0; $i < $num; $i++) {
				$respuesta->datos[] = mysql_fetch_array($result);
			}
			$respuesta->mensaje = "Registro eliminado con éxito.";
			$respuesta->codigo = 1;
		} else {
			$respuesta->mensaje = "Ha ocurrido un error.";
			$respuesta->codigo = 0;
		}
		return json_encode($respuesta);
	}
	
	//Seguridad---------------------------------------------


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

	$sql = "SELECT a.id, a.descripcion, a.fecha, a.total, b.descripcion AS nombre_proveedor
			FROM cuentaxpagar a
			INNER JOIN proveedor b ON a.proveedor_id = b.id
			WHERE a.estado = 'ACTIVO'";


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

function GuardarAsiento($fecha, $valor, $conceptoPago, $factura_id) {

	$sql = "INSERT INTO `asientocontable` (`descripcion`, `fecha`, `numero_referencia`, `debito`, `credito`, `diferencia`, `factura_id`, `cuentaxpagar_id`, `debitocuenta`, `creditocuenta`)
					VALUES ('$conceptoPago', '$fecha', NULL, '$valor', '$valor', '0', '$factura_id', NULL, '1', '2');";


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

// ASIENTOS

function ListaCuentas() {

	//obtiene el id del usuario

	$sql = "SELECT c.descripcion, c.saldo_inicial, c.saldo, tc.descripcion as tipo from cuenta c, tipocuenta tc  where c.tipocuenta_id = tc.id";


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
		$respuesta->mensaje = "No existen registros de cuentas";
		$respuesta->codigo = 0;
	}

	return json_encode($respuesta);
}

function ListaAsiento() {
	$sql = "SELECT asientocontable.descripcion, asientocontable.fecha, asientocontable.numero_referencia, asientocontable.debito, asientocontable.credito, asientocontable.factura_id, asientocontable.cuentaxpagar_id, asientocontable.debitocuenta, asientocontable.creditocuenta, cuenta.descripcion, tipocuenta.descripcion FROM cuenta INNER JOIN asientocontable 
		ON asientocontable.debitocuenta = cuenta.id OR asientocontable.creditocuenta = cuenta.id 
		INNER JOIN tipocuenta ON tipocuenta.id = cuenta.tipocuenta_id";


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
		$respuesta->mensaje = "No existen registros de cuentas";
		$respuesta->codigo = 0;
	}

	return json_encode($respuesta);
}
?>
