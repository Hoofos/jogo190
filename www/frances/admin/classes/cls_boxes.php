<?php

//********************** Evitando duplicaзгo de cуdigo. ***********************
$included_flag = 'INCLUDE_' . basename ( __FILE__ ) ;
if ( ! defined ( $included_flag ) ) {
    define ( $included_flag, TRUE ) ;
}
else {
    // Cуdigo jб foi incluido.
    return ( TRUE ) ;
}
//*****************************************************************************

class cls_boxes {
	// Variбveis pъblicas representando os dados da classe 
	public $id_box ;
	public $id_caso ;
	public $titulo_1 ;
	public $fontes_1 ;
	public $conteudo_1 ;
	public $titulo_2 ;
	public $fontes_2 ;
	public $conteudo_2 ;	
	
	// Variбveis privadas,sу podendo ser acessadas por essa classe
	private   $obj_connect ;
	
	// Mйtodo p/ instanciar o objeto de conexгo e inicializar a variбvel status
	function __construct() { 
		$this->obj_connect = new cls_connect ;
		$this->obj_arquivo = new cls_arquivo ;
		$this->status      = false ;
	} 
	
	// Mйtodo p/ listar todas as boxes
	public function listar_tudo() {
		$this->obj_connect->connect() ;
		$sql = "select * from fr_boxes" ;
		$Rs  = $this->obj_connect->RunSql( $sql ) ;
		return( $Rs ) ;
		$this->obj_connect->disconnect() ;	
	}		
      
	// Mйtodo p/ listar uma box especнfica
	public function listar_reg( $conboxo = '' ) {
		$this->obj_connect->connect() ;
		if( $conboxo == "" ) {
			$sql = "select * from fr_boxes where id_box = '$this->id_box' " ;
		}
		else {
			$sql = "select * from fr_boxes " . $conboxo . " ORDER BY id_box" ;
		}
		$Rs  = $this->obj_connect->RunSql( $sql ) ;
		return( $Rs ) ;
		$this->obj_connect->disconnect();
	}
	
	// Mйtodo p/ listar registro para ListBox
	public function listar_para_combo() {
		$this->obj_connect->connect() ;	
		$sql = "select * from fr_boxes ORDER BY id_box" ;
		$Rs  = $this->obj_connect->RunQry( $sql ) ;
		$this->obj_connect->disconnect() ;
		return $Rs ;
	}
	
	// Mйtodo p/ incluir ou alterar registros	
	function salvar() {		
		$this->obj_connect->connect() ;
   
		// inclusгo
		if( $this->id_box == "" || $this->id_box == 0 ) {			   
			$this->sql    = "insert into fr_boxes (id_caso, titulo_1, fontes_1, conteudo_1, titulo_2, fontes_2, conteudo_2)" ;
			$this->sql    = $this->sql . " values ('$this->id_caso','" . str_replace("'","`",$this->titulo_1) . "','" . str_replace("'","`",$this->fontes_1) . "','" . str_replace("'","`",$this->conteudo_1) . "','" . str_replace("'","`",$this->titulo_2) . "','" . str_replace("'","`",$this->fontes_2) . "','" . str_replace("'","`",$this->conteudo_2) . "')" ;
			$Rs           = $this->obj_connect->RunSql( $this->sql ) ;
			$this->status = true ;	 						   	  		
		}  
		// alteraзгo
		else {
			$sql = "UPDATE fr_boxes set " ;
			$sql = $sql . " id_caso   = '$this->id_caso', " ;
			$sql = $sql . " titulo_1 = '" . str_replace("'","`",$this->titulo_1) . "', " ;
			$sql = $sql . " fontes_1  = '" . str_replace("'","`",$this->fontes_1) . "', " ;
			$sql = $sql . " conteudo_1 = '" . str_replace("'","`",$this->conteudo_1) . "', " ;
			$sql = $sql . " titulo_2  = '" . str_replace("'","`",$this->titulo_2) . "', " ;
			$sql = $sql . " fontes_2 = '" . str_replace("'","`",$this->fontes_2) . "', " ;
			$sql = $sql . " conteudo_2  = '" . str_replace("'","`",$this->conteudo_2) . "' " ;
			$sql = $sql . "WHERE id_box = '$this->id_box'" ;
			$Rs           = $this->obj_connect->RunSql( $sql ) ;
			$this->status = true ;
		}
		$this->obj_connect->disconnect() ;	
	}
	
	// Mйtodo p/ Excluir registros		
	public function excluir() {
		$this->obj_connect->connect() ;
		if( $this->id_box != 0 || $this->id_box != "" ) {
			$sql          = "delete from fr_boxes where id_box = '$this->id_box'" ;
			$Rs           = $this->obj_connect->RunSql( $sql ) ;
			$this->status = true ;
		}
		$this->obj_connect->disconnect() ;		
	}

}
?>