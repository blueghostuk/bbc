<?php
/**
 * DB Connection for winbeta.
 * Web Location: http://winbeta.blueghost.co.uk
 * Version 0.1
 * Date:   0.1 - 15/06/2004
 * Copyright Michael Pritchard 2004
 * Web: http://www.blueghost.co.uk
 */
 
class DB_Connection{
	var $table;
	var $dbc;
	
	function DB_connection(){
		//blank
	}
	
	function DB_setLink($link){
		$this->dbc = $link;
	}
	
	function DB_connect($host,$user,$password,$tbl){
		$this->dbc		= mysql_connect($host,$user,$password);
		$db_table 		= mysql_select_db($tbl, $this->dbc);
		$this->table	= $tbl;
		return $this->dbc;
	}
	
	function DB_disconnect(){
		mysql_close($this->dbc);
	}
	
	function DB_disconnectLink($link){
		mysql_close($link);
	}
	
	function DB_search($sql){
		return mysql_query($sql, $this->dbc);
	}
	
	function DB_array($link){
		return mysql_fetch_array($link);
	}
	
	function DB_num_results($link){
		return mysql_num_rows($link);
	}
	
	function DB_row($link){
		return  mysql_fetch_row($link);
	}
	
	function DB_result($link, $row){
		return mysql_result($link, $row);
	}
	
	function DB_delete_count($sql){
		mysql_query($sql, $this->dbc);
		return mysql_affected_rows();
	}
	
	function DB_affected_rows($link){
		return mysql_affected_rows($link);
	}
}	
?>