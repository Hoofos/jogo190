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

class cls_campanhas {
	// Variбveis pъblicas representando os dados da classe 
	public $id_campanha ;
	public $titulo ;
	public $descricao ;
	
	// Variбveis privadas,sу podendo ser acessadas por essa classe
	private   $obj_connect ;
	
	// Mйtodo p/ instanciar o objeto de conexгo e inicializar a variбvel status
	function __construct() { 
		$this->obj_connect = new cls_connect ;
		$this->status      = false ;
	} 
	
	// Mйtodo p/ listar todas as campanhas
	public function listar_tudo() {
		$this->obj_connect->connect() ;
		$sql = "select * from fr_campanhas" ;
		$Rs  = $this->obj_connect->RunSql( $sql ) ;
		return( $Rs ) ;
		$this->obj_connect->disconnect() ;	
	}		
      
	// Mйtodo p/ listar uma campanha especнfica
	public function listar_reg( $concampanhao = '' ) {
		$this->obj_connect->connect() ;
		if( $concampanhao == "" ) {
			$sql = "select * from fr_campanhas where id_campanha = '$this->id_campanha' " ;
		}
		else {
			$sql = "select * from fr_campanhas " . $concampanhao . "ORDER BY titulo DESC" ;
		}
		$Rs  = $this->obj_connect->RunSql( $sql ) ;
		return( $Rs ) ;
		$this->obj_connect->disconnect();
	}
	
	// Mйtodo p/ listar registro para ListBox
	public function listar_para_combo() {
		$this->obj_connect->connect() ;	
		$sql = "select * from fr_campanhas ORDER BY titulo DESC" ;
		$Rs  = $this->obj_connect->RunQry( $sql ) ;
		$this->obj_connect->disconnect() ;
		return $Rs ;
	}
	
	// Mйtodo p/ incluir ou alterar registros	
	function salvar() {		
		$this->obj_connect->connect() ;
   
		// inclusгo
		if( $this->id_campanha == "" || $this->id_campanha == 0 ) {			   
			if( $this->et_imagem_caminho_temporario != "" ) {
				$maxId = $this->obj_connect->maxReg( 'id_campanha', 'campanhas' ) ;
				$maxId++ ;
				$this->et_nome_imagem = $this->salva_img( $maxId ) ;
			}	
			$this->sql    = "insert into fr_campanhas (titulo, descricao)" ;
			$this->sql    = $this->sql . " values ('$this->titulo','$this->descricao')" ;
			$Rs           = $this->obj_connect->RunSql( $this->sql ) ;
			$this->status = true ;	 						   	  		
		}  
		// alteraзгo
		else {
			$sql = "UPDATE fr_campanhas set " ;
			$sql = $sql . " titulo = '$this->titulo', " ;
			$sql = $sql . " descricao  = '$this->descricao' " ;
			
			$sql = $sql . "WHERE id_campanha = '$this->id_campanha'" ;
			$Rs           = $this->obj_connect->RunSql( $sql ) ;
			$this->status = true ;
		}
		$this->obj_connect->disconnect() ;	
	}
	
	// Mйtodo p/ Excluir registros		
	public function excluir() {
		$this->obj_connect->connect() ;
		if( $this->id_campanha != 0 || $this->id_campanha != "" ) {
			$this->excluir_foto( $this->id_campanha ) ;
			$sql          = "delete from fr_campanhas where id_campanha = '$this->id_campanha'" ;
			$Rs           = $this->obj_connect->RunSql( $sql ) ;
			$sql          = "delete from fr_campanhas_casos where id_campanha = '$this->id_campanha'" ;
			$Rs           = $this->obj_connect->RunSql( $sql ) ;
			$this->status = true ;
		}
		$this->obj_connect->disconnect() ;		
	}

}
?>