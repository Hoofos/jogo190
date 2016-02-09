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

class cls_opcoes {
	// Variáveis públicas representando os dados da classe 
	public $id_opcao ;
	public $id_caso ;
	public $id_situacao ;
	public $id_situacao_destino ;
	public $titulo ;
	public $texto ;
	public $aviso ;
	public $tipo ;
	
	// Método p/ instanciar o objeto de conexão e inicializar a variável status
	function __construct() { 
		$this->obj_connect = new cls_connect ;
		$this->obj_arquivo = new cls_arquivo ;
		$this->status      = false ;
	} 
	
	// Método p/ listar todas os opcoes
	public function listar_tudo() {
		$this->obj_connect->connect() ;
		$sql = "select * from vr_opcoes" ;
		$Rs  = $this->obj_connect->RunSql( $sql ) ;
		return( $Rs ) ;
		$this->obj_connect->disconnect() ;	
	}		
      
	// Método p/ listar um opcao específico
	public function listar_reg( $condicao = '' ) {
		$this->obj_connect->connect() ;
		if( $condicao == "" ) {
			$sql = "select * from vr_opcoes where id_opcao = '$this->id_opcao' " ;
		}
		else {
			$sql = "select * from vr_opcoes " . $condicao . "ORDER BY titulo" ;
		}
		$Rs  = $this->obj_connect->RunSql( $sql ) ;
		return( $Rs ) ;
		$this->obj_connect->disconnect();
	}
	
	// Método p/ listar registro para ListBox
	public function listar_para_combo() {
		$this->obj_connect->connect() ;	
		$sql = "select id_opcao,titulo from vr_opcoes ORDER BY titulo" ;
		$Rs  = $this->obj_connect->RunQry( $sql ) ;
		/*while( $row_cbo = mysql_fetch_assoc( $Rs ) ) {
			$id   = $row_cbo['id_opcao'] ;
			$nome = $row_cbo['et_titulo'] ;
			$scbo = $scbo . "<Option value = $id >$nome</Option>" ;
		}*/
		$this->obj_connect->disconnect() ;
		return( $Rs ) ; //$scbo
	}
	
	// Método p/ incluir ou alterar registros	
	function salvar() {		
		$this->obj_connect->connect() ;
   
		// inclusão
		if( $this->id_opcao == "" || $this->id_opcao == 0 ) {			   
			$this->sql    = "insert into vr_opcoes (id_caso, id_situacao, id_situacao_destino, titulo, texto, aviso, tipo)" ;
			$this->sql    = $this->sql . " values ('$this->id_caso','$this->id_situacao','$this->id_situacao_destino','$this->titulo','$this->texto','$this->aviso','$this->tipo')" ;
			$Rs           = $this->obj_connect->RunSql( $this->sql ) ;
			 	
			$this->status = true ;				   	  		
		}  
		// alteração
		else {
			$sql = "UPDATE vr_opcoes set " ;
			$sql = $sql . " id_caso  = $this->id_caso', " ;
			$sql = $sql . " id_situacao  = '$this->id_situacao', " ;
			$sql = $sql . " id_situacao_destino  = '$this->id_situacao_destino', " ;
			$sql = $sql . " titulo   = '$this->titulo', " ;
			$sql = $sql . " texto = '$this->texto', " ;
			$sql = $sql . " aviso= '$this->aviso', " ;
			$sql = $sql . " tipo= '$this->tipo'" ;
			
			$sql = $sql . " WHERE id_opcao = '$this->id_opcao'" ;
			$Rs           = $this->obj_connect->RunSql( $sql ) ;
			$this->status = true ;
		}
		$this->obj_connect->disconnect() ;	
	}
	
	// Método p/ Excluir registros		
	public function excluir() {
		$this->obj_connect->connect() ;
		if( $this->id_opcao != 0 || $this->id_opcao != "" ) {
			//$this->excluir_foto( $this->id_opcao ) ;
			$sql          = "delete from vr_opcoes where id_opcao = '$this->id_opcao'" ;
			$Rs           = $this->obj_connect->RunSql( $sql ) ;
			$this->status = true ;
		}
		$this->obj_connect->disconnect() ;		
	}

}
?>