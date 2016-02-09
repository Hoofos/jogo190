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




// Projeto : Tem Peixe na Rede
// Classe  : cls_entrevistas	
// Autor   : Nelson Cirilo Ramos
// Cria��o : 19/05/2005

class cls_entrevistas {
	// Vari�veis p�blicas representando os dados da classe 
	public $id_entrevista ;
	public $data ;
	public $titulo ;
	public $chamada ;
	public $texto ;
	public $nome_entrevistado ;
	public $tipo_entrevistado ;
	public $et_imagem ;
	public $et_imagem_caminho_temporario ;
	public $et_nome_imagem_temporario ;
	public $et_nome_imagem ;
	public $status ; // Vari�vel auxiliar que indica o status de uma a��o
	
	// Vari�veis privadas,s� podendo ser acessadas por essa classe
	private   $obj_connect ;
	protected $imagem_entrevista ;
	private   $obj_arquivo ;
	
	// M�todo p/ instanciar o objeto de conex�o e inicializar a vari�vel status
	function __construct() { 
		$this->obj_connect = new cls_connect ;
		$this->obj_arquivo = new cls_arquivo ;
		$this->status      = false ;
	} 
	
	// M�todo p/ listar todas as entrevistas
	public function listar_tudo() {
		$this->obj_connect->connect() ;
		$sql = "select * from entrevistas" ;
		$Rs  = $this->obj_connect->RunSql( $sql ) ;
		return( $Rs ) ;
		$this->obj_connect->disconnect() ;	
	}		
      
	// M�todo p/ listar uma entrevista espec�fica
	public function listar_reg( $condicao = '' ) {
		$this->obj_connect->connect() ;
		if( $condicao == "" ) {
			$sql = "select * from entrevistas where id_entrevista = '$this->id_entrevista' " ;
		}
		else {
			$sql = "select * from entrevistas " . $condicao . "ORDER BY et_data DESC" ;
		}
		$Rs  = $this->obj_connect->RunSql( $sql ) ;
		return( $Rs ) ;
		$this->obj_connect->disconnect();
	}
	
	// M�todo p/ listar registro para ListBox
	public function listar_para_combo() {
		$this->obj_connect->connect() ;	
		$sql = "select id_entrevista,et_titulo from entrevistas ORDER BY et_data DESC" ;
		$Rs  = $this->obj_connect->RunQry( $sql ) ;
		/*while( $row_cbo = mysql_fetch_assoc( $Rs ) ) {
			$id   = $row_cbo['id_entrevista'] ;
			$nome = $row_cbo['et_titulo'] ;
			$scbo = $scbo . "<Option value = $id >$nome</Option>" ;
		}*/
		$this->obj_connect->disconnect() ;
		return( $Rs ) ; //$scbo
	}
	
	// M�todo p/ incluir ou alterar registros	
	function salvar() {		
		$this->obj_connect->connect() ;
   
		// inclus�o
		if( $this->id_entrevista == "" || $this->id_entrevista == 0 ) {			   
			if( $this->et_imagem_caminho_temporario != "" ) {
				$maxId = $this->obj_connect->maxReg( 'id_entrevista', 'entrevistas' ) ;
				$maxId++ ;
				$this->et_nome_imagem = $this->salva_img( $maxId ) ;
			}	
			$this->sql    = "insert into entrevistas (et_data, et_titulo, et_chamada, et_texto, nome_entrevistado, tipo_entrevistado , imagem_entrevista)" ;
			$this->sql    = $this->sql . " values ('$this->data','$this->titulo','$this->chamada','$this->texto','$this->nome_entrevistado','$this->tipo_entrevistado','$this->et_nome_imagem')" ;
			$Rs           = $this->obj_connect->RunSql( $this->sql ) ;
			$this->status = true ;	 						   	  		
		}  
		// altera��o
		else {
			$sql = "UPDATE entrevistas set " ;
			$sql = $sql . " et_data   = '$this->data', " ;
			$sql = $sql . " et_titulo = '$this->titulo', " ;
			$sql = $sql . " et_chamada= '$this->chamada', " ;
			$sql = $sql . " et_texto  = '$this->texto', " ;
			$sql = $sql . " nome_entrevistado = '$this->nome_entrevistado', " ;
			$sql = $sql . " tipo_entrevistado = '$this->tipo_entrevistado' " ;
			
			if( $this->et_imagem_caminho_temporario != "" ) {
				$this->et_nome_imagem = $this->salva_img( $this->id_entrevista ) ;
				$sql = $sql . ", imagem_entrevista = '$this->et_nome_imagem' " ;
			}
			
			$sql = $sql . "WHERE id_entrevista = '$this->id_entrevista'" ;
			$Rs           = $this->obj_connect->RunSql( $sql ) ;
			$this->status = true ;
		}
		$this->obj_connect->disconnect() ;	
	}
	
	//M�todo p/ salvar imagens da entrevista
	public function salva_img( $id ) {
		$this->obj_arquivo->id = $id ;
		$this->obj_arquivo->tipo = 4 ;
		$this->obj_arquivo->nome_arquivo_temporario    = $this->et_nome_imagem_temporario ;
		$this->obj_arquivo->caminho_arquivo_temporario = $this->et_imagem_caminho_temporario ;
		$this->obj_arquivo->upload() ;
		return $this->obj_arquivo->nome_arquivo ;
	}
	
	// M�todo p/ Excluir registros		
	public function excluir() {
		$this->obj_connect->connect() ;
		if( $this->id_entrevista != 0 || $this->id_entrevista != "" ) {
			$this->excluir_foto( $this->id_entrevista ) ;
			$sql          = "delete from entrevistas where id_entrevista = '$this->id_entrevista'" ;
			$Rs           = $this->obj_connect->RunSql( $sql ) ;
			$this->status = true ;
		}
		$this->obj_connect->disconnect() ;		
	}
	// Excluir 
	private function excluir_foto( $id ) {
		$sql        = "select imagem_entrevista from entrevistas where id_entrevista = '$this->id_entrevista'" ;			
		$Rs         = $this->obj_connect->RunSql($sql) ;	
		$row_foto = mysql_fetch_assoc( $Rs ) ;
		if( $row_foto['imagem_entrevista'] != "" )	{
			$this->et_nome_imagem         = $row_foto['imagem_entrevista'] ;
			$this->obj_arquivo->tipo         = 4 ;
			$this->obj_arquivo->nome_arquivo = $this->et_nome_imagem ;
			$this->obj_arquivo->delfile() ;
		}
	}
}
?>