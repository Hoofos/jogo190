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
include("classes/cls_indicadores.php");

// Main --------------------------------------------------------------
$obj_casos = new cls_casos ;

if( isset( $_GET['excluir'] ) || isset( $_POST['Excluir'] ) ) {
	$obj_casos->id_caso = $_SESSION['id_caso'] ;
	$obj_casos->excluir() ;
	
	unset($obj_casos) ;
	
	?><script language="javascript"> alert('Caso excluído com sucesso !!!'); 
	document.location.replace("casos_l.php"); </script><?php
}
elseif( isset( $_GET['alterar'] ) || isset( $_POST['Alterar'] ) ) {
	$trans = array("'" => "`");

	// Instanciando novo objeto banda.
	$obj_casos->id_caso	    = $_SESSION['id_caso'] ;
	$obj_casos->titulo      = ( trim( $_POST['obTxtTitulo'      ] ) != '' ) ? strtr($_POST['obTxtTitulo'      ], $trans) : die( condRetErrCampos  . " >>> TITULO"          ) ;
	$obj_casos->descricao   = ( trim( $_POST['obTaDescricao'     ] ) != '' ) ? strtr($_POST['obTaDescricao'     ], $trans) : die( condRetErrCampos  . " >>> DESCRICAO"         ) ;
	$obj_casos->resumo  	 = ( trim( $_POST['obTaResumo'     ] ) != '' ) ? strtr($_POST['obTaResumo'     ], $trans) : die( condRetErrCampos  . " >>> RESUMO"         ) ;
	$obj_casos->variacao_bom    = ( trim( $_POST['variacao_bom'     ] ) != '' ) ? strtr($_POST['variacao_bom'     ], $trans) : '' ;
	$obj_casos->variacao_medio  = ( trim( $_POST['variacao_medio'     ] ) != '' ) ? strtr($_POST['variacao_medio'     ], $trans) : '' ;
	$obj_casos->variacao_ruim   = ( trim( $_POST['variacao_ruim'     ] ) != '' ) ? strtr($_POST['variacao_ruim'     ], $trans) : '' ;
	$obj_casos->termometro      = ( trim( $_POST['termometro'     ] ) != '' ) ? strtr($_POST['termometro'     ], $trans) : '' ;

	// upload.
	$obj_casos->nome_icone_temporario    = ( trim( $_FILES['icone']['name']     ) != '' ) ? $_FILES['icone']['name']     : '' ;
	$obj_casos->icone_caminho_temporario = ( trim( $_FILES['icone']['tmp_name'] ) != '' ) ? $_FILES['icone']['tmp_name'] : '' ;
	
	// Salva na base de dados.
	$obj_casos->salvar() ;
	$flag_login = 0 ;

	// Atualiza o valor máximo de cada indicador para o caso
	$obj_indicadores = new cls_indicadores ;
	$obj_connect = new cls_connect ;
	$obj_connect->connect() ;
	
	$q = $obj_indicadores->listar_tudo() ;
	
	if ( mysql_num_rows( $q ) ) {
	
		// Primeiro apagamos todas os valores máximos cadastrados para o caso
		$deleteSql = "DELETE FROM vr_indicadores_casos WHERE id_caso=" . $_SESSION['id_caso'] ;
		$obj_connect->RunSql( $deleteSql );
		
		while( $result = mysql_fetch_array( $q ) ) {
			$nivel_indicador = $_POST['indicador_' . $result['id_indicador']] ;
			
			$insertSql = "INSERT INTO vr_indicadores_casos(id_indicador,id_caso,nivel_indicador) VALUES(" . $result['id_indicador'] . "," . $_SESSION['id_caso'] . "," . $nivel_indicador . ")" ;
			$obj_connect->RunSql( $insertSql );
		}
	}

	unset($obj_connect) ;
	unset($obj_indicadores) ;
	unset($obj_casos) ;
	
	?><script language="javascript"> alert( 'Dados do caso alterados com sucesso !!!' ) ; 
	document.location.replace( "casos_l.php" ); </script><?php
}
else {
	$_SESSION['id_caso'] = $_GET['id'] ;
	$obj_casos->id_caso  = $_GET['id'] ;
	$aCaso               = mysql_fetch_assoc( $obj_casos->listar_reg() ) ;
	
	unset($obj_casos) ;
}
?>

<html>
<head>
<title>Sistema Administrativo Ronda Policial - Casos</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<link href="include/style.css" rel="stylesheet" type="text/css">

<script language="javascript" src="include/commonScripts.js"></SCRIPT>

<script src="../Scripts/AC_RunActiveContent.js" type="text/javascript"></script>

</head>

<body>

<?php include( "include/menu.php"  ) ; ?>

<div id="divConteudo">
	<table class="texto" width="100%"  border="0" cellspacing="5" cellpadding="0">
		<tr><td height="40" valign="middle" class="titulo" bgcolor='#032867'>&nbsp; Casos - Visualização</td></tr>
			<tr>
				<td height="40" valign="bottom">
					<table width="100%"  border="0" cellspacing="0" cellpadding="0">
						<form name="BuscaForm" action="casos_l.php" method="post">
							<tr>
								<td>
									<a href='#' onClick="frm.action='casos_v.php?excluir=true'; frm.submit();">Excluir</a> |
									<a href='#' onClick="frm.action='casos_v.php?alterar=true'; frm.submit();">Alterar</a> |
									<a href='#' onClick="javascript:history.back();">Voltar</a>
								</td>
								<td align="right">
									<input type="text" size="15" name="txtBusca">&nbsp;<a href="#" onCLick="BuscaForm.submit();">Buscar</a>
								</td>
							</tr>
						</form>
					</table>
				</td>
			</tr>
		<tr><td bgcolor='#032867' height='3'><img src='imagens/spacer.gif' height='2'></td></tr>
		<form name="frm" action="casos_v.php" enctype="multipart/form-data" method="post">
		<tr>
			<td>

				<table class="texto" width="100%" cellpadding="5" cellspacing="0"  border="0">
					<tr>
						<td align="right"><strong>* Título</strong></td>
						<td align="left"><input name="obTxtTitulo" type="text" id="obTxtTitulo" value="<?php echo $aCaso['titulo'] ; ?>" size="50" maxlength="120"></td>
					</tr>
					<tr>
						<td align="right" valign="top"><strong>* Descrição</strong></td>
						<td align="left"><textarea name="obTaDescricao" style="height:100px; width:300px"><?php echo $aCaso['descricao'] ; ?></textarea></td> 
					</tr>
					<tr>
						<td align="right" valign="top"><strong>* Resumo</strong></td>
						<td align="left"><textarea name="obTaResumo" style="height:50px; width:300px"><?php echo $aCaso['resumo'] ; ?></textarea></td> 
					</tr>
					<tr>
						<td align="right" valign="top"><strong>Indicadores Máximos</strong></td>
						<td align="left">
							
							<table border="0" cellspacing="0" cellpadding="5">
								<?php
									$obj_indicadores = new cls_indicadores ;
									$obj_connect = new cls_connect ;
									
									// Caso exista algum indicador cadastrado
									
									$q = $obj_indicadores->listar_tudo() ;
									
									if ( mysql_num_rows( $q ) ) {
										while( $result = mysql_fetch_array( $q ) ) {
										
											// Selecionamos o nivel deste do indicador para o caso
											$selectSql = "SELECT nivel_indicador FROM vr_indicadores_casos WHERE id_caso=" . $_SESSION['id_caso'] . " AND id_indicador=" . $result['id_indicador'] ;
											$Rs = mysql_fetch_assoc(mysql_query( $selectSql ));

											echo "<tr><td>&nbsp<a href='indicadores_v.php?id=" . $result['id_indicador'] . "'>" . $result['nome'] . "</a></td>" ;
											echo "<td>" ;
											echo "<select name=\"indicador_" . $result['id_indicador'] . "\" style=\"width:40px;\">" ;
											
											for ($i=$result['nivel_min']; $i<=$result['nivel_max']; $i++) {
											
												$varSelected = "" ;
												
												if ($Rs['nivel_indicador'] == $i) {
													$varSelected = "selected" ;
												}
											
												echo "<option value=\"" . $i . "\" " . $varSelected . ">" . $i . "</option>" ;
											}
											
											echo "</select>" ;
											echo "</td></tr>" ;
										}
									}
									else {
										echo "<tr><td>Nenhum indicador cadastrado !</td></tr>" ;
									}
									
									unset($obj_indicadores) ;
								?>
							</table>
							
						</td>
					</tr>
					<tr>
						<td align="right" valign="top"><strong>* Termômetro</strong></td>
						<td align="left">
						
							<table class="texto" border="0" cellspacing="0" cellpadding="5">
								<tr>
									<td>&nbsp;<strong>Valor inicial</strong></td>
									<td align="left">
										<select name="termometro">
											<?php
												for ($i=0; $i<101; $i=$i+10) {
												
													$varSelected = "" ;
													
													if ( $i == $aCaso['termometro'] ) {
														$varSelected = "selected" ;
													}
													
													echo "<option value=\"" . $i . "\" " . $varSelected . ">" . $i . "</option>" ;
												}
											?>
										</select>
									</td>
								</tr>
								<tr>
									<td>&nbsp;<strong>Variação opção boa</strong></td>
									<td>
										<select name="variacao_bom">
											<?php
												for ($i=-10; $i<=10; $i++) {
												
													$varSelected = "" ;
													
													if ($i==$aCaso['variacao_bom']) {
														$varSelected = "selected" ;
													}
												
													echo "<option value=\"" . $i . "\" " . $varSelected . ">" . $i . "</option>" ;
												}
											?>
										</select>
									</td>
								</tr>
								<tr>
									<td>&nbsp;<strong>Variação opção média</strong></td>
									<td>
										<select name="variacao_medio">
											<?php
												for ($i=-10; $i<=10; $i++) {
												
													$varSelected = "" ;
													
													if ($i==$aCaso['variacao_medio']) {
														$varSelected = "selected" ;
													}
												
													echo "<option value=\"" . $i . "\" " . $varSelected . ">" . $i . "</option>" ;
												}
											?>
										</select>
									</td>
								</tr>
								<tr>
									<td>&nbsp;<strong>Variação opção ruim</strong></td>
									<td>
										<select name="variacao_ruim">
											<?php
												for ($i=-10; $i<=10; $i++) {
												
													$varSelected = "" ;
													
													if ($i==$aCaso['variacao_ruim']) {
														$varSelected = "selected" ;
													}
												
													echo "<option value=\"" . $i . "\" " . $varSelected . ">" . $i . "</option>" ;
												}
											?>
										</select>
									</td>
								</tr>
							</table>
							
						</td>
					</tr>
					<tr>
						<td align="right" valign="top"><strong>* Ícone</strong></td>
						<td align="left">
							<input type="file" name="icone" maxlength="80" style="width:300px;"><br>
							<script type="text/javascript">
								AC_FL_RunContent( 'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0','wmode','transparent','src','../casos/caso_<?php echo $aCaso['id_caso'] ; ?>/animacoes/icone_caso_<?php echo $aCaso['id_caso'] ; ?>','quality','high','pluginspage','http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash','movie','../casos/caso_<?php echo $aCaso['id_caso'] ; ?>/animacoes/icone_caso_<?php echo $aCaso['id_caso'] ; ?>' ); //end AC code
							</script>
							<noscript>
								<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0">
									<param name="movie" value="../casos/caso_<?php echo $aCaso['id_caso'] ; ?>/animacoes/icone_caso_<?php echo $aCaso['id_caso'] ; ?>.swf">
									<param name="quality" value="high">
									<embed src="../casos/caso_<?php echo $aCaso['id_caso'] ; ?>/animacoes/icone_caso_<?php echo $aCaso['id_caso'] ; ?>.swf" quality="high" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash"></embed>
								</object>
							</noscript>
						</td>
					</tr>
				</table>

			</td>
		</tr>
		<tr><td bgcolor='#032867' height='2'><img src='imagens/spacer.gif' height='2'></td></tr>
		<tr>
			<td colspan="2" align="right">
				<input type="button" value="Excluir" onClick="frm.action='casos_v.php?excluir=true'; frm.submit();">&nbsp;
				<input type="button" value="Alterar" onClick="frm.action='casos_v.php?alterar=true'; checkrequired('frm');">&nbsp;
				<input type="button" name="Voltar" value="Voltar" onClick="javascript:history.back()">
			</td>
		</tr>
		</form>
	</table>
</div>

<div id="divErro" style="position:absolute; top:160px; left:360px; width:300px; background-color:#FFFFFF; visibility:hidden;">
	<table class="Erro" bordercolor='#032867' width="300" border="2" cellpadding="25" cellspacing="0">
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

<?php unset($aCaso) ; ?>