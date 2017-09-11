<?php

include($_SERVER["DOCUMENT_ROOT"] . '/prourban-ws/db/conexion.php');


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
function ListaDeudasUsuarios($nombrexBuscar) {

	if ($nombrexBuscar === "nulo") {
		$sql = "SELECT usuario.id, persona.primer_nombre, persona.primer_apellido, cuentaxcobrar.fecha_maxima_pago, cuentaxcobrar.estado
						FROM cuentaxcobrar
						INNER JOIN usuario ON usuario.id = cuentaxcobrar.usuario_id
						INNER JOIN persona ON persona.id = usuario.persona_id";
	}else {
		$sql = "SELECT usuario.id, persona.primer_nombre, persona.primer_apellido, cuentaxcobrar.fecha_maxima_pago, cuentaxcobrar.estado
						FROM cuentaxcobrar
						INNER JOIN usuario ON usuario.id = cuentaxcobrar.usuario_id
						INNER JOIN persona ON persona.id = usuario.persona_id
						where CONCAT (persona.primer_nombre, ' ' ,persona.primer_apellido) like '%$nombrexBuscar%' AND cuentaxcobrar.estado = 'pendiente'";
	}
	//obtiene el id del usuario


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
		$respuesta->mensaje = $sql;
		$respuesta->codigo = 0;
	}

	return json_encode($respuesta);

}



?>
