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

class cls_documentos {
	// Variáveis públicas representando os dados da classe 
	public $id_documento ;
	public $id_caso ;
	public $arquivo ;
	public $url ;
	public $titulo ;
	public $titulo_adaptado ;
	public $descricao ;
	public $palavras_chave ;
	public $arquivo_caminho_temporario ;
	public $nome_arquivo_temporario ;
	
	// Variáveis privadas,só podendo ser acessadas por essa classe
	private   $obj_connect ;
	private   $obj_arquivo ;
	
	// Método p/ instanciar o objeto de conexão e inicializar a variável status
	function __construct() { 
		$this->obj_connect = new cls_connect ;
		$this->obj_arquivo = new cls_arquivo ;
		$this->status      = false ;
	} 
	
	// Método p/ listar todas os documentos
	public function listar_tudo() {
		$this->obj_connect->connect() ;
		$sql = "select * from vr_documentos" ;
		$Rs  = $this->obj_connect->RunSql( $sql ) ;
		return( $Rs ) ;
		$this->obj_connect->disconnect() ;	
	}		
      
	// Método p/ listar um documento específico
	public function listar_reg( $condicao = '' ) {
		$this->obj_connect->connect() ;
		if( $condicao == "" ) {
			$sql = "select * from vr_documentos where id_documento = '$this->id_documento' " ;
		}
		else {
			$sql = "select * from vr_documentos " . $condicao . "ORDER BY titulo_adaptado" ;
		}
		$Rs  = $this->obj_connect->RunSql( $sql ) ;
		return( $Rs ) ;
		$this->obj_connect->disconnect();
	}
	
	// Método p/ listar registro para ListBox
	public function listar_para_combo() {
		$this->obj_connect->connect() ;	
		$sql = "select * from vr_documentos ORDER BY nome" ;
		$Rs  = $this->obj_connect->RunQry( $sql ) ;
		/*while( $row_cbo = mysql_fetch_assoc( $Rs ) ) {
			$id   = $row_cbo['id_documento'] ;
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
		if( $this->id_documento == "" || $this->id_documento == 0 ) {			   
			if( $this->arquivo_caminho_temporario != "" ) {
				$maxId = $this->obj_connect->maxReg( 'id_documento', 'vr_documentos' ) ;
				$maxId++ ;
				$this->nome_icone = $this->salva_arquivo( $maxId ) ;
			}	
			$this->sql    = "insert into vr_documentos (id_caso, nome, url, descricao, titulo, titulo_adaptado, palavras_chave)" ;
			$this->sql    = $this->sql . " values ('$this->id_caso','$this->arquivo','$this->url','$this->descricao','$this->titulo','$this->titulo_adaptado','$this->palavras_chave')" ;
			$Rs           = $this->obj_connect->RunSql( $this->sql ) ;
			$this->status = true ;	 						   	  		
		}  
		// alteração
		else {
			$sql = "UPDATE vr_documentos set " ;
			$sql = $sql . " id_caso  = '$this->id_caso', " ;
			$sql = $sql . " nome   = '$this->arquivo', " ;
			$sql = $sql . " url   = '$this->url', " ;
			$sql = $sql . " titulo   = '$this->titulo', " ;
			$sql = $sql . " titulo_adaptado   = '$this->titulo_adaptado', " ;
			$sql = $sql . " descricao = '$this->descricao', " ;
			$sql = $sql . " palavras_chave= '$this->palavras_chave'" ;
			
			if( $this->arquivo_caminho_temporario != "" ) {
				$this->nome = $this->salva_arquivo( $id ) ;
			}
			
			$sql = $sql . "WHERE id_documento = '$this->id_documento'" ;
			$Rs           = $this->obj_connect->RunSql( $sql ) ;
			$this->status = true ;
		}
		$this->obj_connect->disconnect() ;	
	}
	
	//Método p/ salvar imagens do ícone do documento
	public function salva_arquivo( $id ) {
		$this->obj_arquivo->id = $id ;
		$this->obj_arquivo->id_caso = $this->id_caso ;
		$this->obj_arquivo->tipo = 4 ;
		$this->obj_arquivo->nome_arquivo_temporario    = $this->nome_arquivo_temporario ;
		$this->obj_arquivo->caminho_arquivo_temporario = $this->arquivo_caminho_temporario ;
		$this->obj_arquivo->upload() ;
		return $this->obj_arquivo->nome_arquivo ;
	}
	
	// Método p/ Excluir registros		
	public function excluir() {
		$this->obj_connect->connect() ;
		if( $this->id_documento != 0 || $this->id_documento != "" ) {
			//$this->excluir_foto( $this->id_documento ) ;
			$sql          = "delete from vr_documentos where id_documento = '$this->id_documento'" ;
			$Rs           = $this->obj_connect->RunSql( $sql ) ;
			$this->status = true ;
		}
		$this->obj_connect->disconnect() ;		
	}
	
	// Excluir 
	private function excluir_foto( $id ) {
		$this->obj_arquivo->tipo         = 4 ;
		$this->obj_arquivo->nome_arquivo = "icone_documento_" . $id . ".swf" ;
		$this->obj_arquivo->delfile() ;
	}
}
?>