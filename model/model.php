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
	// function InsertarUsuario($nombre_usuario,$clave,$persona_id,$rol_id) {
	// 	$sql = "INSERT INTO usuario (nombre_usuario,clave,persona_id,rol_id)
	// 			VALUES ('$nombre_usuario','$clave','$persona_id','$rol_id')";
	// 	$db = new conexion();
	// 	$result = $db->consulta($sql);
	// 	$respuesta->datos = [];
	// 	$respuesta->mensaje = "";
	// 	$respuesta->codigo = "";
	// 	if ($result) {
	// 		for ($i=0; $i < $num; $i++) {
	// 			$respuesta->datos[] = mysql_fetch_array($result);
	// 		}
	// 		$respuesta->mensaje = "Ok";
	// 		$respuesta->codigo = 1;
	// 	} else {
	// 		$respuesta->mensaje = "Datos no válidos.";
	// 		$respuesta->codigo = 0;
	// 	}
	// 	return json_encode($respuesta);
	// }
	//
	// //  Buscar Usuario
	// function BuscarUsuario($id) {
	// 	$sql = "SELECT * FROM usuario WHERE id = $id";
	// 	$db = new conexion();
	// 	$result = $db->consulta($sql);
	// 	$num = $db->encontradas($result);
	// 	$respuesta->datos = [];
	// 	$respuesta->mensaje = "";
	// 	$respuesta->codigo = "";
	// 	if ($num != 0) {
	// 		for ($i=0; $i < $num; $i++) {
	// 			$respuesta->datos[] = mysql_fetch_array($result);
	// 		}
	// 		$respuesta->mensaje = "Ok";
	// 		$respuesta->codigo = 1;
	// 	} else {
	// 		$respuesta->mensaje = "Sin resultados.";
	// 		$respuesta->codigo = 0;
	// 	}
	// 	return json_encode($respuesta);
	// }
	//
	// // Modificar Usuario
	// function ModificarUsuario($id, $nombre_usuario,$clave, $persona_id, $rol_id) {
	// 	$sql = "UPDATE usuario SET nombre_usuario = '$nombre_usuario', clave = '$clave', persona_id = '$persona_id',rol_id = '$rol_id' WHERE id = $id";
	// 	$file = fopen("prourban.log", "a");
	// 	fwrite($file, $sql);
	// 	fclose($file);
	// 	$db = new conexion();
	// 	$result = $db->consulta($sql);
	// 	$respuesta->datos = [];
	// 	$respuesta->mensaje = "";
	// 	$respuesta->codigo = "";
	// 	if ($result) {
	// 		for ($i=0; $i < $num; $i++) {
	// 			$respuesta->datos[] = mysql_fetch_array($result);
	// 		}
	// 		$respuesta->mensaje = "Registro actualizado.";
	// 		$respuesta->codigo = 1;
	// 	} else {
	// 		$respuesta->mensaje = "Datos no válidos.";
	// 		$respuesta->codigo = 0;
	// 	}
	// 	return json_encode($respuesta);
	// }
	//
	// // Eliminar Usuario
	// function EliminarUsuario($id) {
	// 	$sql = "DELETE FROM usuario WHERE id=$id";
	// 	$db = new conexion();
	// 	$result = $db->consulta($sql);
	// 	$respuesta->datos = [];
	// 	$respuesta->mensaje = "";
	// 	$respuesta->codigo = "";
	// 	if ($result) {
	// 		for ($i=0; $i < $num; $i++) {
	// 			$respuesta->datos[] = mysql_fetch_array($result);
	// 		}
	// 		$respuesta->mensaje = "Registro eliminado con éxito.";
	// 		$respuesta->codigo = 1;
	// 	} else {
	// 		$respuesta->mensaje = "Ha ocurrido un error.";
	// 		$respuesta->codigo = 0;
	// 	}
	// 	return json_encode($respuesta);
	// }



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
function ListaAsientoDebito() {
	$sql = "SELECT asientocontable.descripcion, asientocontable.fecha, asientocontable.numero_referencia, 
	asientocontable.debito, asientocontable.factura_id, asientocontable.cuentaxpagar_id, asientocontable.debitocuenta,
	cuenta.descripcion AS descripcion_debitocuenta FROM asientocontable 
	INNER JOIN cuenta ON cuenta.id = asientocontable.debitocuenta";


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

function ListaAsientoCredito() {
	$sql = "SELECT asientocontable.descripcion, asientocontable.numero_referencia, 
	asientocontable.credito, asientocontable.factura_id, asientocontable.cuentaxpagar_id, asientocontable.creditocuenta,
	cuenta.descripcion AS descripcion_creditocuenta FROM asientocontable 
	INNER JOIN cuenta ON cuenta.id = asientocontable.creditocuenta";


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


//Areas
//------------------------------------------------------------
function ListaAreas() {

	//obtiene el id del usuario

    $sql = "SELECT * FROM `area` WHERE area.estado = 'ACTIVO'";
    //$sql = "SELECT area.id, area.descripcion, area.valor, area.estado WHERE area.estado = 'Activo'";

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
		$respuesta->mensaje = "No existen registros de areas!";
		$respuesta->codigo = 0;
	}

	return json_encode($respuesta);
}

function ListaAreasInactivas() {

	//obtiene el id del usuario

    $sql = "SELECT * FROM `area` WHERE area.estado = 'INACTIVO'";
    //$sql = "SELECT area.id, area.descripcion, area.valor, area.estado WHERE area.estado = 'Activo'";

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
		$respuesta->mensaje = "No existen registros inactivos de área!";
		$respuesta->codigo = 0;
	}

	return json_encode($respuesta);
}

function InsertarArea($descripcion, $valor, $estado) {
	$sql = "INSERT INTO area (descripcion, valor, estado)
			VALUES ('$descripcion', '$valor', '$estado')";

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

		$respuesta->mensaje = "Ok";
		$respuesta->codigo = 1;
	} else {
		$respuesta->mensaje = "Datos inválidos!";
		$respuesta->codigo = 0;
	}

	return json_encode($respuesta);
}
function BuscarArea($id) {

	$sql = "SELECT * FROM area WHERE id = $id";

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
function ModificarArea($id, $descripcion, $valor, $estado) {
    $sql = "UPDATE `area` SET `descripcion` = '$descripcion', `valor` = '$valor', `estado` = '$estado' WHERE `area`.`id` = $id";

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
function EliminarArea($id) {
	//$sql = "DELETE FROM area WHERE id=$id";
    $sql = "UPDATE `area` SET `estado` = 'INACTIVO' WHERE `area`.`id` = '$id'";

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

		$respuesta->mensaje = "Registro eliminado con éxito!";
		$respuesta->codigo = 1;
	} else {
		$respuesta->mensaje = "Ha ocurrido un error!";
		$respuesta->codigo = 0;
	}

	return json_encode($respuesta);
}

function CambiarEstadoArea($id) {
	//$sql = "DELETE FROM area WHERE id=$id";
    $sql = "UPDATE `area` SET `estado` = 'ACTIVO' WHERE `area`.`id` = '$id'";

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

		$respuesta->mensaje = "Registro activado con éxito!";
		$respuesta->codigo = 1;
	} else {
		$respuesta->mensaje = "Ha ocurrido un error!";
		$respuesta->codigo = 0;
	}

	return json_encode($respuesta);
}

//Inmuebles
//------------------------------------------------------------

function ListaInmuebles() {

	//obtiene el id del usuario
	$sql = "SELECT * FROM `inmueble` ";
    //$sql = "SELECT * FROM `inmueble` WHERE inmueble.estado = 'Activo'";

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
		$respuesta->mensaje = "No existen registros de inmuebles!";
		$respuesta->codigo = 0;
	}

	return json_encode($respuesta);
}
function InsertarInmueble($manzana, $numero_villa, $numero_pisos, $numero_cuartos, $numero_banios, $usuario_id) {
	$sql = "INSERT INTO inmueble (manzana, numero_villa, numero_pisos, numero_cuartos, numero_banios, usuario_id)
			VALUES ('$manzana', '$numero_villa', '$numero_pisos', '$numero_cuartos', '$numero_banios', '$usuario_id')";

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
function BuscarInmueble($id) {

	$sql = "SELECT * FROM inmueble WHERE id = $id";

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
function ModificarInmueble($id, $manzana, $numero_villa, $numero_pisos, $numero_cuartos, $numero_banios, $usuario_id) {

   //  $sql = "UPDATE proveedor SET descripcion = '$descripcion', ruc = '$ruc' WHERE proveedor.id = $id";
     $sql = "UPDATE `inmueble` SET `manzana` = '$manzana', `numero_villa` = '$numero_villa', `numero_pisos` = '$numero_pisos', `numero_cuartos` = '$numero_cuartos', `numero_banios` = '$numero_banios', `usuario_id` = '$usuario_id' WHERE `inmueble`.`id` = $id";
        //$sql = "UPDATE inmueble SET manzana = '$manzana', numero_villa = '$numero_villa', numero_pisos = '$numero_pisos', numero_cuartos = '$numero_cuartos', numero_banios = '$numero_banios', usuario_id = '$usuario_id' WHERE inmueble.id = $id";

	/*$file = fopen("prourban.log", "a");
	fwrite($file, $sql);
	fclose($file);*/

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
function EliminarInmueble($id) {
	$sql = "DELETE FROM inmueble WHERE id=$id";

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
function CambiarEstadoInmueble($id, $estado){

    $sql = "UPDATE `inmueble` SET `estado` = '$estado' WHERE `id` = '$id'";

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

//HORARIO MANTENIMIENTO
//------------------------------------------------------------
function ListaHorariosmantenimiento() {

	//$sql = "SELECT * FROM `horariomantenimiento` ";
    $sql = "SELECT * FROM `horariomantenimiento` WHERE horariomantenimiento.estado = 'Activo'";

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
		$respuesta->mensaje = "No existen registros de horarios de mantenimiento!";
		$respuesta->codigo = 0;
	}

	return json_encode($respuesta);
}
function InsertarHorariosmantenimiento($dias, $desde, $hasta, $area, $estado) {
	$sql = "INSERT INTO horariomantenimiento (dias, desde, hasta, area, estado)
			VALUES ('$dias', '$desde', '$hasta', '$area', '$estado')";

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
function BuscarHorariosmantenimiento($id) {

	$sql = "SELECT * FROM horariomantenimiento WHERE id = $id";

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
function ModificarHorariosmantenimiento($id, $dias, $desde, $hasta, $area, $estado) {

    $sql = "UPDATE `horariomantenimiento` SET `dias` = '$dias', `desde` = '$desde', `hasta` = '$hasta', `area` = '$area', `estado` = '$estado' WHERE `horariomantenimiento`.`id` = $id";


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
function EliminarHorariosmantenimiento($id) {
	//$sql = "DELETE FROM horariomantenimiento WHERE id=$id";
     $sql = "UPDATE `horariomantenimiento` SET `estado` = 'Inactivo' WHERE `horariomantenimiento`.`id` = '$id'";

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
function CambiarEstadoHorariosmantenimiento($id, $estado){

    $sql = "UPDATE `horariomantenimiento` SET `estado` = '$estado' WHERE `id` = '$id'";

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

//HORARIO ATENCIÓN
//------------------------------------------------------------
function ListaHorariosatencion() {

	//obtiene el id del usuario
	$sql = "SELECT * FROM `horarioatencion` ";

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
function InsertarHorariosatencion($dias, $desde, $hasta, $comida) {
	$sql = "INSERT INTO horarioatencion (dias, desde, hasta, comida)
			VALUES ('$dias', '$desde', '$hasta', '$comida')";

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
function BuscarHorariosatencion($id) {

	$sql = "SELECT * FROM horarioatencion WHERE id = $id";

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
function ModificarHorariosatencion($dias, $desde, $hasta, $comida, $id) {

	$sql = "UPDATE horarioatencion SET descripcion = '$dias', desde = '$desde', hasta = '$hasta', comida = '$comida' WHERE horarioatencion.id = $id";
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
function EliminarHorariosatencion($id) {
	$sql = "DELETE FROM horarioatencion WHERE id=$id";

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
function CambiarEstadoHorariosatencion($id, $estado){

    $sql = "UPDATE `horarioatencion` SET `estado` = '$estado' WHERE `id` = '$id'";

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
// FIN PARTE DE JOHANNA


//PARTE DE LORENA
//--- PARÁMETROS ---
//------------------------------------------------------------
function ListaParametros() {
	//obtiene el id del usuario
    $sql = "SELECT * FROM `parametro` WHERE parametro.estado = 'ACTIVO'";

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
		$respuesta->mensaje = "Parámetros listados";
		$respuesta->codigo = 1;
	} else {
		$respuesta->mensaje = "No existen registros de parámetros !";
		$respuesta->codigo = 0;
	}
	return json_encode($respuesta);
}
function ListaParametrose() {
	$sql = "SELECT * FROM `parametro` WHERE parametro.estado = 'INACTIVO'";
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

		$respuesta->mensaje = "Lista de parámetros desactivados";
		$respuesta->codigo = 1;
	} else {
		$respuesta->mensaje = "No existen registros de parámetros desactivados!";
		$respuesta->codigo = 0;
	}
	return json_encode($respuesta);
}
function InsertarParametro($descripcion, $valor, $estado) {
	$sql = "INSERT INTO parametro (descripcion, valor, estado) VALUES ('$descripcion', '$valor', '$estado')";

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
		$respuesta->mensaje = "Listo";
		$respuesta->codigo = 1;
	} else {
		$respuesta->mensaje = "Datos inválidos!";
		$respuesta->codigo = 0;
	}
	return json_encode($respuesta);
}
function BuscarParametro($id) {
	$sql = "SELECT * FROM parametro WHERE id = $id";
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
		$respuesta->mensaje = "Ok encontrado";
		$respuesta->codigo = 1;
	} else {
		$respuesta->mensaje = "Ha ocurrido un error!";
		$respuesta->codigo = 0;
	}
	return json_encode($respuesta);
}
function ModificarParametro($id, $descripcion, $valor, $estado) {
	$sql = "UPDATE parametro SET descripcion = '$descripcion', valor = '$valor', estado = '$estado' WHERE parametro.id = $id";
	/*$file = fopen("prourban.log", "a");
	fwrite($file, $sql);
	fclose($file);*/
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
function EliminarParametro($id) {
	//$sql = "DELETE FROM area WHERE id=$id";
    $sql = "UPDATE `parametro` SET `estado` = 'INACTIVO' WHERE `id` = '$id'";
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
		$respuesta->mensaje = "Registro eliminado con éxito!";
		$respuesta->codigo = 1;
	} else {
		$respuesta->mensaje = "Ha ocurrido un error!";
		$respuesta->codigo = 0;
	}
	return json_encode($respuesta);
}
function ActivarParametro($id) {
	$sql = "UPDATE `parametro` SET `estado`= 'ACTIVO' WHERE id=$id";
	$db = new conexion();
	$result = $db->consulta($sql);

	$respuesta->datos = [];
	$respuesta->mensaje = "";
	$respuesta->codigo = "";
	if ($result) {
		for ($i=0; $i < $num; $i++) {
			$respuesta->datos[] = mysql_fetch_array($result);
		}
		$respuesta->mensaje = "Parámetro activado con éxito!";
		$respuesta->codigo = 1;
	} else {
		$respuesta->mensaje = "Ha ocurrido un error!";
		$respuesta->codigo = 0;
	}
	return json_encode($respuesta);
}




//--- CONCEPTO DE PAGOS ---
//------------------------------------------------------------
function ListaConceptopagos() {

	//obtiene el id del usuario

    $sql = "SELECT * FROM `conceptopago` WHERE conceptopago.estado = 'ACTIVO'";

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

		$respuesta->mensaje = "Concepto de pagos listados";
		$respuesta->codigo = 1;
	} else {
		$respuesta->mensaje = "No existen registros de conceptos de pago!";
		$respuesta->codigo = 0;
	}

	return json_encode($respuesta);
}
function ListaConceptopagose() {
	$sql = "SELECT * FROM `conceptopago` WHERE conceptopago.estado = 'INACTIVO'";

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

		$respuesta->mensaje = "Lista de Conceptos de pago desactivados";
		$respuesta->codigo = 1;
	} else {
		$respuesta->mensaje = "No existen registros de Concepto de pago desactivados!";
		$respuesta->codigo = 0;
	}

	return json_encode($respuesta);
}
function InsertarConceptopago($descripcion, $estado) {
	$sql = "INSERT INTO conceptopago (descripcion, estado)
			VALUES ('$descripcion', '$estado')";

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

		$respuesta->mensaje = "Listo";
		$respuesta->codigo = 1;
	} else {
		$respuesta->mensaje = "Datos inválidos!";
		$respuesta->codigo = 0;
	}

	return json_encode($respuesta);
}
function ActivarConceptopago($id) {
	$sql = "UPDATE `conceptopago` SET `estado`= 'ACTIVO' WHERE id=$id";

	$db = new conexion();
	$result = $db->consulta($sql);

	$respuesta->datos = [];
	$respuesta->mensaje = "";
	$respuesta->codigo = "";

	if ($result) {

		for ($i=0; $i < $num; $i++) {
			$respuesta->datos[] = mysql_fetch_array($result);
		}

		$respuesta->mensaje = "Concepto de pago activado con éxito!";
		$respuesta->codigo = 1;
	} else {
		$respuesta->mensaje = "Ha ocurrido un error!";
		$respuesta->codigo = 0;
	}

	return json_encode($respuesta);
}
function BuscarConceptopago($id) {

	$sql = "SELECT * FROM conceptopago WHERE id = $id";

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

		$respuesta->mensaje = "Ok encontrado";
		$respuesta->codigo = 1;
	} else {
		$respuesta->mensaje = "Ha ocurrido un error!";
		$respuesta->codigo = 0;
	}

	return json_encode($respuesta);
}
function ModificarConceptopago($id, $descripcion, $estado) {

	$sql = "UPDATE conceptopago SET descripcion = '$descripcion', estado = '$estado' WHERE conceptopago.id = $id";

	/*$file = fopen("prourban.log", "a");
	fwrite($file, $sql);
	fclose($file);*/

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
		$respuesta->mensaje = "No pudieron actualizarse los datos !";
		$respuesta->codigo = 0;
	}

	return json_encode($respuesta);
}
function EliminarConceptopago($id) {
	//$sql = "DELETE FROM area WHERE id=$id";
    $sql = "UPDATE `conceptopago` SET `estado` = 'INACTIVO' WHERE `id` = '$id'";

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

		$respuesta->mensaje = "Registro eliminado con éxito!";
		$respuesta->codigo = 1;
	} else {
		$respuesta->mensaje = "Ha ocurrido un error!";
		$respuesta->codigo = 0;
	}

	return json_encode($respuesta);
}
function CambiarEstadoConceptopago($id, $estado){

    $sql = "UPDATE `conceptopago` SET `estado` = '$estado' WHERE `id` = '$id'";

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



//--- FORMA DE PAGOS ---
function ListaFormapagos() {

	//obtiene el id del usuario

    $sql = "SELECT * FROM `formapago` WHERE formapago.estado = 'ACTIVO'";

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

		$respuesta->mensaje = "Forma de pagos listados";
		$respuesta->codigo = 1;
	} else {
		$respuesta->mensaje = "No existen registros de formas de pago!";
		$respuesta->codigo = 0;
	}

	return json_encode($respuesta);
}
function ListaFormapagose() {
	$sql = "SELECT * FROM `formapago` WHERE formapago.estado = 'INACTIVO'";

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

		$respuesta->mensaje = "Lista de Formas de pago desactivados";
		$respuesta->codigo = 1;
	} else {
		$respuesta->mensaje = "No existen registros de Forma de pago desactivados!";
		$respuesta->codigo = 0;
	}

	return json_encode($respuesta);
}
function InsertarFormapago($descripcion, $estado) {
	$sql = "INSERT INTO formapago (descripcion, estado)
			VALUES ('$descripcion', '$estado')";

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

		$respuesta->mensaje = "Listo";
		$respuesta->codigo = 1;
	} else {
		$respuesta->mensaje = "Datos inválidos!";
		$respuesta->codigo = 0;
	}

	return json_encode($respuesta);
}
function ActivarFormapago($id) {
	$sql = "UPDATE `formapago` SET `estado`= 'ACTIVO' WHERE id=$id";

	$db = new conexion();
	$result = $db->consulta($sql);

	$respuesta->datos = [];
	$respuesta->mensaje = "";
	$respuesta->codigo = "";

	if ($result) {

		for ($i=0; $i < $num; $i++) {
			$respuesta->datos[] = mysql_fetch_array($result);
		}

		$respuesta->mensaje = "Forma de pago activado con éxito!";
		$respuesta->codigo = 1;
	} else {
		$respuesta->mensaje = "Ha ocurrido un error!";
		$respuesta->codigo = 0;
	}

	return json_encode($respuesta);
}
function BuscarFormapago($id) {

	$sql = "SELECT * FROM formapago WHERE id = $id";

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

		$respuesta->mensaje = "Ok encontrado";
		$respuesta->codigo = 1;
	} else {
		$respuesta->mensaje = "Ha ocurrido un error!";
		$respuesta->codigo = 0;
	}

	return json_encode($respuesta);
}
function ModificarFormapago($id, $descripcion, $estado) {

	$sql = "UPDATE formapago SET descripcion = '$descripcion', estado = '$estado' WHERE formapago.id = $id";

	/*$file = fopen("prourban.log", "a");
	fwrite($file, $sql);
	fclose($file);*/

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
		$respuesta->mensaje = "No pudieron actualizarse los datos !";
		$respuesta->codigo = 0;
	}

	return json_encode($respuesta);
}
function EliminarFormapago($id) {
	//$sql = "DELETE FROM area WHERE id=$id";
    $sql = "UPDATE `formapago` SET `estado` = 'INACTIVO' WHERE `id` = '$id'";

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

		$respuesta->mensaje = "Registro eliminado con éxito!";
		$respuesta->codigo = 1;
	} else {
		$respuesta->mensaje = "Ha ocurrido un error!";
		$respuesta->codigo = 0;
	}

	return json_encode($respuesta);
}
function CambiarEstadoFormapago($id, $estado){

    $sql = "UPDATE `formapago` SET `estado` = '$estado' WHERE `id` = '$id'";

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

//FIN PARTE DE LORENA



//PARTE DE ANDRES
//--- USUARIOS ---
function ListaUsuarios() {
	//obtiene el id del usuario
    $sql = "SELECT usuario.id, usuario.nombre_usuario, persona.primer_nombre, persona.primer_apellido, usuario.estado, rol.descripcion FROM usuario INNER JOIN persona ON usuario.persona_id = persona.id INNER JOIN rol ON usuario.rol_id = rol.id WHERE usuario.estado = 'ACTIVO'";

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
		$respuesta->mensaje = "Usuarios listados";
		$respuesta->codigo = 1;
	} else {
		$respuesta->mensaje = "No existen registros de usuarios !";
		$respuesta->codigo = 0;
	}
	return json_encode($respuesta);
}
function ListaUsuariose() {
	$sql = "SELECT usuario.id, usuario.nombre_usuario, persona.primer_nombre, persona.primer_apellido, usuario.estado, rol.descripcion FROM usuario INNER JOIN persona ON usuario.persona_id = persona.id INNER JOIN rol ON usuario.rol_id = rol.id WHERE usuario.estado = 'INACTIVO'";
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

		$respuesta->mensaje = "Lista de usuarios desactivados";
		$respuesta->codigo = 1;
	} else {
		$respuesta->mensaje = "No existen registros de usuarios desactivados!";
		$respuesta->codigo = 0;
	}
	return json_encode($respuesta);
}
function InsertarUsuario($cedula, $primer_nombre, $segundo_nombre, $primer_apellido, $segundo_apellido, $telefono, $correo, $nombre_usuario, $clave) {


	$sql2 = "INSERT INTO persona (cedula, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, telefono, correo, estado)
			VALUES('$cedula', '$primer_nombre', '$segundo_nombre', '$primer_apellido', '$segundo_apellido', '$telefono', '$correo', 'ACTIVO')";

	$db = new conexion();

	$result2 = $db->consulta($sql2);


	$sql = "INSERT INTO usuario (nombre_usuario, clave, estado, persona_id, rol_id)
			VALUES('$nombre_usuario', '$clave', 'ACTIVO', (SELECT id FROM persona WHERE cedula = '$cedula'), 2)";

	$result = $db->consulta($sql);

	$respuesta->datos = [];
	$respuesta->mensaje = "";
	$respuesta->codigo = "";

	if ($result) {

		for ($i=0; $i < $num; $i++) {
			$respuesta->datos[] = mysql_fetch_array($result);
		}

		$respuesta->mensaje = "Listo";
		$respuesta->codigo = 1;
	} else {
		$respuesta->mensaje = "Datos inválidos!";
		$respuesta->codigo = 0;
	}

	return json_encode($respuesta);
}
function BuscarUsuario($id) {
	$sql = "SELECT usuario.id, persona.cedula, persona.primer_nombre,persona.segundo_nombre, persona.primer_apellido, persona.segundo_apellido, persona.telefono, persona.correo, usuario.nombre_usuario, usuario.clave FROM usuario INNER JOIN persona ON usuario.persona_id = persona.id INNER JOIN rol ON usuario.rol_id = rol.id WHERE usuario.id = $id";
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
		$respuesta->mensaje = "Ok encontrado";
		$respuesta->codigo = 1;
	} else {
		$respuesta->mensaje = "Ha ocurrido un error!";
		$respuesta->codigo = 0;
	}
	return json_encode($respuesta);
}
function ModificarUsuario($id, $cedula, $primer_nombre, $segundo_nombre, $primer_apellido, $segundo_apellido, $telefono, $correo, $nombre_usuario, $clave) {

	$sql = "UPDATE persona SET cedula = '$cedula', primer_nombre = '$primer_nombre', segundo_nombre = '$segundo_nombre', primer_apellido = '$primer_apellido', segundo_apellido = '$segundo_apellido', telefono = '$telefono', correo = '$correo', estado = '$estado', WHERE persona.id = $id";

	/*$file = fopen("prourban.log", "a");
	fwrite($file, $sql);
	fclose($file);*/

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
		$respuesta->mensaje = "No pudieron actualizarse los datos !";
		$respuesta->codigo = 0;
	}

	return json_encode($respuesta);
}
function EliminarUsuario($id) {
	$sql = "UPDATE `usuario` SET `estado`= 'INACTIVO' WHERE id=$id";

	$db = new conexion();
	$result = $db->consulta($sql);

	$respuesta->datos = [];
	$respuesta->mensaje = "";
	$respuesta->codigo = "";

	if ($result) {

		for ($i=0; $i < $num; $i++) {
			$respuesta->datos[] = mysql_fetch_array($result);
		}

		$respuesta->mensaje = "Usuario desactivado con éxito!";
		$respuesta->codigo = 1;
	} else {
		$respuesta->mensaje = "Ha ocurrido un error!";
		$respuesta->codigo = 0;
	}

	return json_encode($respuesta);
}
function ActivarUsuario($id) {
	$sql = "UPDATE `usuario` SET `estado`= 'activo' WHERE id=$id";
	$db = new conexion();
	$result = $db->consulta($sql);

	$respuesta->datos = [];
	$respuesta->mensaje = "";
	$respuesta->codigo = "";

	if ($result) {

		for ($i=0; $i < $num; $i++) {
			$respuesta->datos[] = mysql_fetch_array($result);
		}

		$respuesta->mensaje = "Usuario activado con éxito!";
		$respuesta->codigo = 1;
	} else {
		$respuesta->mensaje = "Ha ocurrido un error!";
		$respuesta->codigo = 0;
	}

	return json_encode($respuesta);
}


// *-------------- Modulo Reserva -----------------*

function listaReserva(){
    
  
    $sql = "SELECT usuario.id as id, reserva.id as id_reserva , concat(persona.primer_nombre, ' ', persona.primer_apellido) AS nombre,  reserva.fecha_reserva as fechaReserva , reserva.desde as desde , reserva.hasta as hasta , reserva.estado as estado ,area.descripcion as area 
FROM reserva
INNER JOIN usuario ON reserva.usuario_id = usuario.id  and reserva.estado = 'Pre-reserva'
INNER JOIN area ON reserva.area_id = area.id 
INNER JOIN persona ON usuario.persona_id = persona.id";
    
    //SELECT usuario.nombre_usuario  FROM reserva INNER JOIN usuario ON reserva.usuario_id = usuario.id;

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
		$respuesta->mensaje = "No existen registros de reserva!";
		$respuesta->codigo = 0;
	}

	return json_encode($respuesta);
    
    
}
function estadoReserva($id){
     $sql = "SELECT reserva.id as id, reserva.fecha_reserva as fechareserva,  reserva.desde as desde , reserva.hasta as hasta , reserva.estado as estado ,area.descripcion as area FROM reserva INNER JOIN area ON reserva.area_id = area.id and reserva.estado = 'pre-reserva'  and reserva.usuario_id = '$id'";
    
    //SELECT usuario.nombre_usuario  FROM reserva INNER JOIN usuario ON reserva.usuario_id = usuario.id;

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
		$respuesta->mensaje = "No existen registros de reserva!";
		$respuesta->codigo = 0;
	}

	return json_encode($respuesta);
    
    
    }
    

function listaReservaAceptada(){
    
  
    $sql = "SELECT usuario.id as id, reserva.id as id_reserva , concat(persona.primer_nombre, ' ', persona.primer_apellido) AS nombre,  reserva.fecha_reserva as fechaReserva , reserva.desde as desde , reserva.hasta as hasta , reserva.estado as estado ,area.descripcion as area 
FROM reserva
INNER JOIN usuario ON reserva.usuario_id = usuario.id  and reserva.estado = 'aceptada'
INNER JOIN area ON reserva.area_id = area.id 
INNER JOIN persona ON usuario.persona_id = persona.id";
    

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


function listarAreas (){
    $sql = "SELECT id, descripcion FROM area";
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
function listarAreasAdmin(){
    $sql = "SELECT id, descripcion FROM area";
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
		$respuesta->mensaje = "No existen registros de areas!";
		$respuesta->codigo = 0;
	}

	return json_encode($respuesta);
    
}




function CancelarPreReserva($id){
    $sql = "Update reserva Set estado='Libre' Where id='$id' ";

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


/*function insertarReserva($fecha, $desde, $hasta , $estado, $area ) {
	$sql = "INSERT INTO reserva (fecha_solicitud, start, desde, hasta, estado, usuario_id, area_id) 
			VALUES (CURDATE(), '$fecha', '$desde' ,'$hasta' ,'$estado' , '1' ,'$area')";*/
function insertarReserva($fecha, $desde, $hasta , $area, $id ) {
	$sql = "INSERT INTO reserva (fecha_solicitud, fecha_reserva, desde, hasta, estado, usuario_id, area_id) 
			VALUES (CURDATE(), '$fecha', '$desde' ,'$hasta' ,'pre-reserva' , '$id' ,'$area')";    

	$db = new conexion();
	$result = $db->consulta($sql);

	$respuesta->datos = [];
	$respuesta->mensaje = "";
	$respuesta->codigo = "";

	if ($result) {

		for ($i=0; $i < $num; $i++) {
			$respuesta->datos[] = mysql_fetch_array($result);
		}

		$respuesta->mensaje = "Ok se guardo";
		$respuesta->codigo = 1;
	} else {
		$respuesta->mensaje = "Datos inválidos Reserva!";
		$respuesta->codigo = 0;
	}

	return json_encode($respuesta);
}
function insertarHoraMantenimiento($fecha_inicio, $fecha_fin, $desde, $hasta , $area ) {
	$sql = "INSERT INTO horariomantenimiento (fecha_inicio, fecha_fin, desde, hasta,  area_id) VALUES ('$fecha_inicio', '$fecha_fin', '$desde' ,'$hasta' , '$area')";

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

function eliminacionAutomatica($valor){
   /* CREATE EVENT limpieza
    ON SCHEDULE AT CURRENT_TIMESTAMP + INTERVAL 1 DAY
    DO
      TRUNCATE TABLE esquema.tabla;*/
   // $sql = "CREATE EVENT limpieza ON SCHEDULE AT CURRENT_TIMESTAMP + INTERVAL 24 HOUR  DO DELETE FROM  reserva where estado = 'pre-reserva'";
           $sql =   "ALTER EVENT limpieza ON SCHEDULE EVERY '$valor' HOUR";
    
    

	$db = new conexion();
	$result = $db->consulta($sql);

	$respuesta->datos = [];
	$respuesta->mensaje = "";
	$respuesta->codigo = "";

	if ($result) {

		for ($i=0; $i < $num; $i++) {
			$respuesta->datos[] = mysql_fetch_array($result);
		}

		$respuesta->mensaje = "Ok grabo";
		$respuesta->codigo = 1;
	} else {
		$respuesta->mensaje = "Datos inválidos!";
		$respuesta->codigo = 0;
	}

	return json_encode($respuesta);
}

function guardarHora($valor){
   /* $sql = "INSERT INTO impuesto (descripcion, valor) 
			VALUES ('tiempo', '$valor')";*/
    $sql = " UPDATE parametro
SET valor='$valor'
WHERE descripcion='Reserva'";
   

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



/***********************************  Fin Funciones Reserva **********************************/


?>
