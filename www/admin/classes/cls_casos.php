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

class cls_casos {
	// Vari�veis p�blicas representando os dados da classe 
	public $id_caso ;
	public $caso ;
	public $titulo ;
	public $descricao ;
	public $resumo ;
	public $variacao_bom ;
	public $variacao_medio ;
	public $variacao_ruim ;
	public $termometro ;
	public $icone ;
	public $icone_caminho_temporario ;
	public $nome_icone_temporario ;
	public $nome_icone ;
	
	// Vari�veis privadas,s� podendo ser acessadas por essa classe
	private   $obj_connect ;
	
	// M�todo p/ instanciar o objeto de conex�o e inicializar a vari�vel status
	function __construct() { 
		$this->obj_connect = new cls_connect ;
		$this->obj_arquivo = new cls_arquivo ;
		$this->status      = false ;
	} 
	
	// M�todo p/ listar todas as casos
	public function listar_tudo() {
		$this->obj_connect->connect() ;
		$sql = "select * from vr_casos" ;
		$Rs  = $this->obj_connect->RunSql( $sql ) ;
		return( $Rs ) ;
		$this->obj_connect->disconnect() ;	
	}		
      
	// M�todo p/ listar uma caso espec�fica
	public function listar_reg( $condicao = '' ) {
		$this->obj_connect->connect() ;
		if( $condicao == "" ) {
			$sql = "select * from vr_casos where id_caso = '$this->id_caso' " ;
		}
		else {
			$sql = "select * from vr_casos " . $condicao . "ORDER BY titulo DESC" ;
		}
		$Rs  = $this->obj_connect->RunSql( $sql ) ;
		return( $Rs ) ;
		$this->obj_connect->disconnect();
	}		
      
	// M�todo p/ listar registro para ListBox
	public function listar_para_combo() {
		$this->obj_connect->connect() ;	
		$sql = "select * from vr_casos ORDER BY titulo DESC" ;
		$Rs  = $this->obj_connect->RunQry( $sql ) ;
		$this->obj_connect->disconnect() ;
		return $Rs ;
	}
	
	// M�todo p/ incluir ou alterar registros	
	function salvar() {		
		$this->obj_connect->connect() ;
   
		// inclus�o
		if( $this->id_caso == "" || $this->id_caso == 0 ) {			
		
			$this->sql    = "insert into vr_casos (titulo, descricao, resumo, termometro, variacao_bom, variacao_medio, variacao_ruim)" ;
			$this->sql    = $this->sql . " values ('$this->titulo','$this->descricao','$this->resumo','$this->termometro','$this->variacao_bom','$this->variacao_medio','$this->variacao_ruim')" ;
			$Rs           = $this->obj_connect->RunSql( $this->sql ) ;
			
			// Seleciona o id do caso cadastrado
			$maxId = $this->obj_connect->maxReg( 'id_caso', 'vr_casos' ) ;
			
			// Cria estrutura de diret�rios para o caso
			mkdir("../casos/caso_" . $maxId);
			mkdir("../casos/caso_" . $maxId . "/animacoes");
			mkdir("../casos/caso_" . $maxId . "/documentos");
			mkdir("../casos/caso_" . $maxId . "/imagens");
			mkdir("../casos/caso_" . $maxId . "/videos");
			mkdir("../casos/caso_" . $maxId . "/audio");
			
			if( $this->icone_caminho_temporario != "" ) {
				$this->nome_icone = $this->salva_img( $maxId ) ;
			}	
			
			$this->status = true ;	 						   	  		
		}  
		// altera��o
		else {
			$sql = "UPDATE vr_casos set " ;
			$sql = $sql . " titulo = '$this->titulo', " ;
			$sql = $sql . " descricao  = '$this->descricao', " ;
			$sql = $sql . " resumo  = '$this->resumo', " ;
			$sql = $sql . " termometro  = '$this->termometro', " ;
			$sql = $sql . " variacao_bom  = '$this->variacao_bom', " ;
			$sql = $sql . " variacao_medio  = '$this->variacao_medio', " ;
			$sql = $sql . " variacao_ruim  = '$this->variacao_ruim' " ;
			$sql = $sql . "WHERE id_caso = '$this->id_caso'" ;
			
			if( $this->icone_caminho_temporario != "" ) {
				$this->nome_icone = $this->salva_img( $this->id_caso ) ;
			}
			
			$Rs           = $this->obj_connect->RunSql( $sql ) ;
			$this->status = true ;
		}
		$this->obj_connect->disconnect() ;	
	}
	
	//M�todo p/ salvar imagens do �cone do indicador
	public function salva_img( $id ) {
		$this->obj_arquivo->id = $id ;
		$this->obj_arquivo->id_caso = $id ;
		$this->obj_arquivo->tipo = 5 ;
		$this->obj_arquivo->nome_arquivo_temporario    = $this->nome_icone_temporario ;
		$this->obj_arquivo->caminho_arquivo_temporario = $this->icone_caminho_temporario ;
		$this->obj_arquivo->upload() ;
		return $this->obj_arquivo->nome_arquivo ;
	}
	
	// M�todo p/ Excluir registros		
	public function excluir() {
		$this->obj_connect->connect() ;
		if( $this->id_caso != 0 || $this->id_caso != "" ) {
			$sql          = "delete from vr_casos where id_caso = '$this->id_caso'" ;
			$Rs           = $this->obj_connect->RunSql( $sql ) ;
			$this->status = true ;
		}
		$this->obj_connect->disconnect() ;		
	}

}
?>