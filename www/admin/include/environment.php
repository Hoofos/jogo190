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


//*****************************************************************************
//* Arquivo   : envirorment.inc
//*
//* Introdu��o: Agrupa as defini��es das vari�veis de ambiente, mensagens de 
//*             erro e configura��es adicionais do sistema.
//*****************************************************************************


//****************** Defini��es de Vari�veis (escopo GLOBAL). ******************


// Condi��es de retorno naturais.
define( "condRetNULL" , NULL ) ;
define( "condRetFALSE", 0    ) ;
define( "condRetTRUE" , 1    ) ; 


// Dados de LOGIN do administrador do sistema.
define( "ADMIN_LOGIN", "admin"         ) ;
define( "ADMIN_SENHA", "1q2w3e"        ) ;
define( "ADMIN_NOME" , "Administrador" ) ;


// Dados para conex�o � base de dados.
define( "HOSTNAME", "mysql01.brjcomunicacao.com.br"      ) ;
define( "USERNAME", "brjcomunicacao"  ) ;
define( "PASSWORD", "mysqlbrj01"        ) ; 
define( "DATABASE", "brjcomunicacao" ) ;


// Tamanho m�ximo permitido para arquivos enviados (UPLOAD) em KBYTES.
define( "MAX_MP3_SIZE", 6400000 ) ; // 6 MBYTES
define( "MAX_IMG_SIZE", 640000  ) ; // 640 KBYTES


// Tipos de arquivos permitidos para envio (UPLOAD). Padr�o MIME.
define( "MUSIC_TYPE", "audio/mpeg"  ) ; // MP3
$IMAGE_TYPE = array( "jpg" => "image/pjpeg", "gif" => "image/gif" ) ; // JPEG e GIF


// Pastas de destino para UPLOAD de arquivos. Os destinos devem ser alterados convenientemente.
define( "PASTA_DESTINO_ANIMACOES"  , "../casos/"        ) ;
define( "PASTA_DESTINO_CLIPE"    	 , "/arquivos/clipes/"      ) ;
define( "PASTA_DESTINO_MUSICA"    	 , "/arquivos/musicas/"     ) ;
define( "PASTA_DESTINO_RESENHA"   	 , "/arquivos/resenhas/"    ) ;
define( "PASTA_DESTINO_ENTREVISTA"	 , "/arquivos/entrevistas/" ) ;
?>