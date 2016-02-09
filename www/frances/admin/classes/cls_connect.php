<?php

//********************** Evitando duplicação de código. ***********************
$included_flag = 'INCLUDE_' . basename ( __FILE__ ) ;
if ( ! defined ( $included_flag ) ) {
    define ( $included_flag, TRUE ) ;
}
else {
    // Código já foi incluido.
    return ( TRUE ) ;
}
//*****************************************************************************

/* Arquivo p/ tratamento da BASE DE DADOS. */
include ( "include/environment.php" ) ;

function conexao( $sql ) {	
	 $conexao    = mysql_connect  ( HOSTNAME, USERNAME, PASSWORD ) or die( condRetErrSGBD ) ;
	 $db         = mysql_select_db( DATABASE, $conexao ) or die( condRetErrSlct  ) ;
	 $sql_result = mysql_query    ( $sql    , $conexao ) or die( condRetErrQuery ) ; 	
	 return( $sql_result ) ;
	 mysql_close( $conexao ) ;
}
 
// Criacao da classe cls_connect
// Obj.: Conectar , Desconectar e executar comandos no banco
class cls_connect {
	// Publicas	   
	public $connect ;
   
	// Metodo construtor padrao
	function __construct() {
		$this->connect = false ;
	}
	
	// Conecta com o banco
	public function connect() {
		$this->conexao = mysql_connect( HOSTNAME, USERNAME, PASSWORD ) or die( condRetErrSGBD ) ;
		$db = mysql_select_db( DATABASE, $this->conexao ) or die( condRetErrSlct ) ;
		$this->connect = true ;
	}
	
	// Executa comandos SQL no banco
	// Obs : depende do metodo  connect()
	// Parametros : ($sql) comando Sql
	public function RunSql( $sql ) {
		$sql_result = mysql_query( $sql, $this->conexao ) or die ( condRetErrQuery . " " . $sql ) ;
		return( $sql_result ) ;
	}
	
	// Executa comandos SQL conectando  e desconectando com o BANCO
	// Obs : não depende do método connect() para rodar
	public function RunQry( $sql ) {
		$this->connect() ;
		return( $this->RunSql( $sql ) ) ;
		$this->disconnect() ;
	}		
	
	// Desconecata com o Banco		
	public function disconnect() { 
		mysql_close( $this->conexao ) ;  
		$this->connect = false ;
	}
	
	// Retorna o chave de maior numero da tabela.
	public function maxReg( $campo, $tabela ) {
		$sql     = "select max(" . $campo . ") as maxId from " . $tabela . "" ;
		$Rs      = $this->RunQry( $sql ) ;
		$row_max = mysql_fetch_assoc( $Rs ) ;
		$maxId   = $row_max['maxId'] ;
		return( $maxId ) ;
	}
	
} 
?>

