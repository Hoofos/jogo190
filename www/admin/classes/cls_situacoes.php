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

class cls_situacoes
{
		
	// Vari�veis p�blicas representando os dados da classe 
	
		public $id_situacao;
		public $id_caso;
		public $identificador;
		public $descricao;
		public $problematica_boa;
		public $problematica_media;
		public $problematica_ruim;
		public $primeira_situacao;
		public $situacao_final;
		public $narrativa_final;
		public $status;
		
		// Caso um dia precisemos gravar o nome do arquivo na base, estas vari�veis ir�o servir pra isso
		public $objeto_1 ;
		public $objeto_2 ;
		public $objeto_3 ;
	
		public $nome_objeto_1_temporario;
		public $caminho_objeto_1_temporario;
		
		public $nome_objeto_2_temporario;
		public $caminho_objeto_2_temporario;
		
		public $nome_objeto_3_temporario;
		public $caminho_objeto_3_temporario;

	
	// Vari�veis privadas,s� podendo ser acessadas por essa classe
	
		private $obj_connect;
		private $obj_arquivo ;
		
	// M�todo p/ instanciar o objeto de conex�o e inicializar a vari�vel status
		
		function __construct() 
    	{ 
			$this->obj_connect = new cls_connect;
			$this->obj_arquivo = new cls_arquivo ;
			$this->status = false;
    	} 
	
	// M�todo p/ listar todas as entrevistas
		
		public function listar_tudo()
		{
			$this->obj_connect->connect();				
			$sql = "select * from vr_situacoes";			
			$Rs = $this->obj_connect->RunSql($sql);
			return ($Rs);
			$this->obj_connect->disconnect();	
		}		
      
	// M�todo p/ listar uma entrevista espec�fica
	public function listar_reg($condicao = '')	
	{
		$this->obj_connect->connect();	
		if($condicao == "")
		{
			$sql = "select * from vr_situacoes where id_situacao = '$this->id_situacao' ";
			$Rs = $this->obj_connect->RunSql($sql);
			return ($Rs);
		}
		else
		{
			$sql = "select * from vr_situacoes " . $condicao . " ORDER BY identificador";
			$Rs = $this->obj_connect->RunSql($sql);
			return ($Rs);
		}
		$this->obj_connect->disconnect();
	}
	
	
	// M�todo p/ listar registro para ListBox
		
		public function listar_para_combo()
		{
			$this->obj_connect->connect();	
			$sql = "select * from vr_situacoes ORDER BY identificador";
		 	$Rs = $this->obj_connect->RunQry($sql);
		 	/*while ($row_cbo = mysql_fetch_assoc($Rs))
		 	{
				$id = $row_cbo['id_situacao'];
				$nome = $row_cbo['et_titulo'];
		 		$scbo = $scbo . "<Option value = $id >$nome</Option>";
		 	}*/
		 	$this->obj_connect->disconnect();
			return ($Rs);
		}
	
	
	// M�todo p/ incluir ou alterar registros	
	
		function salvar()
		{		

       		$this->obj_connect->connect();
	   
	   		// inclus�o
	   		if($this->id_situacao == "" || $this->id_situacao == 0) 
	   		{
				// Caso esta situa��o seja marcada como primeira situa��o
				// temos primeiro que desmarcar qualquer situa��o que esteja
				// marcada como primeira op��o para este caso
				if ( $this->primeira_situacao == 'Y' ) {
					$sql = "UPDATE vr_situacoes SET primeira_situacao='N' WHERE id_caso='$this->id_caso'" ;
					$Rs = $this->obj_connect->RunSql($sql);
				}

		   		$this->sql = "insert into vr_situacoes (id_caso, identificador, descricao, primeira_situacao, situacao_final, final,problematica_boa, problematica_media, problematica_ruim)";	   
		   		$this->sql = $this->sql . " values ('$this->id_caso','$this->identificador','$this->descricao','$this->primeira_situacao','$this->situacao_final','$this->narrativa_final','$this->problematica_boa','$this->problematica_media','$this->problematica_ruim')";
		   		$Rs = $this->obj_connect->RunSql($this->sql);
				$this->status = true;
				
				$maxId = $this->obj_connect->maxReg( 'id_situacao', 'vr_situacoes' ) ;
				
				if( $this->caminho_objeto_1_temporario!="" ) {
					$this->objeto_1 = $this->salva_objetos( $maxId.'_1', 2 ) ;
				}
				if( $this->caminho_objeto_2_temporario!="" ) {
					$this->objeto_2 = $this->salva_objetos( $maxId.'_2', 2 ) ;
				}
				if( $this->caminho_objeto_3_temporario!="" ) {
					$this->objeto_3 = $this->salva_objetos( $maxId.'_3', 2 ) ;
				}
		   	  		
			}  
			// altera��o
			else 
			{
				if( $this->caminho_objeto_1_temporario!="" ) {
					$this->objeto_1 = $this->salva_objetos( $this->id_situacao.'_1', 2 ) ;
				}
				if( $this->caminho_objeto_2_temporario!="" ) {
					$this->objeto_2 = $this->salva_objetos( $this->id_situacao.'_2', 2 ) ;
				}
				if( $this->caminho_objeto_3_temporario!="" ) {
					$this->objeto_3 = $this->salva_objetos( $this->id_situacao.'_3', 2 ) ;
				}
				
				// Caso esta situa��o seja marcada como primeira situa��o
				// temos primeiro que desmarcar qualquer situa��o que esteja
				// marcada como primeira op��o para este caso
				if ( $this->primeira_situacao == 'Y' ) {
					$sql = "UPDATE vr_situacoes SET primeira_situacao='N' WHERE id_caso = '$this->id_caso'" ;
					$Rs = $this->obj_connect->RunSql($sql);
				}
				
				$sql = "UPDATE vr_situacoes set ";
				$sql = $sql. " id_caso   = '$this->id_caso',";
				$sql = $sql. " identificador = '$this->identificador',";
				$sql = $sql. " descricao = '$this->descricao',";
				$sql = $sql. " primeira_situacao = '$this->primeira_situacao',";
				$sql = $sql. " situacao_final = '$this->situacao_final',";
				$sql = $sql. " final = '$this->narrativa_final',";
				$sql = $sql. " problematica_boa = '$this->problematica_boa',";
				$sql = $sql. " problematica_media = '$this->problematica_media',";
				$sql = $sql. " problematica_ruim = '$this->problematica_ruim'";
				$sql = $sql. " WHERE id_situacao = '$this->id_situacao'";
				$Rs = $this->obj_connect->RunSql($sql);
				$this->status = true;		
			} 		
			$this->obj_connect->disconnect();
		}
	
    // Upload de arquivos (Tipos: 1=obras, 2=funcionarios, 3=informes)
	public function salva_objetos( $id, $tipo ) {
		//
		$this->obj_arquivo->id 		= $id ;
		$this->obj_arquivo->tipo 	= $tipo ;
		//
		if( $this->nome_objeto_1_temporario!="" ) {
			$nomeTemporario		= $this->nome_objeto_1_temporario ;
			$caminhoTemporario	= $this->caminho_objeto_1_temporario ;
			$this->nome_objeto_1_temporario="" ;
		}
		else if( $this->nome_objeto_2_temporario!="" ) {
			$nomeTemporario		= $this->nome_objeto_2_temporario ;
			$caminhoTemporario	= $this->caminho_objeto_2_temporario ;
			$this->nome_objeto_2_temporario="" ;
		}
		else if( $this->nome_objeto_3_temporario!="" ) {
			$nomeTemporario		= $this->nome_objeto_3_temporario ;
			$caminhoTemporario	= $this->caminho_objeto_3_temporario ;
			$this->nome_objeto_3_temporario="" ;
		}

		//
		$this->obj_arquivo->id_caso = $this->id_caso ;
		$this->obj_arquivo->nome_arquivo_temporario		= $nomeTemporario ;
		$this->obj_arquivo->caminho_arquivo_temporario 	= $caminhoTemporario ;
		//
		$this->obj_arquivo->upload() ;
		//
		return( $this->obj_arquivo->nome_arquivo ) ;
	}
	
	// M�todo p/ Excluir registros
		
	public function excluir()
	{
		$this->obj_connect->connect() ;
		//if( $this->id_situacao != 0 || $this->id_situacao != "" ) {	
		//{
			$sql = "delete from vr_situacoes where id_situacao = '$this->id_situacao'" ;
			$Rs           = $this->obj_connect->RunSql( $sql ) ;
			$this->status = true;
		//}
		$this->obj_connect->disconnect();		
	}
	
}
?>
