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
// Classe  : cls_mensagem_site	
// Autor   : Nelson Cirilo Ramos
// Cria��o : 22/05/2005

class cls_mensagem_site {
	// Vari�veis p�blicas representando os dados da classe 
	public $id_mensagem ;
	public $mensagem_padrao ;
	public $mensagem ;
	public $status ; // Vari�vel auxiliar que indica o status de uma a��o
	
	// Vari�veis privadas,s� podendo ser acessadas por essa classe
	private $obj_connect ;
		
	// M�todo p/ instanciar o objeto de conex�o e inicializar a vari�vel status
	function __construct() { 
		$this->obj_connect = new cls_connect ;
		$this->status      = false ;
	} 
	
	// M�todo p/ listar todas as mensagens do site
	public function listar_tudo() {
		$this->obj_connect->connect() ;				
		$sql = "select * from mensagem_site" ;			
		$Rs  = $this->obj_connect->RunSql( $sql ) ;
		return( $Rs ) ;
		$this->obj_connect->disconnect() ;	
	}		
      
	// M�todo p/ listar uma mensagem espec�fica
	public function listar_reg( $condicao = '' ) {
		$this->obj_connect->connect() ;	
		if( $condicao == "" ) {
			$sql = "select * from mensagem_site where id_mensagem = '$this->id_mensagem' " ;
		}
		else {
			$sql = "select * from mensagem_site " . $condicao ;
		}
		$Rs  = $this->obj_connect->RunSql( $sql ) ;
		return( $Rs ) ;
		$this->obj_connect->disconnect() ;
	}
	
	// M�todo p/ listar registro para ListBox
	public function listar_para_combo() {
		$this->obj_connect->connect() ;	
		$sql = "select id_mensagem, ms_mensagem_padrao, ms_mensagem from mensagem_site" ;
		$Rs  = $this->obj_connect->RunQry( $sql ) ;
		$scbo = mysql_fetch_assoc( $Rs ) ;
		/*while( $row_cbo = mysql_fetch_assoc( $Rs ) ) {
			$id   = $row_cbo['id_mensagem'] ;
			$nome = $row_cbo['ms_mensagem_padrao'] ;
			$scbo = $scbo . "<Option value = $id >$nome</Option>" ;
		}*/
		$this->obj_connect->disconnect() ;
		return( $scbo ) ; // $scbo
	}
	
	// M�todo p/ incluir ou alterar registros	
	function salvar() {		

		$this->obj_connect->connect();   
		// inclus�o
		if( $this->id_mensagem == "" || $this->id_mensagem == 0 ) {			   
			$this->sql = "insert into mensagem_site ( ms_mensagem_padrao, ms_mensagem )" ;	   
			$this->sql = $this->sql . " values ( '$this->mensagem_padrao', '$this->mensagem' )" ;
			$Rs        = $this->obj_connect->RunSql( $this->sql ) ;
		}  
		// altera��o
		else {
			$sql = "UPDATE mensagem_site set " ;
			$sql = $sql. " ms_mensagem_padrao   = '$this->mensagem_padrao', " ;
			$sql = $sql. " ms_mensagem = '$this->mensagem' " ;
			$sql = $sql. " WHERE id_mensagem = '$this->id_mensagem'" ;
			$Rs  = $this->obj_connect->RunSql( $sql ) ;
		}
		$this->status = true ;	 
		$this->obj_connect->disconnect() ;
	}
	
	// M�todo p/ Excluir registros
	public function excluir() {
		$this->obj_connect->connect() ;			
		if ( $this->id_mensagem != 0 ) {
			$sql = "delete from mensagem_site where id_mensagem = '$this->id_mensagem'" ;
			//$sql = "delete from mensagem_site" ;
			$this->obj_connect->RunSql( $sql ) ;
			$this->status = true ;
		}
		$this->obj_connect->disconnect() ;		
	}
}

?>