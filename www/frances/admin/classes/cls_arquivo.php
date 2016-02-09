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


include( "include/environment.php"  ) ;


class cls_arquivo {
	//publicas
	public $id;
	public $id_caso;
	public $tipo; // 1 : �cones indicadores, 2 : objetos situa��es, 22 : bandas (imagem), 3 : clipes, 4 : entrevistas, 5 : resenhas
	public $counter;
	public $nome_arquivo;
	public $nome_arquivo_temporario;
	public $caminho_arquivo_temporario;				
	public $caminho;
	public $caminho_destino;
	//privadas
	private $obj_connect;

	//Construtor
	function __construct(){
		//$this->obj_connect = new cls_connect;
	}	

	// Rotina da upload de arquivos
	public function upload() {
		if( $this->tipo == 1 ) {
			$this->nome_arquivo    = "animacoes_globais\icone_indicador_" ;
			$this->caminho_destino = PASTA_DESTINO_ANIMACOES ;
		}			
		if( $this->tipo == 2 ) {
			$this->nome_arquivo    = "casos\caso_" . $this->id_caso . "\animacoes\objeto_" ;
			$this->caminho_destino = PASTA_DESTINO_ANIMACOES ;
		}
        if( $this->tipo == 3 ) {
			$this->nome_arquivo    = "casos\caso_" . $this->id_caso . "\animacoes\icone_opcao_" ;
			$this->caminho_destino = PASTA_DESTINO_ANIMACOES ;
		}
		if( $this->tipo == 4 ) {
			$this->nome_arquivo    = "casos\caso_" . $this->id_caso . "\documentos" ;
			$this->caminho_destino = PASTA_DESTINO_ENTREVISTA ;
		}			
		if( $this->tipo == 5 ) {
			$this->nome_arquivo    = "casos\caso_" . $this->id_caso . "\animacoes\icone_caso_" ;
			$this->caminho_destino = PASTA_DESTINO_RESENHA ;
		}
		
		//monta nome arquivo.
		//echo getcwd();
		//exit();
		
		if( $this->tipo != 4 ) {
			$Extencao           = strstr( $this->nome_arquivo_temporario, "." ) ;
			$this->nome_arquivo = $this->nome_arquivo.$this->id.$this->counter.$Extencao ;
		}
		else {
			$this->nome_arquivo = $this->nome_arquivo."/".$this->nome_arquivo_temporario ;
		}
		
		//monta caminho.
		$this->caminho_destino = (str_replace("admin","",getcwd())).$this->nome_arquivo ;
		//echo $this->caminho_destino;
		//exit;
		move_uploaded_file( $this->caminho_arquivo_temporario, $this->caminho_destino ) ;
	}
		
	// Rotina de download de arquivos
	// Par�metros : $nome_arquivo , $tipo
	public function download() {
		// First, see if the file exists		   
		// monta caminho arquivo
		$this->caminho = "" ;
	   
	  if( $this->tipo == 1 ) {
			$this->caminho = PASTA_DESTINO_BLOG . $this->nome_arquivo ;
		}			
		if( ($this->tipo == 2) || ($this->tipo == 22) ) {				
			$this->caminho = PASTA_DESTINO_MUSICA . $this->nome_arquivo ;
		}
		if( $this->tipo == 3 ) {
			$this->caminho = PASTA_DESTINO_CLIPE . $this->nome_arquivo ;
		}
		if( $this->tipo == 4 ) {
			$this->caminho = PASTA_DESTINO_ENTREVISTA . $this->nome_arquivo ;
		}
		if( $this->tipo == 5 ) {
			$this->caminho = PASTA_DESTINO_RESENHA . $this->nome_arquivo ;
		}
	  if( !is_file( $this->caminho ) ) { 
			die( "<b>condRetErrFile</b>" ) ;
		}
	
		//Gather relevent info about file
		$len            = filesize  ( $this->caminho ) ;
		$filename       = basename  ( $this->caminho ) ;
		$file_extension = strtolower( substr( strrchr( $filename, "." ), 1 ) ) ;
	
		//This will set the Content-Type to the appropriate setting for the file
		switch( $file_extension ) {
			case "pdf"  : $ctype = "application/pdf"               ; break ;
			case "exe"  : $ctype = "application/octet-stream"      ; break ;
			case "zip"  : $ctype = "application/zip"               ; break ;
			case "doc"  : $ctype = "application/msword"            ; break ;
			case "xls"  : $ctype = "application/vnd.ms-excel"      ; break ;
			case "ppt"  : $ctype = "application/vnd.ms-powerpoint" ; break ;
			case "gif"  : $ctype = "image/gif"                     ; break ;
			case "png"  : $ctype = "image/png"                     ; break ;
			case "jpeg" :
			case "jpg"  : $ctype = "image/pjpeg"                   ; break ;
			case "mp3"  : $ctype = "audio/mpeg"                    ; break ;
			case "wav"  : $ctype = "audio/x-wav"                   ; break ;
			case "mpeg" :
			case "mpg"  :
			case "mpe"  : $ctype = "video/mpeg"                    ; break ;
			case "mov"  : $ctype = "video/quicktime"               ; break ;
			case "avi"  : $ctype = "video/x-msvideo"               ; break ;
			
			//The following are for extensions that shouldn't be downloaded (sensitive stuff, like php files)
			case "php"  :
			case "htm"  :
			case "html" :
			case "txt"  : die( "<b>condRetErrDownload<b> Arquivo " . $file_extension ) ; break ;
			
			default : $ctype = "application/force-download" ;
		}
	
		//Begin writing headers
		header( "Pragma: public" ) ;
		header( "Expires: 0" ) ;
		header( "Cache-Control: must-revalidate, post-check=0, pre-check=0" ) ;
		header( "Cache-Control: public" ) ; 
		header( "Content-Description: File Transfer" ) ;
		
		//Use the switch-generated Content-Type
		header( "Content-Type: $ctype" ) ;
		
		//Force the download
		$header = "Content-Disposition: attachment; filename=" . $filename . ";" ;
		header( $header ) ;
		header( "Content-Transfer-Encoding: binary" ) ;
		header( "Content-Length: " . $len ) ;
		@readfile( $this->caminho ) ;
		exit ;
	}//downoad
		
	// Excluir arquivos
	// Parametros : $nome_arquivo , tipo;
	public function delfile() {
		// monta caminho arquivo
	  	if( $this->tipo == 1 ) {
			$this->caminho = PASTA_DESTINO_ANIMACOES . $this->nome_arquivo ;
		}			
		if($this->tipo == 2) {				
			$this->caminho = PASTA_DESTINO_ANIMACOES . $this->nome_arquivo ;
		}
		if( $this->tipo == 3 ) {
			$this->caminho = PASTA_DESTINO_ANIMACOES . $this->nome_arquivo ;
		}
		if( $this->tipo == 4 ) {
			$this->caminho = PASTA_DESTINO_ENTREVISTA . $this->nome_arquivo ;
		}
		if( $this->tipo == 5 ) {
			$this->caminho = PASTA_DESTINO_RESENHA . $this->nome_arquivo ;
		}
		$this->caminho = (str_replace("/admin","",getcwd())).$this->caminho ;

		unlink( $this->caminho ) ;
	}				
}
?>
