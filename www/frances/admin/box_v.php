<?php
Header('Cache-Control: no-cache');
Header('Pragma: no-cache');

session_start();

if( !isset($_SESSION['administrador']) ) {
	header("Location: login.php"); 
}

include("classes/cls_connect.php");
include("classes/cls_arquivo.php");
include("classes/cls_boxes.php");
include("classes/cls_casos.php");
include("classes/cls_situacoes.php");


// Main --------------------------------------------------------------
$obj_boxes = new cls_boxes ;

if( isset( $_GET['excluir'] ) || isset( $_POST['Excluir'] ) ) {
	$obj_boxes->id_box = $_SESSION['id_box'] ;
	$obj_boxes->excluir() ;
	
	unset($obj_boxes) ;
	
	?><script language="javascript"> alert('Box excluído com sucesso !!!'); 
	document.location.replace("box_l.php"); </script><?php
}
elseif( isset( $_GET['alterar'] ) || isset( $_POST['Alterar'] ) ) {
	$trans = array("'" => "`");
	
	// Instanciando novo objeto banda.
	$obj_boxes->id_box	  			 = $_SESSION['id_box'] ;
	$obj_boxes->id_caso	   			 = $_POST['Caso'] ;
	$obj_boxes->titulo_1   		 	 = $_POST['titulo_1'] ;
	$obj_boxes->titulo_2   		 	 = $_POST['titulo_2'] ;
	$obj_boxes->fontes_1 			 = $_POST['fontes_1'] ;
	$obj_boxes->fontes_2 			 = $_POST['fontes_2'] ;
	$obj_boxes->conteudo_1     		 = $_POST['conteudo_1'] ;
	$obj_boxes->conteudo_2     		 = $_POST['conteudo_2'] ;

	// Salva na base de dados.
	$obj_boxes->salvar() ;
	$flag_login = 0 ;

	unset($obj_boxes) ;
	
	?><script language="javascript"> alert( 'Dados do Box alterados com sucesso !!!' ) ; 
	document.location.replace( "box_l.php" ); </script><?php
}
else {
	$where 					   = "WHERE id_caso=" . $_GET['Caso'] ;
	$aBox			           = mysql_fetch_assoc( $obj_boxes->listar_reg( $where ) ) ;
	
	if ( !mysql_fetch_assoc( $obj_boxes->listar_reg( $where ) )  ){
		echo "<script language=\"javascript\">window.location=\"box_c.php?Caso=" . $_GET['Caso'] . "\";</script>" ;
	}
	
	$_SESSION['id_box']        = $aBox['id_box'] ;
	$obj_boxes->id_box         = $aBox['id_box'] ;
	
	if ( isset($_GET['Caso']) ) {
		$id_caso = $_GET['Caso'] ;
	}
	else {
		$id_caso = $aDica['id_caso'] ;
	}
	
	unset($obj_boxes) ;
}
?>

<html>
<head>
<title>Sistema Administrativo Ronda Policial - Boxes</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<link href="include/style.css" rel="stylesheet" type="text/css">

<script language="javascript" src="include/commonScripts.js"></SCRIPT>

<script language="javascript" type="text/javascript">
<!--
	function RecarregaOpcoes(op,valor) {
		if (op == "Caso") {
			window.location = "box_v.php?Caso=" + valor ;
		}
		else if (op == "Situacao") {
			window.location = "box_v.php?Caso=<?php echo $id_caso ; ?>&Situacao=" + valor ;
		}
	}
//-->
</script>

</head>

<body>

<?php include( "include/menu.php"  ) ; ?>

<div id="divConteudo">
	<table class="texto" width="100%"  border="0" cellspacing="5" cellpadding="0">
		<tr><td height="40" valign="middle" class="titulo" bgcolor='#333333'>&nbsp; Boxes - Visualização</td></tr>
		<tr>
			<td height="40" valign="bottom">
				<table width="100%"  border="0" cellspacing="0" cellpadding="0">
					<form name="BuscaForm" action="box_l.php" method="post">
					<tr>
						<td>
							<a href='#' onClick="frm.action='box_v.php?excluir=true'; frm.submit();">Excluir</a> |
							<a href='#' onClick="frm.action='box_v.php?alterar=true'; frm.submit();">Alterar</a> |
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
		<form name="frm" action="box_v.php" method="post">
		<tr>
			<td>
			
				<table class="texto" width="100%"  border="0" cellspacing="0" cellpadding="5">
					<tr>
						<td align="right">
							<strong>Caso</strong>
						</td>
						<td>
							<select id="Caso" name="Caso" style="width:220px;" onChange="RecarregaOpcoes('Caso',this.value);">
								<?php
									// Instanciando objeto
									$obj_casos = new cls_casos ;
			
									$q = $obj_casos->listar_tudo() ;
									
									// Caso exista algum caso cadastrada
									if ( mysql_num_rows( $q ) ) {
										while( $result = mysql_fetch_array( $q ) ) {
										
											$varSelected = "" ;
										
											if ( $id_caso == $result['id_caso'] ) {
												$varSelected = "selected" ;
											}
											
											echo "<option value=\"" . $result['id_caso'] . "\" " . $varSelected . ">" . $result['titulo'] . "</option>" ;
										}
									}
								?>
							</select>
						</td>
					</tr>
					<tr>
						<td align="right"><strong>* Título 1</strong></td>
						<td align="left"><input name="titulo_1" type="text" id="titulo_1" value="<?php echo $aBox['titulo_1'] ; ?>" size="50" maxlength="120"></td>
					</tr>
					<tr>
						<td align="right" valign="top"><strong>* Fontes 1</strong></td>
						<td align="left"><textarea name="fontes_1" id="fontes_1" style="width:300px; height:100px;"><?php echo $aBox['fontes_1'] ; ?></textarea></td>
					</tr>
					<tr>
						<td align="right" valign="top"><strong>* Conteudo 1</strong></td>
						<td align="left"><textarea name="conteudo_1" id="conteudo_1" style="width:300px; height:100px;"><?php echo $aBox['conteudo_1'] ; ?></textarea></td>
					</tr>
					<tr>
						<td align="right"><strong>Título 2</strong></td>
						<td align="left"><input name="titulo_2" type="text" id="titulo_2" value="<?php echo $aBox['titulo_2'] ; ?>" size="50" maxlength="120"></td>
					</tr>
					<tr>
						<td align="right" valign="top"><strong>Fontes 2</strong></td>
						<td align="left"><textarea name="fontes_2" id="fontes_2" style="width:300px; height:100px;"><?php echo $aBox['fontes_2'] ; ?></textarea></td>
					</tr>
					<tr>
						<td align="right" valign="top"><strong>Conteudo 2</strong></td>
						<td align="left"><textarea name="conteudo_2" id="conteudo_2" style="width:300px; height:100px;"><?php echo $aBox['conteudo_2'] ; ?></textarea></td>
					</tr>
				</table>
				
			</td>
		</tr>
		<tr><td bgcolor='#FFFFFF' height='2'><img src='imagens/spacer.gif' height='2'></td></tr>
		<tr>
			<td colspan="2" align="right">
				<input type="button" value="Excluir" onClick="frm.action='box_v.php?excluir=true'; frm.submit();">&nbsp;
				<input type="button" value="Alterar" onClick="frm.action='box_v.php?alterar=true'; checkrequired('frm');">&nbsp;
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

<?php unset($aBox) ; ?>