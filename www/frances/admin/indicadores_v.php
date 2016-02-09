<?php
Header('Cache-Control: no-cache');
Header('Pragma: no-cache');

session_start();

if( !isset($_SESSION['administrador']) ) {
	header("Location: login.php"); 
}

include("classes/cls_connect.php");
include("classes/cls_arquivo.php");
include("classes/cls_indicadores.php");


// Main --------------------------------------------------------------
$obj_indicadores = new cls_indicadores ;

if( isset( $_GET['excluir'] ) || isset( $_POST['Excluir'] ) ) {
	$obj_indicadores->id_indicador = $_SESSION['id_indicador'] ;
	$obj_indicadores->excluir() ;
	
	unset($obj_indicadores) ;
	
	?><script language="javascript"> alert('Indicador excluído com sucesso !!!'); 
	document.location.replace("indicadores_l.php"); </script><?php
}
elseif( isset( $_GET['alterar'] ) || isset( $_POST['Alterar'] ) ) {
	$trans = array("'" => "`");
	
	// Instanciando novo objeto banda.
	$obj_indicadores->id_indicador	    = $_SESSION['id_indicador'] ;
	$obj_indicadores->nome              = $_POST['obTxtNome'] ;
	$obj_indicadores->descricao         = $_POST['obTaDescricao'] ;
	$obj_indicadores->nivel_min         = $_POST['NivelMin'] ;
	$obj_indicadores->nivel_max         = $_POST['NivelMax'] ;
	
	// upload.
	$obj_indicadores->nome_icone_temporario    = ( trim( $_FILES['icone']['name']     ) != '' ) ? $_FILES['icone']['name']     : '' ;
	$obj_indicadores->icone_caminho_temporario = ( trim( $_FILES['icone']['tmp_name'] ) != '' ) ? $_FILES['icone']['tmp_name'] : '' ;
	
	// Salva na base de dados.
	$obj_indicadores->salvar() ;
	$flag_login = 0 ;

	unset($obj_indicadores) ;
	
	?><script language="javascript"> alert( 'Dados do Indicador alterados com sucesso !!!' ) ; 
	document.location.replace( "indicadores_l.php" ); </script><?php
}
else {
	$_SESSION['id_indicador']        = $_GET['id'] ;
	$obj_indicadores->id_indicador   = $_GET['id'] ;
	$oIndicador			             = mysql_fetch_assoc( $obj_indicadores->listar_reg() ) ;
	
	unset($obj_indicadores) ;
}
?>

<html>
<head>
<title>Sistema Administrativo Ronda Policial - Indicadores</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<link href="include/style.css" rel="stylesheet" type="text/css">

<script language="javascript" src="include/commonScripts.js"></SCRIPT>

</head>

<body>

<?php include( "include/menu.php"  ) ; ?>

<div id="divConteudo">
	<table class="texto" width="100%"  border="0" cellspacing="5" cellpadding="0">
		<tr><td height="40" valign="middle" class="titulo" bgcolor='#333333'>&nbsp; Indicadores - Visualização</td></tr>
		<tr>
			<td height="40" valign="bottom">
				<table width="100%"  border="0" cellspacing="0" cellpadding="0">
					<form name="BuscaForm" action="indicadores_l.php" method="post">
					<tr>
						<td>
							<a href='#' onClick="frm.action='indicadores_v.php?excluir=true'; frm.submit();">Excluir</a> |
							<a href='#' onClick="frm.action='indicadores_v.php?alterar=true'; frm.submit();">Alterar</a> |
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
		<form name="frm" action="indicadores_v.php" enctype="multipart/form-data" method="post">
		<tr>
			<td>
			
				<table class="texto" width="100%"  border="0" cellspacing="0" cellpadding="5">
					<tr>
						<td align="right"><strong>* Nome</strong></td>
						<td align="left"><input name="obTxtNome" type="text" id="obTxtNome" value="<?php echo $oIndicador['nome'] ; ?>" size="50" maxlength="120"></td>
					</tr>
					<tr>
						<td align="right" valign="top"><strong>* Descrição</strong></td>
						<td align="left"><textarea name="obTaDescricao" id="obTaDescricao" style="width:300px; height:100px;"><?php echo $oIndicador['descricao'] ; ?></textarea></td>
					</tr>
					<tr>
						<td align="right"><strong>Nível Mínimo</strong></td>
						<td align="left">
							<select name="NivelMin">
								<?php
									for ($i=-10; $i<11; $i++) {
									
										$varSelected = "" ;
									
										if ($i == $oIndicador['nivel_min']) {
											$varSelected = "selected" ;
										}
									
										echo "<option value=\"" . $i . "\" ". $varSelected . ">" . $i . "</option>" ;
									}
								?>
							</select>
						</td>
					</tr>
					<tr>
						<td align="right"><strong>Nível Máximo</strong></td>
						<td align="left">
							<select name="NivelMax">
								<?php
									for ($i=-10; $i<11; $i++) {
									
										$varSelected = "" ;
									
										if ($i == $oIndicador['nivel_max']) {
											$varSelected = "selected" ;
										}
									
										echo "<option value=\"" . $i . "\" ". $varSelected . ">" . $i . "</option>" ;
									}
								?>
							</select>
						</td>
					</tr>
					<tr>
						<td align="right"><strong>* Ícone</strong></td>
						<td align="left"><input type="file" name="icone" maxlength="80" style="width:300px;"></td>
					</tr>
				</table>
				
			</td>
		</tr>
		<tr><td bgcolor='#FFFFFF' height='2'><img src='imagens/spacer.gif' height='2'></td></tr>
		<tr>
			<td colspan="2" align="right">
				<input type="button" value="Excluir" onClick="frm.action='indicadores_v.php?excluir=true'; frm.submit();">&nbsp;
				<input type="button" value="Alterar" onClick="frm.action='indicadores_v.php?alterar=true'; checkrequired('frm');">&nbsp;
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

<?php unset($oIndicador) ; ?>