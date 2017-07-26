<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Conexion
 *
 * @author Lissenia
 */
class Conexion {
    public $usuario;
    public $contrasena;
    public $nombrehost;
    public $basedatos;
    public $nombrebase;
    private $_enlace;
    
    public function __construct() {

        $this->nombrehost = "localhost"; // localhost:3306
        $this->usuario = "root"; //prourbanAMI //
        $this->contrasena = ""; //prourbanAMI //
        $this->basedatos = "prourban"; //admoux_prourban //

        $this->_enlace = mysql_connect($this->nombrehost, $this->usuario, $this->contrasena);
        if (!$this->_enlace) {
            die('Could not connect: ' . mysql_error());
        } else {

            $base = mysql_select_db($this->basedatos, $this->_enlace);
            if (!$base)
                die('Error en seleccion de base de datos: ' . mysql_error());
        }
    }
    
    /* ejecucion de consulta regresa un array */

    public function consulta_arr($sql) {

        $rsql = mysql_query($sql);
        if ($rsql) {
            $arsql = mysql_fetch_array($rsql);
            return $arsql;
        } else {
            return 0;
        }
    }
    
    /* ejecucion de consulta regresa un objeto */

    public function consulta_obj($sql) {

        $rsql = mysql_query($sql);
        if ($rsql) {
            $orsql = mysql_fetch_object($rsql);
            return $orsql;
        } else {
            return 0;
        }
    }
    
    /* ejecucion de consulta */

    public function consulta($sql) {

        $rsql = mysql_query($sql, $this->_enlace);


        if ($rsql) {

            return $rsql;
        } else {
            return 0;
        }
    }
    
     public function afectadas() {
        $afectadas = mysql_affected_rows($this->_enlace);
        return $afectadas;
    }

    public function encontradas($rsql) {

        $filas = mysql_num_rows($rsql);

        return $filas;
    }

    public function desconecta() {
        mysql_close($this->_enlace);
    }

    function limpiasql($val) {

        if (get_magic_quotes_gpc()) {
            $val = stripslashes($val);
        } elseif (function_exists("mysql_real_escape_string")) {
            $val = mysql_real_escape_string($val, $this->_enlace);
        } else {
            $val = addslashes($val);
        }
        return $val;
    }

    function begin() {
        $rsql = mysql_query("START TRANSACTION", $this->_enlace);


        if ($rsql) {

            return $rsql;
        } else {
            return 0;
        }
    }

    function commit() {
        $rsql = mysql_query("COMMIT", $this->_enlace);

        if ($rsql) {

            return $rsql;
        } else {
            return 0;
        }
    }

    function rollback() {
        $rsql = mysql_query("ROLLBACK", $this->_enlace);


        if ($rsql) {

            return $rsql;
        } else {
            return 0;
        }
    }
}

?>
