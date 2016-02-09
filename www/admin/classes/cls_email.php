<?php
class cls_email
{
	// PROPRIEDADES
		public $remetente;
		public $nome_remetente;
		public $destinatario;
		public $assunto;
		private $cabecalho;
		public $mensagem;
	
	
	// METODOS
	function __construct() 
	{
		if($this->remetente != "" && $this->nome_remetente != "")
		{			
		 	$this->cabecalho = "From: ".$this->nome_remetente." <".$this->remetente.">\r\n";
		}	
		else 
		{
			$this->cabecalho = "From: Festival Tem Peixe Na Rede <falecom@oitempeixenarede.com.br>\r\n";
		}
		
	}	
	
	private function seta_cabecalho()
	{
		if($this->remetente != "" && $this->nome_remetente != "")
		{			
		 	$this->cabecalho = "From: ".$this->nome_remetente." <".$this->remetente.">\r\n";
		}	
		else 
		{
			$this->cabecalho = "From: Festival Tem Peixe Na Rede <falecom@oitempeixenarede.com.br>\r\n";
		}
	}
	
	public function envia_email_cadastro($tipo)
	{
		if($this->mensagem != "")
		{
			if($tipo == 1)
			{
				// ----- TIPO TEXTO
				$msg_tipo = "Content-type: text/plain; charset=iso-8859-1\r\n";			
				$this->cabecalho = $msg_tipo.$this->cabecalho;
				//$this->mensagem = $msg_tipo. $this->mensagem;
			}
			if($tipo == 2)	
			{	
				// --- TIPO HTML
				$msg_tipo = "Content-type: text/html; charset=iso-8859-1\r\n";			
				$this->cabecalho = $msg_tipo.$this->cabecalho;
				//$this->mensagem = $msg_tipo. $this->mensagem;			
			}					
			//echo($this->destinatario."<br>");			
			//echo($this->mensagem."<br>");
			//echo($this->cabecalho."<br>");
			$eml = mail($this->destinatario,$this->assunto,$this->mensagem,$this->cabecalho);						
			return ($eml);
		}else 
		{
			echo('O campo mensagem esta vazio.');
			exit();			
		}
		
	}
	function envia_email_faleconosco($tipo)
	{
		if($this->mensagem != "")
		{
			if($tipo == 1)
			{
				// ----- TIPO TEXTO
				$msg_tipo = "Content-type: text/plain; charset=iso-8859-1\r\n";			
				$this->cabecalho = $msg_tipo.$this->cabecalho;		
			}
			if($tipo == 2)	
			{	
				// --- TIPO HTML
				$msg_tipo = "Content-type: text/html; charset=iso-8859-1\r\n";			
				$this->cabecalho = $msg_tipo.$this->cabecalho;				
			}					
			$this->seta_cabecalho();
			$this->destinatario = "falecom@oitempeixenarede.com.br,henrique.kusel@gabiesel.com.br";
			$this->assunto = "FALE CONOSCO";			
			echo($this->destinatario);
			echo($this->assunto);
			echo($this->assunto);
			echo($this->mensagem);
			$eml = mail($this->destinatario,$this->assunto,$this->mensagem,$this->cabecalho);						
			
			return ($eml);
		}else 
		{
			echo('O campo mensagem esta vazio.');
			exit();			
		}
	}
}
?>