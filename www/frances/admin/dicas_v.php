<?php
Header('Cache-Control: no-cache');
Header('Pragma: no-cache');

session_start();

if( !isset($_SESSION['administrador']) ) {
	header("Location: login.php"); 
}

include("classes/cls_connect.php");
include("classes/cls_arquivo.php");
include("classes/cls_dicas.php");
include("classes/cls_casos.php");
include("classes/cls_situacoes.php");


// Main --------------------------------------------------------------
$obj_dicas = new cls_dicas ;

if( isset( $_GET['excluir'] ) || isset( $_POST['Excluir'] ) ) {
	$obj_dicas->id_dica = $_SESSION['id_dica'] ;
	$obj_dicas->excluir() ;
	
	unset($obj_dicas) ;
	
	?><script language="javascript"> alert('Opção excluída com sucesso !!!'); 
	document.location.replace("dicas_l.php"); </script><?php
}
elseif( isset( $_GET['alterar'] ) || isset( $_POST['Alterar'] ) ) {
	$trans = array("'" => "`");
	
	// Instanciando novo objeto banda.
	$obj_dicas->id_dica	  			 = $_SESSION['id_dica'] ;
	$obj_dicas->id_caso	   			 = $_POST['Caso'] ;
	$obj_dicas->id_situacao   		 = $_POST['Situacao'] ;
	$obj_dicas->id_situacao_destino  = $_POST['SituacaoDestino'] ;
	$obj_dicas->titulo     		     = $_POST['obTxtTitulo'] ;
	$obj_dicas->texto     		 	 = $_POST['obTaTexto'] ;

	// Salva na base de dados.
	$obj_dicas->salvar() ;
	$flag_login = 0 ;

	unset($obj_dicas) ;
	
	?><script language="javascript"> alert( 'Dados da Dica alterados com sucesso !!!' ) ; 
	document.location.replace( "dicas_l.php" ); </script><?php
}
else {
	$_SESSION['id_dica']        = $_GET['id'] ;
	$obj_dicas->id_dica         = $_GET['id'] ;
	$aDica			            = mysql_fetch_assoc( $obj_dicas->listar_reg() ) ;
	
	if ( isset($_GET['Caso']) ) {
		$id_caso = $_GET['Caso'] ;
	}
	else {
		$id_caso = $aDica['id_caso'] ;
	}
	
	unset($obj_dicas) ;
}
?>

<html>
<head>
<title>Sistema Administrativo Ronda Policial - Dicas</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<link href="include/style.css" rel="stylesheet" type="text/css">

<script language="javascript" src="include/commonScripts.js"></SCRIPT>

<script language="javascript" type="text/javascript">
<!--
	function RecarregaOpcoes(op,valor) {
		if (op == "Caso") {
			window.location = "dicas_v.php?Caso=" + valor ;
		}
		else if (op == "Situacao") {
			window.location = "dicas_v.php?Caso=<?php echo $id_caso ; ?>&Situacao=" + valor ;
		}
	}
//-->
</script>

</head>

<body>

<?php include( "include/menu.php"  ) ; ?>

<div id="divConteudo">
	<table class="texto" width="100%"  border="0" cellspacing="5" cellpadding="0">
		<tr><td height="40" valign="middle" class="titulo" bgcolor='#333333'>&nbsp; Dicas - Visualização</td></tr>
		<tr>
			<td height="40" valign="bottom">
				<table width="100%"  border="0" cellspacing="0" cellpadding="0">
					<form name="BuscaForm" action="dicas_l.php" method="post">
					<tr>
						<td>
							<a href='#' onClick="frm.action='dicas_v.php?excluir=true'; frm.submit();">Excluir</a> |
							<a href='#' onClick="frm.action='dicas_v.php?alterar=true'; frm.submit();">Alterar</a> |
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
		<form name="frm" action="dicas_v.php" method="post">
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
						<td align="right">
							<strong>Situação</strong>
						</td>
						<td>
							<select id="Situacao" name="Situacao" style="width:250px;">
								<?php
									// Instanciando objeto
									$obj_situacoes = new cls_situacoes ;
			
									$where = "WHERE ( id_caso=" . $id_caso . " )" ;	
									$q = $obj_situacoes->listar_reg( $where ) ;
									
									// Caso exista algum caso cadastrada
									if ( mysql_num_rows( $q ) ) {
										while( $result = mysql_fetch_array( $q ) ) {
										
											$varSelected = "" ;
										
											if ( $result['id_situacao'] == $aDica['id_situacao'] ) {
												$varSelected = "selected" ;
											}
											
											echo "<option value=\"" . $result['id_situacao'] . "\" " . $varSelected . ">" . $result['identificador'] . "</option>" ;
										}
									}
								?>
							</select>
						</td>
					</tr>
					<tr>
						<td align="right"><strong>* Título</strong></td>
						<td align="left"><input name="obTxtTitulo" type="text" id="obTxtTitulo" value="<?php echo $aDica['titulo'] ; ?>" size="50" maxlength="120"></td>
					</tr>
					<tr>
						<td align="right" valign="top"><strong>* Texto</strong></td>
						<td align="left"><textarea name="obTaTexto" id="obTaTexto" style="width:300px; height:100px;"><?php echo $aDica['texto'] ; ?></textarea></td>
					</tr>
				</table>
				
			</td>
		</tr>
		<tr><td bgcolor='#FFFFFF' height='2'><img src='imagens/spacer.gif' height='2'></td></tr>
		<tr>
			<td colspan="2" align="right">
				<input type="button" value="Excluir" onClick="frm.action='dicas_v.php?excluir=true'; frm.submit();">&nbsp;
				<input type="button" value="Alterar" onClick="frm.action='dicas_v.php?alterar=true'; checkrequired('frm');">&nbsp;
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