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




// Projeto : Tem Peixe na Rede
// Classe  : cls_mensagem_site	
// Autor   : Nelson Cirilo Ramos
// Criação : 22/05/2005

class cls_mensagem_site {
	// Variáveis públicas representando os dados da classe 
	public $id_mensagem ;
	public $mensagem_padrao ;
	public $mensagem ;
	public $status ; // Variável auxiliar que indica o status de uma ação
	
	// Variáveis privadas,só podendo ser acessadas por essa classe
	private $obj_connect ;
		
	// Método p/ instanciar o objeto de conexão e inicializar a variável status
	function __construct() { 
		$this->obj_connect = new cls_connect ;
		$this->status      = false ;
	} 
	
	// Método p/ listar todas as mensagens do site
	public function listar_tudo() {
		$this->obj_connect->connect() ;				
		$sql = "select * from mensagem_site" ;			
		$Rs  = $this->obj_connect->RunSql( $sql ) ;
		return( $Rs ) ;
		$this->obj_connect->disconnect() ;	
	}		
      
	// Método p/ listar uma mensagem específica
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
	
	// Método p/ listar registro para ListBox
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
	
	// Método p/ incluir ou alterar registros	
	function salvar() {		

		$this->obj_connect->connect();   
		// inclusão
		if( $this->id_mensagem == "" || $this->id_mensagem == 0 ) {			   
			$this->sql = "insert into mensagem_site ( ms_mensagem_padrao, ms_mensagem )" ;	   
			$this->sql = $this->sql . " values ( '$this->mensagem_padrao', '$this->mensagem' )" ;
			$Rs        = $this->obj_connect->RunSql( $this->sql ) ;
		}  
		// alteração
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
	
	// Método p/ Excluir registros
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