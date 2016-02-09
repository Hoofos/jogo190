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





//*****************************************************************************
//* Arquivo   : envirorment.inc
//*
//* Introdução: Agrupa as definições das variáveis de ambiente, mensagens de 
//*             erro e configurações adicionais do sistema.
//*****************************************************************************





//****************** Definições de Variáveis (escopo GLOBAL). ******************

// Condições de retorno naturais.
define( "condRetNULL" , NULL ) ;
define( "condRetFALSE", 0    ) ;
define( "condRetTRUE" , 1    ) ; 

// Especificação de mensagens de erro.
define( "condRetErrSGBD" 	  	, "Erro ao tentar conectar ao SGBD."                               	) ;
define( "condRetErrSlct"  	  	, "Erro ao selecionar a base de dados."                            	) ;
define( "condRetErrQuery"  		, "Erro ao enviar a query."                                        	) ;
define( "condRetErrId"      	, "Erro. Parâmetro ID_ não foi recebido."                          	) ;
define( "condRetErrFile"    	, "Erro. O arquivo não foi encontrado."                           	) ;
define( "condRetErrDownload"	, "Erro. Download desse tipo de aplicação não pode ser realizado." 	) ;
define( "condRetErrCampos"  	, "Erro. Os Campos não foram preenchidos corretamente."            	) ;
define( "condRetErrUsrRepetido" , "Erro. E-mail já cadastrado."      							    ) ;

// Outras mensagens.
define( "condRetZeroBandas"       , "Nenhuma Banda foi encontrada com essa(s) caracerística(s)."            ) ;
define( "condRetZeroUsuarios"     , "Nenhum Usuário foi encontrado com essa(s) característica(s)."          ) ;
define( "condRetZeroEntrevistas"  , "Nenhuma Entrevista foi encontrada com essa(s) característica(s)."      ) ;
define( "condRetZeroResenhas"     , "Nenhuma Resenha foi encontrada com essa(s) característica(s)."         ) ;
define( "condRetZeroMensagens"    , "Nenhuma Mensagem foi encontrada. Cadastre uma mensagem padrão."        ) ;
define( "condRetZeroBlogTemas"    , "Nenhum Tema para o Blog foi encontrado com essa(s) característica(s)." ) ;
define( "condRetZeroBlogMensagens", "Não há Mensagens para o Tema do Blog com essa(s) característica(s)."   ) ;




// Dados de LOGIN do administrador do sistema.
define( "ADMIN_LOGIN", "admin"         ) ;
define( "ADMIN_SENHA", "1q2w3e"        ) ;
define( "ADMIN_NOME" , "Administrador" ) ;



// Dados para conexão à base de dados.
//define( "HOSTNAME", "tempeixenarede.oi.com.br"      ) ;
//define( "USERNAME", "hptempeixe"  ) ;
//define( "PASSWORD", "peixe"        ) ; 

define( "HOSTNAME", "localhost"      ) ;
define( "USERNAME", "root"  ) ;
define( "PASSWORD", "1q2w3e"        ) ; 
define( "DATABASE", "tempeixenarede" ) ;



// Tamanho máximo permitido para arquivos enviados (UPLOAD) em KBYTES.
define( "MAX_MP3_SIZE", 6400000 ) ; // 6 MBYTES
define( "MAX_IMG_SIZE", 640000  ) ; // 640 KBYTES
// Tipos de arquivos permitidos para envio (UPLOAD). Padrão MIME.
define( "MUSIC_TYPE", "audio/mpeg"  ) ; // MP3
$IMAGE_TYPE = array( "jpg" => "image/pjpeg", "gif" => "image/gif" ) ; // JPEG e GIF



// Define a idade máxima para os participantes, pela diferença entre o ano maximo e o ano mínimo (45 anos).
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