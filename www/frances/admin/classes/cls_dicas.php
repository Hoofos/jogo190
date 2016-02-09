<?php

//********************** Evitando duplica��o de c�digo. ***********************
$included_flag = 'INCLUDE_' . basename ( __FILE__ ) ;
if ( ! defined ( $included_flag ) ) {
    define ( $included_flag, TRUE ) ;
}
else {
    // C�digo j� foi incluido.
    return ( TRUE ) ;
}
//*****************************************************************************

class cls_dicas {
	// Vari�veis p�blicas representando os dados da classe 
	public $id_dica ;
	public $id_caso ;
	public $id_situacao ;
	public $titulo ;
	public $texto ;
	
	// Vari�veis privadas,s� podendo ser acessadas por essa classe
	private   $obj_connect ;
	
	// M�todo p/ instanciar o objeto de conex�o e inicializar a vari�vel status
	function __construct() { 
		$this->obj_connect = new cls_connect ;
		$this->obj_arquivo = new cls_arquivo ;
		$this->status      = false ;
	} 
	
	// M�todo p/ listar todas as dicas
	public function listar_tudo() {
		$this->obj_connect->connect() ;
		$sql = "select * from fr_dicas" ;
		$Rs  = $this->obj_connect->RunSql( $sql ) ;
		return( $Rs ) ;
		$this->obj_connect->disconnect() ;	
	}		
      
	// M�todo p/ listar uma dica espec�fica
	public function listar_reg( $condicao = '' ) {
		$this->obj_connect->connect() ;
		if( $condicao == "" ) {
			$sql = "select * from fr_dicas where id_dica = '$this->id_dica' " ;
		}
		else {
			$sql = "select * from fr_dicas " . $condicao . "ORDER BY titulo DESC" ;
		}
		$Rs  = $this->obj_connect->RunSql( $sql ) ;
		return( $Rs ) ;
		$this->obj_connect->disconnect();
	}
	
	// M�todo p/ listar registro para ListBox
	public function listar_para_combo() {
		$this->obj_connect->connect() ;	
		$sql = "select * from fr_dicas ORDER BY titulo DESC" ;
		$Rs  = $this->obj_connect->RunQry( $sql ) ;
		$this->obj_connect->disconnect() ;
		return $Rs ;
	}
	
	// M�todo p/ incluir ou alterar registros	
	function salvar() {		
		$this->obj_connect->connect() ;
   
		// inclus�o
		if( $this->id_dica == "" || $this->id_dica == 0 ) {			   
			if( $this->et_imagem_caminho_temporario != "" ) {
				$maxId = $this->obj_connect->maxReg( 'id_dica', 'dicas' ) ;
				$maxId++ ;
				$this->et_nome_imagem = $this->salva_img( $maxId ) ;
			}	
			$this->sql    = "insert into fr_dicas (id_caso,id_situacao, titulo, texto)" ;
			$this->sql    = $this->sql . " values ('$this->id_caso','$this->id_situacao','" . str_replace("'","`",$this->titulo) . "','" . str_replace("'","`",$this->texto) . "')" ;
			$Rs           = $this->obj_connect->RunSql( $this->sql ) ;
			$this->status = true ;	 						   	  		
		}  
		// altera��o
		else {
			$sql = "UPDATE fr_dicas set " ;
			$sql = $sql . " id_caso   = '$this->id_caso', " ;
			$sql = $sql . " id_situacao   = '$this->id_situacao', " ;
			$sql = $sql . " titulo = '" . str_replace("'","`",$this->titulo) . "', " ;
			$sql = $sql . " texto  = '" . str_replace("'","`",$this->texto) . "' " ;
			$sql = $sql . "WHERE id_dica = '$this->id_dica'" ;
			$Rs           = $this->obj_connect->RunSql( $sql ) ;
			$this->status = true ;
		}
		$this->obj_connect->disconnect() ;	
	}
	
	// M�todo p/ Excluir registros		
	public function excluir() {
		$this->obj_connect->connect() ;
		if( $this->id_dica != 0 || $this->id_dica != "" ) {
			$this->excluir_foto( $this->id_dica ) ;
			$sql          = "delete from fr_dicas where id_dica = '$this->id_dica'" ;
			$Rs           = $this->obj_connect->RunSql( $sql ) ;
			$this->status = true ;
		}
		$this->obj_connect->disconnect() ;		
	}

}
?>