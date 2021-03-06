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
//* Arquivo   : extra.inc
//*
//* Introdu��o: Agrupa as fun��es auxiliares do sistema.
//*****************************************************************************




// Vari�veis de ambiente.
include( "include/envirorment.inc") ;





//******************************************************************************
//* Fun��o     : EXTRA_xmlWriter
//*
//* Atualiza��o: 26/07/2005
//* Autor      : Marcello de Gregorio Gabino (MGG).
//*
//* Objetivo   : Gera e/ou atualiza o arquivo .XML respons�vel pelo gerenciamen-
//*              to dos dados, para o PLAYER FLASH MP3.
//*
//* Retorna    : $false, caso o array n�o seja informado.
//******************************************************************************
if ( ! ( function_exists( "EXTRA_xmlWriter" ) ) ) {
    function EXTRA_xmlWriter( $arrVar=null ) {
        if ( !isset( $arrVar ) ) {
            return false ;
        }
        $fileName = PASTA_DESTINO_XMLFILE . "mp3player.xml" ;
        // Abre arquivo para escrita.
        if( !( $xmlFile=fopen( $fileName, "w" ) ) ) {
            die( condRetErrFile ) ;
        }
        // Escreve no arquivo
        fputs( $xmlFile, "<?xml version=\"1.0\" encoding=\"UTF-8\"?>" . "\n" ) ;
        fputs( $xmlFile, "<player showDisplay=\"yes\" showPlaylist=\"yes\" autoStart=\"no\">" . "\n" ) ;
        // Caso exista alguma banda cadastrada
		if ( mysql_num_rows( $arrVar ) ) {
			while( $result = mysql_fetch_array( $arrVar ) ) {
                // Caminho e dados das m�sicas.
								$nome_musica = $result['bd_nome_musica'] ;
								$nome_banda = $result['bd_nome'];
								$estilo = $result['bd_estilo'];
								
								$trans = array("�" => "a", "�" => "a","�" => "a", "�" => "a","�" => "e", "�" => "e","�" => "i", "�" => "o","�" => "o","�" => "o","�" => "u","�" => "u","�" => "c","�" => "A", "�" => "A","�" => "A", "�" => "A","�" => "E", "�" => "E","�" => "I", "�" => "O","�" => "O","�" => "O","�" => "U","�" => "U","�" => "C","'" => "","`" => "","�" => "","�" => "","\"" => "");
								$nome_musica = strtr($nome_musica, $trans);
								$nome_banda = strtr($nome_banda, $trans);
								$estilo = strtr($estilo, $trans);

                fputs( $xmlFile, "<song path=\"".PASTA_DESTINO_MUSICA . $result['bd_arquivo_musica']."\" title=\"".$nome_musica." - ".$nome_banda."\" estilo=\"".$estilo."\" id=\"".$result['id_banda']."\" />" . "\n" ) ;
            }
        }
        fputs( $xmlFile, "</player>" ) ;
        // Fecha arquivo.
        fclose( $xmlFile ) ;
        return true ;
    }
}

//******************************************************************************
//* Fun��o     : EXTRA_varExplode
//*
//* Atualiza��o: 19/10/2003
//* Autor      : Marcello de Gregorio Gabino (MGG).
//*
//* Objetivo   : Divide um ARRAY em diversas STRINGS, de acordo com um delimita-
//*              dor passado como par�metro.
//*
//* Retorna    : $
//******************************************************************************
if ( ! ( function_exists( "EXTRA_varExplode" ) ) ) {
    function EXTRA_varExplode( $arrVar=null, $delimiter=null ) {
        if ( !isset( $arrVar ) || !isset( $delimiter ) ) {
            return null ;
        }
        return explode( $delimiter, $arrVar ) ;
    }
}

//******************************************************************************
//* Fun��o     : EXTRA_selectEstiloMusical
//*
//* Atualiza��o: 12/07/2005
//* Autor      : Marcello de Gregorio Gabino (MGG).
//*
//* Objetivo   : Exibir o campo SELECT, contendo os estilos musicais. O estilo
//*              a ser selecionado dever� ser passado em $arrVar, correpondendo
//*              a um valor inteiro entre 0 e 20.
//*
//* Entrada    : $arrVAr, valor inteiro entre 0 e 20 (21 estilos).
//******************************************************************************
if ( ! ( function_exists( "EXTRA_selectEstiloMusical" ) ) ) {
    function EXTRA_selectEstiloMusical( $arrVar=null ) {
        
        // ARRAY Contendo os estilos.
        $arrEstilos = array( 0 => "Black Music"     , "Blues"               , "Cl�ssico"            , "Country" , "Dance"       ,
                                  "Disco"           , "Easy Listening"      , "Eletr�nico"          , "Gospel"  , "Heavy Metal" ,
                                  "Infantil"        , "Instrumental"        , "Jazz Contempor�neo"  , "Jazz Tradicional"        ,
                                  "Reggae"          , "MPB"                 , "New Age"             , "POP"                     ,
                                  "Punk"            , "Rock"                , "World Music") ;
        
        if ( $arrVar<0 || $arrVar>=count( $arrEstilos ) ) {
            return null ;
        }
        else {
            print ( "<select name=\"obTxtEstilo\" size=\"1\">" ) ;
            print ( "<option value=\"\"></option>" ) ;
            for( $i=0; $i<count( $arrEstilos ); $i++ ) {
                print ( "<option value=\"" . $i . "\"" . ( (!(strcmp($i, $arrVar))) ? " SELECTED" : "" ) . ">" . $arrEstilos[$i] . "</option>" ) ;
            }
            print ( "</select>" ) ;
        }
    }
}

//******************************************************************************
//* Fun��o     : EXTRA_printEstado
//*
//* Atualiza��o: 10/08/2005
//* Autor      : Henrique Thorp K�sel (HTK). :D
//*
//* Objetivo   : Imprimir o estado referente ao inteiro entre 0 e 26
//*
//* Entrada    : $arrVAr, valor inteiro entre 0 e 26 (27 estados).
//******************************************************************************
if ( ! ( function_exists( "EXTRA_printEstado" ) ) ) {
    function EXTRA_printEstado( $arrVar=null ) {
        
        // ARRAY Contendo os estados do Brasil.
        $arrEstados = array( 0 => "Acre"             , "Alagoas"           , "Amap�"         , "Amazonas"      , "Bahia"              ,
                                  "Cear�"            , "Distrito Federal"  , "Espirito Santo", "Goi�s"         , "Maranh�o"           ,
                                  "Mato Grosso"      , "Mato Grosso do Sul", "Minas Gerais"  , "Par�"          , "Para�ba"            ,
                                  "Paran�"           , "Pernambuco"        , "Piau�"         , "Rio de Janeiro", "Rio Grande do Norte",              
                                  "Rio Grande Do Sul", "Rond�nia"          , "Roraima"       , "Santa Catarina", "S�o Paulo"          ,
                                  "Sergipe"          , "Tocantins" ) ;
        
        if ( $arrVar<0 || $arrVar>=count( $arrEstados ) ) {
            return null ;
        }
        else {
            return $arrEstados[$arrVar]  ;
        }
    }
}

//******************************************************************************
//* Fun��o     : EXTRA_selectEstado
//*
//* Atualiza��o: 19/10/2003
//* Autor      : Marcello de Gregorio Gabino (MGG).
//*
//* Objetivo   : Exibir o campo SELECT, contendo os estados do Brasil. O Estado
//*              a ser selecionado dever� ser passado em $arrVar, correpondendo
//*              a um valor inteiro entre 0 e 26.
//*
//* Entrada    : $arrVAr, valor inteiro entre 0 e 26 (27 estados).
//******************************************************************************
if ( ! ( function_exists( "EXTRA_selectEstado" ) ) ) {
    function EXTRA_selectEstado( $arrVar=null ) {
        
        // ARRAY Contendo os estados do Brasil.
        $arrEstados = array( 0 => "Acre"             , "Alagoas"           , "Amap�"         , "Amazonas"      , "Bahia"              ,
                                  "Cear�"            , "Distrito Federal"  , "Espirito Santo", "Goi�s"         , "Maranh�o"           ,
                                  "Mato Grosso"      , "Mato Grosso do Sul", "Minas Gerais"  , "Par�"          , "Para�ba"            ,
                                  "Paran�"           , "Pernambuco"        , "Piau�"         , "Rio de Janeiro", "Rio Grande do Norte",              
                                  "Rio Grande Do Sul", "Rond�nia"          , "Roraima"       , "Santa Catarina", "S�o Paulo"          ,
                                  "Sergipe"          , "Tocantins" ) ;
        
        if ( $arrVar<0 || $arrVar>=count( $arrEstados ) ) {
            return null ;
        }
        else {
            print ( "<select name=\"obTxtEstado\" size=\"1\">" ) ;
            print ( "<option value=\"\"></option>" ) ;
            for( $i=0; $i<count( $arrEstados ); $i++ ) {
                print ( "<option value=\"" . $i . "\"" . ( (!(strcmp($i, $arrVar))) ? " SELECTED" : "" ) . ">" . $arrEstados[$i] . "</option>" ) ;
            }
            print ( "</select>" ) ;
        }
    }
}

//******************************************************************************
//* Fun��o     : EXTRA_selectData
//*
//* Atualiza��o: 19/10/2003
//* Autor      : Marcello de Gregorio Gabino (MGG).
//*
//* Objetivo   : Exibir o campo SELECT, para sele��o de uma data espec�fica. A 
//*              data a ser selecionada dever� ser passada em $arrVar, corres-
//*              pondendo a um ARRAY de 3 valores inteiros:
//*
//* Entrada    : $arrVar[0], ano ;
//*              $arrVar[1], m�s ;
//*              $arrVar[2], dia.
//******************************************************************************
if ( ! ( function_exists( "EXTRA_selectData" ) ) ) {
    function EXTRA_selectData( $arrVar=null ) {
        
        // ARRAY contendo os m�ses do ano.
        $arrMeses = array( 1 => "Janeiro", "Fevereiro", "Mar�o"   , "Abril"  , "Maio"    , "Junho"    ,
                                "Julho"  , "Agosto"   , "Setembro", "Outubro", "Novembro", "Dezembro"  ) ;
        
        if ( ( isset( $arrVar )                                        ) &&
             ( ( $arrVar[0] < ANO_MINIMO || $arrVar[0] > ANO_MAXIMO    ) ||
               ( $arrVar[1] < 1    || $arrVar[1] >= count( $arrMeses ) ) ||
               ( $arrVar[2] < 1    || $arrVar[2] > 31   )              ) ) {
            return null ;
        }
        else {
            // Campo DIA
            print ( "<select name=\"obDia\">" ) ;
						print ( "<option value=\"\"></option>" ) ;
            for( $i=1; $i<=31; $i++ ) {
                print ( "<option value=\"" . $i . "\"" . ( (!(strcmp($i, (int)$arrVar[2]))) ? " SELECTED" : "" ) . ">" . $i . "</option>" ) ;
            }
            print ( "</select>" ) ;
            // Campo M�S
            print ( "&nbsp; <select name=\"obMes\">" ) ;
			print ( "<option value=\"\"></option>" ) ;
            for( $i=1; $i<=count( $arrMeses ); $i++ ) {
                print ( "<option value=\"" . $i . "\"" . ( (!(strcmp($i, (int)$arrVar[1]))) ? " SELECTED" : "" ) . ">" . $arrMeses[$i] ."</option>" ) ;
            }
            print ( "</select>" ) ;
            // Campo ANO
            print ( "&nbsp; <select name=\"obAno\">" ) ;
			print ( "<option value=\"\"></option>" ) ;
            for( $i=ANO_MINIMO; $i<=ANO_MAXIMO; $i++ ) {
                print ( "<option value=\"" . $i . "\"" . ( (!(strcmp($i, (int)$arrVar[0]))) ? " SELECTED" : "" ) . ">" . $i . "</option>" ) ;
            }
            print ( "</select>" ) ;
        }
    }
}

?>