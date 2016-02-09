<?php
Header('Cache-Control: no-cache');
Header('Pragma: no-cache');

session_start();

if( !isset($_SESSION['administrador']) ) {
	header("Location: login.php"); 
}

include("classes/cls_connect.php");
include("classes/cls_arquivo.php");
include("classes/cls_casos.php");
include("classes/cls_situacoes.php");


// Main --------------------------------------------------------------
$obj_situacoes = new cls_situacoes ;

if( isset( $_GET['excluir'] ) || isset( $_POST['Excluir'] ) ) {
	$obj_situacoes->id_situacao = $_SESSION['id_situacao'] ;
	$obj_situacoes->excluir() ;
	
	unset($obj_situacoes) ;
	
	?><script language="javascript"> alert('Situação excluída com sucesso !!!'); 
	document.location.replace("situacoes_l.php"); </script><?php
}
elseif( isset( $_GET['alterar'] ) || isset( $_POST['Alterar'] ) ) {
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
	$obj_situacoes->id_situacao	  			= $_SESSION['id_situacao'] ;
	$obj_situacoes->id_caso      		    = $_POST['Caso'] ;
	$obj_situacoes->primeira_situacao		= $primeira_situacao ;
	$obj_situacoes->situacao_final			= $situacao_final ;
	$obj_situacoes->narrativa_final		  	= ( isset( $_POST['TaFinal'      ] ) ) ? strtr($_POST['TaFinal' ], $trans) : ''  ;
	$obj_situacoes->identificador  			= ( trim( $_POST['obTxtIdentificador'      ] ) != '' ) ? strtr($_POST['obTxtIdentificador' ], $trans) : die( condRetErrCampos . " >>> IDENTIFICADOR"          ) ;
	$obj_situacoes->descricao	  			= ( trim( $_POST['TxtDescricao'      ] ) != '' ) ? strtr($_POST['TxtDescricao' ], $trans) : ''  ;
	$obj_situacoes->problematica_boa        = ( isset( $_POST['TaProblematicaBoa'      ] ) ) ? strtr($_POST['TaProblematicaBoa'      ], $trans) : '' ;
	$obj_situacoes->problematica_media      = ( isset( $_POST['TaProblematicaMedia'      ] ) ) ? strtr($_POST['TaProblematicaMedia'      ], $trans) : '' ;
	$obj_situacoes->problematica_ruim       = ( isset( $_POST['TaProblematicaRuim'      ] ) ) ? strtr($_POST['TaProblematicaRuim'      ], $trans) : '' ;
	
	// upload.
	$obj_situacoes->nome_objeto_1_temporario    = ( trim( $_FILES['objeto_1']['name']     ) != '' ) ? $_FILES['objeto_1']['name']     : '' ;
	$obj_situacoes->caminho_objeto_1_temporario = ( trim( $_FILES['objeto_1']['tmp_name'] ) != '' ) ? $_FILES['objeto_1']['tmp_name'] : '' ;
	
	$obj_situacoes->nome_objeto_2_temporario    = ( trim( $_FILES['objeto_2']['name']     ) != '' ) ? $_FILES['objeto_2']['name']     : '' ;
	$obj_situacoes->caminho_objeto_2_temporario = ( trim( $_FILES['objeto_2']['tmp_name'] ) != '' ) ? $_FILES['objeto_2']['tmp_name'] : '' ;
	
	$obj_situacoes->nome_objeto_3_temporario    = ( trim( $_FILES['objeto_3']['name']     ) != '' ) ? $_FILES['objeto_3']['name']     : '' ;
	$obj_situacoes->caminho_objeto_3_temporario = ( trim( $_FILES['objeto_3']['tmp_name'] ) != '' ) ? $_FILES['objeto_3']['tmp_name'] : '' ;

	// Salva na base de dados.
	$obj_situacoes->salvar() ;
	$flag_login = 0 ;

	unset($obj_situacoes) ;
	
	?><script language="javascript"> alert( 'Dados da Situação alterados com sucesso !!!' ) ; 
	document.location.replace( "situacoes_l.php" ); </script><?php
}
else {
	$_SESSION['id_situacao']      = $_GET['id'] ;
	$obj_situacoes->id_situacao   = $_GET['id'] ;
	$aSituacao			      = mysql_fetch_assoc( $obj_situacoes->listar_reg() ) ;
	
	unset($obj_situacoes) ;
}
?>

<html>
<head>
<title>Sistema Administrativo Ronda Policial - Situações</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<link href="include/style.css" rel="stylesheet" type="text/css">

<script language="javascript" src="include/commonScripts.js"></SCRIPT>

<script src="../Scripts/AC_RunActiveContent.js" type="text/javascript"></script>
</head>

<body onLoad="HabilitaFinal();">

<?php include( "include/menu.php"  ) ; ?>

<div id="divConteudo">
	<table class="texto" width="100%"  border="0" cellspacing="5" cellpadding="0">
		<tr><td height="40" valign="middle" class="titulo" bgcolor='#333333'>&nbsp; Situações - Visualização</td></tr>
		<tr>
			<td height="40" valign="bottom">
				<table width="100%"  border="0" cellspacing="0" cellpadding="0">
					<form name="BuscaForm" action="situacoes_l.php" method="post">
					<tr>
						<td>
							<a href='#' onClick="frm.action='situacoes_v.php?excluir=true'; frm.submit();">Excluir</a> |
							<a href='#' onClick="frm.action='situacoes_v.php?alterar=true'; frm.submit();">Alterar</a> |
							<a href='#' onClick="javascript:history.back();">Voltar</a>
						</td>
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
		<form name="frm" action="situacoes_v.php" enctype="multipart/form-data" method="post">
		<tr>
			<td>
			
				<table class="texto" width="100%"  border="0" cellspacing="0" cellpadding="5">
					<tr>
						<td align="right"><strong>* Caso</strong></td>
						<td align="left">
							<select id="Caso" name="Caso">
								<?php
									// Instanciando objeto
									$obj_casos = new cls_casos ;
	
									$q = $obj_casos->listar_tudo() ;
									
									// Caso exista algum caso cadastrada
									if ( mysql_num_rows( $q ) ) {
										while( $result = mysql_fetch_array( $q ) ) {
										
											$varSelected = "" ;
											
											if ( $aSituacao['id_caso'] == $result['id_caso'] ) {
												$varSelected = "selected" ;
											}
											
											echo "<option value=\"" . $result['id_caso'] . "\" " . $varSelected . " >" . $result['titulo'] . "</option>" ;
										}
									}
								?>
							</select>
						</td>
					</tr>
					<tr>
						<td align="right"><strong>Primeira situação do caso</strong></td>
						<?php
							$varChecked = "" ;
							
							if ( $aSituacao['primeira_situacao'] == 'Y' ) {
								$varChecked = 'checked' ;
							}
						?>
						<td align="left"><input name="primeira_situacao" type="checkbox" id="primeira_situacao" value="Y" <?php echo $varChecked ; ?> /></td>
					</tr>
					<tr>
						<td align="right"><strong>Situação final do caso</strong></td>
						<?php
							$varChecked = "" ;
							
							if ( $aSituacao['situacao_final'] == 'Y' ) {
								$varChecked = 'checked' ;
							}
						?>
						<td align="left"><input name="situacao_final" type="checkbox" id="situacao_final" value="Y" onClick="HabilitaFinal();" <?php echo $varChecked ; ?> /></td>
					</tr>
					<tr>
						<td align="right"><strong>* Identificador</strong></td>
						<td align="left"><input name="obTxtIdentificador" type="text" id="obTxtIdentificador" value="<?php echo $aSituacao['identificador'] ; ?>" size="50" maxlength="120"></td>
					</tr>
					<tr>
						<td align="right"><strong>Descrição</strong></td>
						<td align="left"><input name="TxtDescricao" type="text" id="TxtDescricao" value="<?php echo $aSituacao['descricao'] ; ?>" size="50" maxlength="120"></td>
					</tr>
					<tr>
						<td align="right" valign="top"><strong>Problemática Boa</strong></td>
						<td align="left"><textarea name="TaProblematicaBoa" id="TaProblematicaBoa" style="width:300px; height:100px;"><?php echo $aSituacao['problematica_boa'] ; ?></textarea></td>
					</tr>
					<tr>
						<td align="right" valign="top"><strong>Problemática Média</strong></td>
						<td align="left"><textarea name="TaProblematicaMedia" id="TaProblematicaMedia" style="width:300px; height:100px;"><?php echo $aSituacao['problematica_media'] ; ?></textarea></td>
					</tr>
					<tr>
						<td align="right" valign="top"><strong>Problemática Ruim</strong></td>
						<td align="left"><textarea name="TaProblematicaRuim" id="TaProblematicaRuim" style="width:300px; height:100px;"><?php echo $aSituacao['problematica_ruim'] ; ?></textarea></td>
					</tr>
					<tr>
						<td align="right" valign="top"><strong>Final</strong></td>
						<td align="left"><textarea name="TaFinal" id="TaFinal" style="width:300px; height:100px;"><?php echo $aSituacao['final'] ; ?></textarea></td>
					</tr>
					<tr>
						<td align="right" valign="top"><strong>* Objeto Flash 1</strong></td>
						<td align="left">
							<input type="file" name="objeto_1" maxlength="80" style="width:300px;"><br>
							<script type="text/javascript">
								AC_FL_RunContent( 'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0','wmode','transparent','src','../casos/caso_<?php echo $aSituacao['id_caso'] ; ?>/animacoes/objeto_<?php echo $aSituacao['id_situacao'] ; ?>_1','quality','high','pluginspage','http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash','movie','../casos/caso_<?php echo $aSituacao['id_caso'] ; ?>/animacoes/objeto_<?php echo $aSituacao['id_situacao'] ; ?>_1' ); //end AC code
							</script>
							<noscript>
								<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0">
									<param name="movie" value="../casos/caso_<?php echo $aSituacao['id_caso'] ; ?>/animacoes/objeto_<?php echo $aSituacao['id_situacao'] ; ?>_1.swf">
									<param name="quality" value="high">
									<embed src="../casos/caso_<?php echo $aSituacao['id_caso'] ; ?>/animacoes/objeto_<?php echo $aSituacao['id_situacao'] ; ?>_1.swf" quality="high" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash"></embed>
								</object>
							</noscript>
						</td>
					</tr>
					<tr>
						<td align="right" valign="top"><strong>* Objeto Flash 2</strong></td>
						<td align="left"><input type="file" name="objeto_2" maxlength="80" style="width:300px;"><br>
							<script type="text/javascript">
								AC_FL_RunContent( 'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0','wmode','transparent','src','../casos/caso_<?php echo $aSituacao['id_caso'] ; ?>/animacoes/objeto_<?php echo $aSituacao['id_situacao'] ; ?>_2','quality','high','pluginspage','http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash','movie','../casos/caso_<?php echo $aSituacao['id_caso'] ; ?>/animacoes/objeto_<?php echo $aSituacao['id_situacao'] ; ?>_2' ); //end AC code
							</script>
							<noscript>
								<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0">
									<param name="movie" value="../casos/caso_<?php echo $aSituacao['id_caso'] ; ?>/animacoes/objeto_<?php echo $aSituacao['id_situacao'] ; ?>_2.swf">
									<param name="quality" value="high">
									<embed src="../casos/caso_<?php echo $aSituacao['id_caso'] ; ?>/animacoes/objeto_<?php echo $aSituacao['id_situacao'] ; ?>_2.swf" quality="high" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash"></embed>
								</object>
							</noscript>
						</td>
					</tr>
					<tr>
						<td align="right" valign="top"><strong>* Objeto Flash 3</strong></td>
						<td align="left"><input type="file" name="objeto_3" maxlength="80" style="width:300px;"><br>
							<script type="text/javascript">
								AC_FL_RunContent( 'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0','wmode','transparent','src','../casos/caso_<?php echo $aSituacao['id_caso'] ; ?>/animacoes/objeto_<?php echo $aSituacao['id_situacao'] ; ?>_3','quality','high','pluginspage','http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash','movie','../casos/caso_<?php echo $aSituacao['id_caso'] ; ?>/animacoes/objeto_<?php echo $aSituacao['id_situacao'] ; ?>_3' ); //end AC code
							</script>
							<noscript>
								<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0">
									<param name="movie" value="../casos/caso_<?php echo $aSituacao['id_caso'] ; ?>/animacoes/objeto_<?php echo $aSituacao['id_situacao'] ; ?>_3.swf">
									<param name="quality" value="high">
									<embed src="../casos/caso_<?php echo $aSituacao['id_caso'] ; ?>/animacoes/objeto_<?php echo $aSituacao['id_situacao'] ; ?>_3.swf" quality="high" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash"></embed>
								</object>
							</noscript>
						</td>
					</tr>
				</table>
				
			</td>
		</tr>
		<tr><td bgcolor='#FFFFFF' height='2'><img src='imagens/spacer.gif' height='2'></td></tr>
		<tr>
			<td height="42" colspan="2" align="right">
				<input type="button" value="Excluir" onClick="frm.action='situacoes_v.php?excluir=true'; frm.submit();">&nbsp;
				<input type="button" value="Alterar" onClick="frm.action='situacoes_v.php?alterar=true'; frm.submit();">&nbsp;
				<input type="button" name="Voltar" value="Voltar" onClick="javascript:history.back()">
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

<?php unset($aSituacao) ; ?>