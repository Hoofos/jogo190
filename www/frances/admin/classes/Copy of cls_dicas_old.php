<?php
// Projeto : Tem Peixe na Rede
// Classe  : cls_entrevistas	
// Autor   : Nelson Cirilo Ramos
// Criação : 19/05/2005

class cls_entrevistas
{
	
	
	// Variáveis públicas representando os dados da classe 
	
		public $id_entrevista;
		public $data;
		public $titulo;
		public $chamada;
		public $texto;
		public $nome_entrevistado;
		public $tipo_entrevistado;
		public $nome_imagem_entrevista;
		public $status; // Variável auxiliar que indica o status de uma ação
	
	// Variáveis privadas,só podendo ser acessadas por essa classe
	
		private $obj_connect;
		protected $imagem_entrevista;
	
		
	// Método p/ instanciar o objeto de conexão e inicializar a variável status
		
		function __construct() 
    		{ 
			$this->obj_connect = new cls_connect;
			$this->status = false;
    		} 
	
	// Método p/ listar todas as entrevistas
		
		public function listar_tudo()
		{
			$this->obj_connect->connect();				
			$sql = "select * from bandas";			
			$Rs = $this->obj_connect->RunSql($sql);
			return ($Rs);
			$this->obj_connect->disconnect();	
		}		
      
	// Método p/ listar uma entrevista específica
		
		public function listar_reg($condicao = '')	
		{
			$this->obj_connect->connect();	
			if($condicao == "")
			{
				$sql = "select * from entrevistas where id_entrevista = '$this->id_entrevista' ";
				$Rs = $obj_connect->RunSql($sql);
				return ($Rs);
			}
			else
			{
				$sql = "select * from entrevistas ".$condicao;
				$Rs = $obj_connect->RunSql($sql);
				return ($Rs);
			}
			$this->obj_connect->disconnect();
		}
	
	
	// Método p/ listar registro para ListBox
		
		public function listar_para_combo()
		{
			$this->obj_connect->connect();	
			$sql = "select id_entrevista,et_titulo from entrevistas";
		 	$Rs = $this->obj_connect->RunQry($sql);
		 	while ($row_cbo = mysql_fetch_assoc($Rs))
		 	{
				$id = $row_cbo['id_entrevista'];
				$nome = $row_cbo['et_titulo'];
		 		$scbo = $scbo . "<Option value = $id >$nome</Option>";
		 	}
		 	$this->obj_connect->disconnect();
			return ($scbo);
		}
	
	
	// Método p/ incluir ou alterar registros	
	
		function salvar()
		{		

       			$this->obj_connect->connect();
	   
	   		// inclusão
	   		if($this->id_entrevista == "" || $this->id_entrevista == 0) 
	   		{			   
		   		$this->sql = "insert into entrevistas (et_data, et_titulo, et_chamada, et_texto, nome_entrevistado, tipo_entrevistado , imagem_entrevista)";	   
		   		$this->sql = $this->sql . " values ('$this->data','$this->titulo','$this->chamada','$this->texto','$this->nome_entrevistado','$this->tipo_entrevistado','$this->nome_imagem_entrevista')";
		   		$Rs = $this->obj_connect->RunSql($this->sql);
				$this->status = true;	 
		   	  		
			}  
			// alteração
			else 
			{
				$sql = "UPDATE entrevistas set ";
				$sql = $sql. " et_data   = '$this->data',";
				$sql = $sql. " et_titulo = '$this->titulo',";
				$sql = $sql. " et_chamada= '$this->chamada',";
				$sql = $sql. " et_texto  = '$this->texto',";
				$sql = $sql. " nome_entrevistado = '$this->nome_entrevistado',";
				$sql = $sql. " tipo_entrevistado = '$this->tipo_entrevistado',";
				$sql = $sql. " imagem_entrevista = '$this->nome_imagem_entrevista'";
				$sql = $sql. " WHERE id_entrevista = '$this->id_entrevista'";
				$Rs = $this->obj_connect->RunSql($sql);
				$this->status = true;		
			} 		
			$this->obj_connect->disconnect();
		
		}
	
	//Método p/ salvar imagens da entrevista
		
		public function salva_img_entrevista()
		{
			
		}
	
	// Método p/ Excluir registros
		
		public function excluir()
		{
			$this->obj_connect->connect();			
			if ($this->id_entrevista() != 0)
			{
				$sql = "delete from entrevistas where id_entrevista = '$this->id_entrevista'";
				$this->status = true;
			}
			$this->obj_connect->disconnect();		
		}
	
}
?>
