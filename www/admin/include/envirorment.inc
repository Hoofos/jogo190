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

// Especifica��o de mensagens de erro.
define( "condRetErrSGBD" 	  	, "Erro ao tentar conectar ao SGBD."                               	) ;
define( "condRetErrSlct"  	  	, "Erro ao selecionar a base de dados."                            	) ;
define( "condRetErrQuery"  		, "Erro ao enviar a query."                                        	) ;
define( "condRetErrId"      	, "Erro. Par�metro ID_ n�o foi recebido."                          	) ;
define( "condRetErrFile"    	, "Erro. O arquivo n�o foi encontrado."                           	) ;
define( "condRetErrDownload"	, "Erro. Download desse tipo de aplica��o n�o pode ser realizado." 	) ;
define( "condRetErrCampos"  	, "Erro. Os Campos n�o foram preenchidos corretamente."            	) ;
define( "condRetErrUsrRepetido" , "Erro. E-mail j� cadastrado."      							    ) ;

// Outras mensagens.
define( "condRetZeroBandas"       , "Nenhuma Banda foi encontrada com essa(s) caracer�stica(s)."            ) ;
define( "condRetZeroUsuarios"     , "Nenhum Usu�rio foi encontrado com essa(s) caracter�stica(s)."          ) ;
define( "condRetZeroEntrevistas"  , "Nenhuma Entrevista foi encontrada com essa(s) caracter�stica(s)."      ) ;
define( "condRetZeroResenhas"     , "Nenhuma Resenha foi encontrada com essa(s) caracter�stica(s)."         ) ;
define( "condRetZeroMensagens"    , "Nenhuma Mensagem foi encontrada. Cadastre uma mensagem padr�o."        ) ;
define( "condRetZeroBlogTemas"    , "Nenhum Tema para o Blog foi encontrado com essa(s) caracter�stica(s)." ) ;
define( "condRetZeroBlogMensagens", "N�o h� Mensagens para o Tema do Blog com essa(s) caracter�stica(s)."   ) ;




// Dados de LOGIN do administrador do sistema.
define( "ADMIN_LOGIN", "admin"         ) ;
define( "ADMIN_SENHA", "1q2w3e"        ) ;
define( "ADMIN_NOME" , "Administrador" ) ;



// Dados para conex�o � base de dados.
//define( "HOSTNAME", "tempeixenarede.oi.com.br"      ) ;
//define( "USERNAME", "hptempeixe"  ) ;
//define( "PASSWORD", "peixe"        ) ; 

define( "HOSTNAME", "localhost"      ) ;
define( "USERNAME", "root"  ) ;
define( "PASSWORD", "1q2w3e"        ) ; 
define( "DATABASE", "tempeixenarede" ) ;



// Tamanho m�ximo permitido para arquivos enviados (UPLOAD) em KBYTES.
define( "MAX_MP3_SIZE", 6400000 ) ; // 6 MBYTES
define( "MAX_IMG_SIZE", 640000  ) ; // 640 KBYTES
// Tipos de arquivos permitidos para envio (UPLOAD). Padr�o MIME.
define( "MUSIC_TYPE", "audio/mpeg"  ) ; // MP3
$IMAGE_TYPE = array( "jpg" => "image/pjpeg", "gif" => "image/gif" ) ; // JPEG e GIF



// Define a idade m�xima para os participantes, pela diferen�a entre o ano maximo e o ano m�nimo (45 anos).
define( "ANO_MINIMO", 1945 ) ;
define( "ANO_MAXIMO", 2005 ) ;

// Pastas de destino para UPLOAD de arquivos. Os destinos devem ser alterados convenientemente.
define( "PASTA_DESTINO_BLOG"      , "/arquivos/blog/"        ) ;
define( "PASTA_DESTINO_CLIPE"     , "/arquivos/clipes/"      ) ;
define( "PASTA_DESTINO_MUSICA"    , "/arquivos/musicas/"     ) ;
define( "PASTA_DESTINO_RESENHA"   , "/arquivos/resenhas/"    ) ;
define( "PASTA_DESTINO_ENTREVISTA", "/arquivos/entrevistas/" ) ;
// Pasta de destino para o arquvo XML, gerado para o FLASH PLAYER MP3.
define( "PASTA_DESTINO_XMLFILE"   , "/srv/www/vhosts/tempeixenarede.com.br/arquivos/musicas/"        ) ;

?>