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

class cls_indicadores {
	// Variáveis públicas representando os dados da classe 
	public $id_indicador ;
	public $nome ;
	public $descricao ;
	public $nivel_max ;
	public $nivel_min ;
	public $icone ;
	public $icone_caminho_temporario ;
	public $nome_icone_temporario ;
	public $nome_icone ;
	
	// Variáveis privadas,só podendo ser acessadas por essa classe
	private   $obj_connect ;
	protected $imagem_icone ;
	private   $obj_arquivo ;
	
	// Método p/ instanciar o objeto de conexão e inicializar a variável status
	function __construct() { 
		$this->obj_connect = new cls_connect ;
		$this->obj_arquivo = new cls_arquivo ;
		$this->status      = false ;
	} 
	
	// Método p/ listar todas os indicadores
	public function listar_tudo() {
		$this->obj_connect->connect() ;
		$sql = "select * from fr_indicadores" ;
		$Rs  = $this->obj_connect->RunSql( $sql ) ;
		return( $Rs ) ;
		$this->obj_connect->disconnect() ;	
	}		
      
	// Método p/ listar um indicador específico
	public function listar_reg( $condicao = '' ) {
		$this->obj_connect->connect() ;
		if( $condicao == "" ) {
			$sql = "select * from fr_indicadores where id_indicador = '$this->id_indicador' " ;
		}
		else {
			$sql = "select * from fr_indicadores " . $condicao . "ORDER BY nome" ;
		}
		$Rs  = $this->obj_connect->RunSql( $sql ) ;
		return( $Rs ) ;
		$this->obj_connect->disconnect();
	}
	
	// Método p/ listar registro para ListBox
	public function listar_para_combo() {
		$this->obj_connect->connect() ;	
		$sql = "select id_indicador,nome from fr_indicadores ORDER BY nome" ;
		$Rs  = $this->obj_connect->RunQry( $sql ) ;
		/*while( $row_cbo = mysql_fetch_assoc( $Rs ) ) {
			$id   = $row_cbo['id_indicador'] ;
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
		if( $this->id_indicador == "" || $this->id_indicador == 0 ) {			   
			if( $this->icone_caminho_temporario != "" ) {
				$maxId = $this->obj_connect->maxReg( 'id_indicador', 'fr_indicadores' ) ;
				$maxId++ ;
				$this->nome_icone = $this->salva_img( $maxId ) ;
			}	
			$this->sql    = "insert into fr_indicadores (nome, descricao, nivel_max, nivel_min)" ;
			$this->sql    = $this->sql . " values ('$this->nome','$this->descricao','$this->nivel_max','$this->nivel_min')" ;
			$Rs           = $this->obj_connect->RunSql( $this->sql ) ;
			$this->status = true ;	 						   	  		
		}  
		// alteração
		else {
			$sql = "UPDATE fr_indicadores set " ;
			$sql = $sql . " nome   = '$this->nome', " ;
			$sql = $sql . " descricao = '$this->descricao', " ;
			$sql = $sql . " nivel_max= '$this->nivel_max', " ;
			$sql = $sql . " nivel_min  = '$this->nivel_min'" ;
			
			if( $this->icone_caminho_temporario != "" ) {
				$this->nome_icone = $this->salva_img( $this->id_indicador ) ;
			}
			
			$sql = $sql . "WHERE id_indicador = '$this->id_indicador'" ;
			$Rs           = $this->obj_connect->RunSql( $sql ) ;
			$this->status = true ;
		}
		$this->obj_connect->disconnect() ;	
	}
	
	//Método p/ salvar imagens do ícone do indicador
	public function salva_img( $id ) {
		$this->obj_arquivo->id = $id ;
		$this->obj_arquivo->tipo = 1 ;
		$this->obj_arquivo->nome_arquivo_temporario    = $this->nome_icone_temporario ;
		$this->obj_arquivo->caminho_arquivo_temporario = $this->icone_caminho_temporario ;
		$this->obj_arquivo->upload() ;
		return $this->obj_arquivo->nome_arquivo ;
	}
	
	// Método p/ Excluir registros		
	public function excluir() {
		$this->obj_connect->connect() ;
		if( $this->id_indicador != 0 || $this->id_indicador != "" ) {
			//$this->excluir_foto( $this->id_indicador ) ;
			$sql          = "delete from fr_indicadores where id_indicador = '$this->id_indicador'" ;
			$Rs           = $this->obj_connect->RunSql( $sql ) ;
			$this->status = true ;
		}
		$this->obj_connect->disconnect() ;		
	}
	
	// Excluir 
	private function excluir_foto( $id ) {
		$this->obj_arquivo->tipo         = 4 ;
		$this->obj_arquivo->nome_arquivo = "icone_indicador_" . $id . ".swf" ;
		$this->obj_arquivo->delfile() ;
	}
}
?>