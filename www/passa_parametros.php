<?php
	// Evita que o php seja armazenado no cache do servidor
	Header( 'Cache-Control: no-cache' ) ;
	Header( 'Pragma: no-cache' ) ;
	
	session_start();
	
	// Faz conexão com o Banco de Dados
	$link = mysql_connect( "localhost", "jogo190", "mysql190!!" ) or die('Could not connect: ' . mysql_error());

	// Seleciona a Base de Dados
	mysql_select_db('jogo190') or die('Could not select database');
	
	// Inicializa variáveis
	$strParametros = "" ;
	$palavras_chave = "" ;
	$nivelNarrativaBoa = 70 ; // Nível mínimo/máximo para uma narrativa ser boa/média
	$nivelNarrativaMedia = 40 ; // Nível mínimo/máximo para uma narrativa ser média/ruim
	
	if (!isset($_SESSION['campanha'])) {
		$_SESSION['campanha'] = "0" ;
	}
	
	// RECEBE PARÂMETROS < 
	
		// Caso o parâmetro 'tipo' não seja passado, retorna erro
		if ( !isset($_GET['tipo']) ) {
			$strParametros = "&erro=1" ;
		}
		else {
			$tipo = $_GET['tipo'] ;
		}
		/*
		Valores válidos para $tipo:
			1.Casos
			2.Situações
			3.Dicas
			4.Documentos
			5.Busca
		*/
		
		// Recebe o id de um caso
		if ( isset($_GET['caso']) ) {
			$caso = $_GET['caso'] ;
		}
		
		// Recebe o id de uma campanha
		if ( isset($_GET['campanha']) ) {
			$campanha = $_GET['campanha'] ;
		}
		// Nesta primeira etapa do jogo o id da campanha será fixo com o valor 1, já que só existirá uma única campanha
		
		// Recebe o id de uma decisão
		if ( isset($_GET['opcao']) ) {
			$opcao = $_GET['opcao'] ;
		}
		
		// Recebe o tipo da opção selecionada
		if ( isset($_GET['tipo_opcao']) ) {
			$tipo_opcao = $_GET['tipo_opcao'] ;
		}
		else {
			$tipo_opcao = '' ;
		}
		
		// Recebe o tipo da opção selecionada
		if ( isset($_GET['proxima_situacao']) ) {
			$proxima_situacao = $_GET['proxima_situacao'] ;
		}
		
		// Recebe o id da situacao atual para selecionar as dicas ou os documentos relacionados
		if ( isset($_GET['situacao']) ) {
			$situacao = $_GET['situacao'] ;
		}
		
		// Recebe palavra-chave para busca (documentos de referência)
		if ( isset($_GET['busca']) ) {
			$busca = $_GET['busca'] ;
		}

	// RECEBE PARÂMETROS >
	
	// ZERA O JOGO <
	if ( $tipo == 0 ) {
		$_SESSION['campanha'] = "0" ;
		$_SESSION['variacao_termometro_B'] = 0 ;
		$_SESSION['variacao_termometro_M'] = 0 ;
		$_SESSION['variacao_termometro_R'] = 0 ;
		$_SESSION['termometro'] = 0 ;
		
		$strParametros = "&erro=0" ;
	}
	// CASOS <
	elseif ( $tipo == 1 ) {
		//
		// Listagem de todos os casos
		if ( $campanha == 1 ) {
			$query 	= 	"SELECT c.titulo,c.descricao,c.resumo,cp.termometro,cp.variacao_bom,cp.variacao_medio,cp.variacao_ruim, b.* "
					.	"FROM casos AS c INNER JOIN boxes AS b "
					.   "ON b.id_caso=c.id_caso "
					.   ", campanhas AS cp "
					.   "WHERE cp.id_campanha=" . $campanha
					.   " AND c.id_Caso "
					.   "IN ( "
					.   "SELECT id_caso "
					.   "FROM campanhas_casos "
					.   "WHERE id_campanha=" . $campanha
					.   ") "
					.	"ORDER BY RAND()" ;
					
			// No caso de campanhas, esse é o início do jogo então devemos configurar os valores iniciais
			$_SESSION['campanha'] = "1" ;
		}
		// Listagem de todos os casos relacionados com uma determinada campanha
		elseif ( $campanha == 0 ) {
			$query 	= 	"SELECT c.titulo,c.descricao,c.resumo,c.termometro,b.* "
					.	"FROM casos AS c "
					.   "INNER JOIN boxes AS b "
					.   "ON b.id_caso=c.id_caso "
					.	"ORDER BY titulo" ;
			
			// O jogador escolheu jogar caso a caso então marcamos a variável abaixo como false		
			$_SESSION['campanha'] = "0" ;
		}
		
		//
		$rs = mysql_query( $query ) ;
		//
		if( mysql_num_rows($rs) ) {
			//
			$counter = 1 ;
			//
			while( $result=mysql_fetch_array($rs) ) {
				//
				
				if ( ( $_SESSION['campanha'] == "1" ) && ($counter == 1) ){
					$_SESSION['variacao_termometro_B'] = $result['variacao_bom'] ;
					$_SESSION['variacao_termometro_M'] = $result['variacao_medio'] ;
					$_SESSION['variacao_termometro_R'] = $result['variacao_ruim'] ;
					$_SESSION['termometro'] = $result['termometro'] ;
				}
				
				$strParametros = $strParametros	. "&id_caso" . $counter . "=" . $result['id_caso']
												. "&titulo" . $counter . "=" . urlencode(trim($result['titulo']))
												. "&descricao" . $counter . "=" . urlencode(trim($result['descricao']))
												. "&resumo" . $counter . "=" . urlencode(trim($result['resumo']))
												. "&titulo_box_1_" . $counter . "=" . urlencode(trim($result['titulo_1']))
												. "&fontes_box_1_" . $counter . "=" . urlencode(trim($result['fontes_1']))
												. "&conteudo_box_1_" . $counter . "=" . urlencode(trim($result['conteudo_1']))
												. "&titulo_box_2_" . $counter . "=" . urlencode(trim($result['titulo_2']))
												. "&fontes_box_2_" . $counter . "=" . urlencode(trim($result['fontes_2']))
												. "&conteudo_box_2_" . $counter . "=" . urlencode(trim($result['conteudo_2']))
												. "&termometro" . $counter . "=" . $result['termometro'] ;
				$counter = $counter + 1 ;
			}
			//
			$strParametros = $strParametros	. "&recCount=" . ($counter-1) ;
		}
		else {
			$strParametros = "&erro=1" ;
		}
	}
	// CASOS >
	
	// SITUAÇÕES <
	elseif( $tipo == 2 ) {
		//
		// Se opção for igual a 0 então se trata da primeira situação
		if ( $opcao == 0 ) {
			//
			// No caso de jogar caso a caso, este é o início do jogo então devemos configurar os valores iniciais
			//
			// Se recebermos o valor do $tipo_opcao significa que estamos jogando o mesmo caso novamente devido a um erro
			// ...esta informação serve para mantermos o termômetro com o resultado da jogada anterior.
			// ... ou seja, se recebemos $tipo_opcao, não devemos entrar no if abaixo

			if ( ( $_SESSION['campanha'] == "0" ) && ( $tipo_opcao == '' ) ) {

				$query = "SELECT termometro,variacao_bom,variacao_medio,variacao_ruim "
				         . " FROM casos"
						 . " WHERE id_caso=" . $caso ;
				
				$rs = mysql_query( $query ) ;
						 
				if( $rs ) {
					$row = mysql_fetch_row($rs);
					$_SESSION['variacao_termometro_B'] = $row[1] ;
					$_SESSION['variacao_termometro_M'] = $row[2] ;
					$_SESSION['variacao_termometro_R'] = $row[3] ;
					$_SESSION['termometro'] = $row[0] ;
					unset($rs) ;
				}
				
			}
			/* Desconta novamente os pontos quando reiniciamos um caso após erro do jogador
			else {
				
				if ($tipo_opcao != "") {
					$_SESSION['termometro'] = $_SESSION['termometro'] + $_SESSION['variacao_termometro_' . $tipo_opcao];
					
					if ( $_SESSION['termometro'] > 100 ) {
						$_SESSION['termometro'] = 100 ;
					}
				}
				
			} */

			$query = "SELECT s.*,o.* "
			         . "FROM situacoes s INNER JOIN opcoes o ON s.id_situacao=o.id_situacao "
			         . "WHERE s.primeira_situacao='Y' AND s.id_caso=" . $caso
					 . " ORDER BY RAND()" ;
		}
		else {
		
			// Primeiro calculamos a variação do termômetro de acordo com a opção escolhida
			$_SESSION['termometro'] = $_SESSION['termometro'] + $_SESSION['variacao_termometro_' . $tipo_opcao];
			
			if ( $_SESSION['termometro'] > 100 ) {
				$_SESSION['termometro'] = 100 ;
			}
			
			$query = "SELECT s.*,o.* "
			         . " FROM situacoes s INNER JOIN opcoes o ON s.id_situacao=o.id_situacao"
			         . " WHERE s.primeira_situacao='N' AND s.id_situacao=" . $proxima_situacao
					 . " ORDER BY RAND()" ;
		}
		
		// Calcula qual narrativa será apresentada (Boa, Média ou Ruim)
		//
		if ( $_SESSION['termometro'] > $nivelNarrativaBoa ) {
			$opNarrativa = "problematica_boa" ;
		}
		elseif ( $_SESSION['termometro'] > $nivelNarrativaMedia ) {
			$opNarrativa = "problematica_media" ;
		}
		else {
			$opNarrativa = "problematica_ruim" ;
		}

		$rs = mysql_query( $query ) ;
		//
		if( mysql_num_rows($rs) ) {
			//
			$counter = 1 ;
			
			//
			while( $result = mysql_fetch_array($rs) ) {
				//
				if ( $counter == 1 ) {
					$strParametros	= $strParametros . "&id_situacao=" . $result['id_situacao']
							        .	"&narrativa=" . urlencode(trim($result[$opNarrativa])) ;
									
									$descricao = urlencode(trim($result['descricao'])) ;
				}
				
				$strParametros	=	$strParametros . "&id_op" . $counter . "=" . $result['id_opcao']
								. 	"&txt_op" . $counter . "=" . urlencode(trim($result['texto']))
								. 	"&aviso_op" . $counter . "=" . urlencode(trim($result['aviso']))
								.   "&tipo_op" . $counter . "=" . $result['tipo'] ;
								
				$queryFinal = "SELECT id_situacao "
							 . " FROM situacoes "
							 . " WHERE situacao_final='Y' AND id_situacao=" . $result['id_situacao_destino'] ;
				
				$rsFinal = mysql_query( $queryFinal ) ;
				//
				if( mysql_num_rows($rsFinal) ) {
					$strParametros	=	$strParametros . "&proxima_situacao" . $counter . "=*" . $result['id_situacao_destino'] ;
				}
				else {
					$strParametros	=	$strParametros . "&proxima_situacao" . $counter . "=" . $result['id_situacao_destino'] ;
				}
				
				unset($rsFinal) ;
								
				$counter = $counter + 1 ;
			}
			
			$strParametros	=	$strParametros .  "&termometro=" . $_SESSION['termometro']
										       .  "&descricao=" . $descricao ;
		}
		elseif ( $proxima_situacao != "" ) {
		
			// Primeiro calculamos a variação do termômetro de acordo com a opção escolhida
			// $_SESSION['termometro'] = $_SESSION['termometro'] + $_SESSION['variacao_termometro_' . $tipo_opcao];
			
			// if ( $_SESSION['termometro'] > 100 ) {
			//	$_SESSION['termometro'] = 100 ;
			//}
			
			$query = "SELECT id_situacao,descricao,final "
			         . " FROM situacoes "
			         . " WHERE id_situacao=" . $proxima_situacao ;

					$rs = mysql_query( $query ) ;
			//
			if( $rs ) {
				$row = mysql_fetch_row($rs);
				$strParametros	=	"&id_situacao=" . $row[0]
									.	"&narrativa=" . urlencode(trim($row[2]))
									.	"&termometro=" . $_SESSION['termometro']
									.	"&descricao=" . urlencode(trim($row[1])) ;

				unset($rs) ;
			}
			else {
				$strParametros = "&erro=1" ;
			}
		}
		else {
			$strParametros = "&erro=1" ;
		}

	}
	// SITUAÇÕES >
	
	// DICAS <
	elseif( $tipo == 3 ) {
		//
		$query = "SELECT * FROM dicas WHERE id_situacao=" . $situacao . " ORDER BY titulo LIMIT 0,4" ;
		//
		$rs = mysql_query( $query ) ;
		//
		if( mysql_num_rows($rs) ) {
			//
			$counter = 1 ;
			//
			while( $result = mysql_fetch_array($rs) ) {
				//
				$strParametros =  $strParametros . "&titulo" . $counter . "=" . urlencode(trim($result['titulo']))
							   .  "&texto" . $counter . "=" . urlencode(trim($result['texto'])) ;
				
				$counter = $counter + 1 ;
			}
			//
			$strParametros = $strParametros . "&recCount=" . ($counter-1) ;
		}
		else {
			$strParametros = "&erro=1" ;
		}
		//
		
	}
	// DICAS >
	
	// DOCUMENTOS <
	elseif( $tipo == 4 ) {
		//
		$query = "SELECT * FROM documentos WHERE id_caso=" . $caso . " ORDER BY titulo_adaptado" ;
		$rs = mysql_query( $query ) ;
		//
		if ( mysql_num_rows($rs) ) {
			//
			$counter = 1 ;
			//			 
			while( $result = mysql_fetch_array( $rs ) ) {
				//
				$strParametros = $strParametros	. "&arquivo" . $counter . "=" . $result['nome']
												. "&url" . $counter . "=" . urlencode(trim($result['url']))
												. "&titulo_adaptado" . $counter . "=" . urlencode(trim($result['titulo_adaptado']))
												. "&descricao" . $counter . "=" . urlencode(trim($result['descricao'])) ;
				$counter = $counter + 1 ;
			}
			//
			$strParametros 	= $strParametros . "&recCount=" . ($counter-1) ;
		}
		else {
			$strParametros = "&erro=1" ;
		}
	}
	// DOCUMENTOS >
	
	// BUSCA <
	elseif( $tipo == 5 ) {
		//
		$query = "SELECT * FROM documentos WHERE ( nome LIKE '%" . $busca . "%' ) OR "
		       . "( url LIKE '%" . $busca . "%') OR "
		       . "( titulo LIKE '%" . $busca . "%') OR "
		       . "( titulo_adaptado LIKE '%" . $busca . "%') OR "
		       . "( descricao LIKE '%" . $busca . "%') OR "
			   . "( palavras_chave LIKE '%" . $busca . "%' )" ;
			   
		$rs = mysql_query( $query ) ;
		//
		if ( mysql_num_rows($rs) ) {
			//
			$counter = 1 ;
			//			 
			while( $result = mysql_fetch_array( $rs ) ) {
				//
				$strParametros = $strParametros	. "&arquivo" . $counter . "=" . $result['nome']
												. "&id_caso" . $counter . "=" . $result['id_caso']
												. "&url" . $counter . "=" . urlencode(trim($result['url']))
												. "&titulo" . $counter . "=" . urlencode(trim($result['titulo']))
												. "&titulo_adaptado" . $counter . "=" . urlencode(trim($result['titulo_adaptado']))
												. "&descricao" . $counter . "=" . urlencode(trim($result['descricao'])) ;
				$counter = $counter + 1 ;
			}
			//
			$strParametros 	= $strParametros . "&recCount=" . ($counter-1) ;
		}
		else {
			$strParametros = "&erro=1" ;
		}
	}
	// BUSCA >
	
	// PALAVRAS CHAVE (24 ITENS)<
	elseif( $tipo == 6 ) {
	//
		$query = "SELECT palavras_chave FROM documentos ORDER BY RAND()" ;
			   
		$rs = mysql_query( $query ) ;
		//
		if ( mysql_num_rows($rs) ) {
			//
			while( $result = mysql_fetch_array( $rs ) ) {
				//
				$palavras_chave = $palavras_chave . str_replace(";",",",trim($result['palavras_chave'])) . "," ;
			}

			$palavras_chave = str_replace("\t","",$palavras_chave) ;			
			$arrPalavrasChave = split(",", $palavras_chave);
			
			sort($arrPalavrasChave) ;
			
			$strParametros = "&palavras_chave=" ;
			$counter = 0 ;

			for ($i=0; $i<sizeOf($arrPalavrasChave); $i++) {

				if ( strlen($arrPalavrasChave[$i]) > 0 ) {

					if ( strpos($strParametros,$arrPalavrasChave[$i]) == false ) {
						$strParametros = $strParametros . $arrPalavrasChave[$i] . "," ;
						$counter = $counter + 1;
					}
					
				}
				
				if ($counter > 34) {
					break;
				}

			}

			$strParametros = rtrim($strParametros,",");
			//
		}
		else {
			$strParametros = "&erro=1" ;
		}	
	}
	// PALAVRAS CHAVE >
	
	// FINAL <
	elseif( $tipo == 7 ) {
		
	}
	// FINAL >
	else {
		$strParametros = "&erro=2" ;
	}
	// CASO DE ERRO >
	
	// Libera o resultado da busca
	if ( isset($rs) ) {
		mysql_free_result( $rs ) ;
	}
	
	// Libera o resultado da busca
	if ( isset($rs2) ) {
		db_clear_result( $rs2 ) ;
	}
	
	// Fecha a conexão com a base
	mysql_close( $link ) ;
	
	// Passa os parâmetros para o flash
	echo $strParametros ; 
?>
