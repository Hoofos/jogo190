<?php
session_start();

if( !isset( $_SESSION['administrador'] ) ) {
	header( "Location: login.php" ) ; 
}

include( "classes/cls_connect.php" ) ;
include( "classes/cls_arquivo.php" ) ;
include( "classes/cls_situacoes.php"  ) ;
include( "classes/cls_casos.php"  ) ;

if( isset( $_POST['Incluir'] ) ) {
	$trans = array("'" => "`");
								
	if ( isset( $_POST['primeira_situacao'] ) ) {
		if ( $_POST['primeira_situacao'] == "" ) {
			$primeira_situacao = "N" ;
		}
		else {
			$primeira_situacao = $_POST['primeira_situacao'] ;
		}
	}
	else {
		$primeira_situacao = "N" ;
	}

	if ( isset( $_POST['situacao_final'] ) ) {
		if ( $_POST['situacao_final'] == "" ) {
			$situacao_final = "N" ;
		}
		else {
			$situacao_final = $_POST['situacao_final'] ;
		}
	}
	else {
		$situacao_final = "N" ;
	}
	
	// Instanciando novo objeto banda.
	$obj_situacao 			      			= new cls_situacoes ;
	$obj_situacao->id_caso      		    = $_POST['obCaso'] ;
	$obj_situacao->primeira_situacao		= $primeira_situacao ;
	$obj_situacao->situacao_final			= $situacao_final ;
	$obj_situacao->narrativa_final		  	= ( isset( $_POST['TaFinal'      ] )  ) ? strtr($_POST['TaFinal' ], $trans) : ''   ;
	$obj_situacao->identificador  			= ( trim( $_POST['obTxtIdentificador'      ] ) != '' ) ? strtr($_POST['obTxtIdentificador' ], $trans) : ''  ;
	$obj_situacao->descricao	  			= ( trim( $_POST['TxtDescricao'      ] ) != '' ) ? strtr($_POST['TxtDescricao' ], $trans) : ''  ;
	$obj_situacao->problematica_boa         = ( isset( $_POST['TaProblematicaBoa'      ] )  ) ? strtr($_POST['TaProblematicaBoa'      ], $trans) : '' ;
	$obj_situacao->problematica_media       = ( isset( $_POST['TaProblematicaMedia'      ] )  ) ? strtr($_POST['TaProblematicaMedia'      ], $trans) : '' ;
	$obj_situacao->problematica_ruim        = ( isset( $_POST['TaProblematicaRuim'      ] )  ) ? strtr($_POST['TaProblematicaRuim'      ], $trans) : '' ;	
	
	// upload.
	$obj_situacao->nome_objeto_1_temporario    = ( trim( $_FILES['objeto_1']['name']     ) != '' ) ? $_FILES['objeto_1']['name']     : '' ;
	$obj_situacao->caminho_objeto_1_temporario = ( trim( $_FILES['objeto_1']['tmp_name'] ) != '' ) ? $_FILES['objeto_1']['tmp_name'] : '' ;
	
	$obj_situacao->nome_objeto_2_temporario    = ( trim( $_FILES['objeto_2']['name']     ) != '' ) ? $_FILES['objeto_2']['name']     : '' ;
	$obj_situacao->caminho_objeto_2_temporario = ( trim( $_FILES['objeto_2']['tmp_name'] ) != '' ) ? $_FILES['objeto_2']['tmp_name'] : '' ;
	
	$obj_situacao->nome_objeto_3_temporario    = ( trim( $_FILES['objeto_3']['name']     ) != '' ) ? $_FILES['objeto_3']['name']     : '' ;
	$obj_situacao->caminho_objeto_3_temporario = ( trim( $_FILES['objeto_3']['tmp_name'] ) != '' ) ? $_FILES['objeto_3']['tmp_name'] : '' ;
	
	// Salva na base de dados.
	$obj_situacao->salvar() ;
	$flag_login = 0 ;

	unset($obj_situacao) ;
	
	?><script language="javascript">alert( 'Situação cadastrada com sucesso !!!' ) ; 
	document.location.replace( "situacoes_l.php" ); </script><?php
}
?>

<html>
<head>
<title>Sistema Administrativo Ronda Policial - Situações</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<link href="include/style.css" rel="stylesheet" type="text/css">

<script language="javascript" src="include/commonScripts.js"></SCRIPT>

</head>

<body>

<?php include( "include/menu.php"  ) ; ?>

<div id="divConteudo">
	<table class="texto" width="100%"  border="0" cellspacing="5" cellpadding="0">
		<tr><td height="40" valign="middle" class="titulo" bgcolor='#333333'>&nbsp; Situações - Cadastro</td></tr>
		<tr>
			<td height="40" valign="bottom">
			
				<table width="100%"  border="0" cellspacing="0" cellpadding="0">
					<form name="BuscaForm" action="situacoes_l.php" method="post">
					<tr>
						<td>&nbsp;<a href='situacoes_l.php'>Listar Situações</a></td>
						<td align="right">
					
							<table border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td>&nbsp;<input type="text" size="15" name="txtBusca">&nbsp;<a href="#" onCLick="BuscaForm.submit();">Buscar</a></td>
								</tr>
							</table>
								
						</td>
					</tr>
					</form>
				</table>
				
			</td>
		</tr>
	  	<tr><td bgcolor='#FFFFFF' height='3'><img src='imagens/spacer.gif' height='2'></td></tr>
		<form method="post" action="situacoes_c.php" enctype="multipart/form-data" name="frm" >
		<input type="hidden" name="Incluir" value="1">
		<tr>
			<td>
			
				<table class="texto" width="100%"  border="0" cellspacing="0" cellpadding="5">
					<tr>
						<td align="right"><strong>* Caso</strong></td>
						<td align="left">
							<select id="obCaso" name="obCaso">
								<option value=""></option>
								<?php
									// Instanciando objeto
									$obj_casos = new cls_casos ;
	
									$q = $obj_casos->listar_tudo() ;
									
									// Caso exista algum caso cadastrada
									if ( mysql_num_rows( $q ) ) {
										while( $result = mysql_fetch_array( $q ) ) {
											echo "<option value=\"" . $result['id_caso'] . "\">" . $result['titulo'] . "</option>" ;
										}
									}
								?>
							</select>
						</td>
					</tr>
					<tr>
						<td align="right"><strong>Primeira situação do caso</strong></td>
						<td align="left"><input name="primeira_situacao" type="checkbox" id="primeira_situacao" value="Y" /></td>
					</tr>
					<tr>
						<td align="right"><strong>Situação final do caso</strong></td>
						<td align="left"><input name="situacao_final" type="checkbox" id="situacao_final" value="Y" onClick="HabilitaFinal();" /></td>
					</tr>
					<tr>
						<td align="right"><strong>* Identificador</strong></td>
						<td align="left"><input name="obTxtIdentificador" type="text" id="obTxtIdentificador" size="50" maxlength="120"></td>
					</tr>
					<tr>
						<td align="right"><strong>descricao</strong></td>
						<td align="left"><input name="TxtDescricao" type="text" id="TxtDescricao" size="50" maxlength="500"></td>
					</tr>
					<tr>
						<td align="right" valign="top"><strong>Problemática Boa</strong></td>
						<td align="left"><textarea name="TaProblematicaBoa" id="TaProblematicaBoa" style="width:300px; height:100px;"></textarea></td>
					</tr>
					<tr>
						<td align="right" valign="top"><strong>Problemática Média</strong></td>
						<td align="left"><textarea name="TaProblematicaMedia" id="TaProblematicaMedia" style="width:300px; height:100px;"></textarea></td>
					</tr>
					<tr>
						<td align="right" valign="top"><strong>Problemática Ruim</strong></td>
						<td align="left"><textarea name="TaProblematicaRuim" id="TaProblematicaRuim" style="width:300px; height:100px;"></textarea></td>
					</tr>
					<tr>
						<td align="right" valign="top"><strong>Final</strong></td>
						<td align="left"><textarea name="TaFinal" id="TaFinal" style="width:300px; height:100px;" disabled></textarea></td>
					</tr>
					<tr>
						<td align="right"><strong>* Objeto Flash 1</strong></td>
						<td align="left"><input type="file" name="objeto_1" maxlength="80" style="width:300px;"></td>
					</tr>
					<tr>
						<td align="right"><strong>* Objeto Flash 2</strong></td>
						<td align="left"><input type="file" name="objeto_2" maxlength="80" style="width:300px;"></td>
					</tr>
					<tr>
						<td align="right"><strong>* Objeto Flash 3</strong></td>
						<td align="left"><input type="file" name="objeto_3" maxlength="80" style="width:300px;"></td>
					</tr>
				</table>
				
			</td>
		</tr>
		<tr><td bgcolor='#FFFFFF' height='2'><img src='imagens/spacer.gif' height='2'></td></tr>
		<tr>
			<td align="right">
				<input type="button" onClick="frm.submit();" value="Cadastrar">&nbsp; 
				<input type="button" value="Cancelar" onClick="location.replace('situacoes_l.php');">
			</td>
		</tr>
		</form>
	</table>
</div>

<div id="divErro" style="position:absolute; top:160px; left:360px; width:300px; background-color:#FFFFFF; visibility:hidden;">
	<table class="Erro" bordercolor='#FFFFFF' width="300" border="2" cellpadding="25" cellspacing="0">
	  <tr>
		<td align="center">
			<div id="divTextoErro"></div><br><br><br>
			<input type="button" value="Fechar" onClick="MM_showHideLayers('divErro','','hide','divForm','','show');">
		</td>
	  </tr>
	</table>
</div>

</body>
</html>