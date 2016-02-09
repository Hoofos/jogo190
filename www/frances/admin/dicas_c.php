<?php
session_start();

if( !isset( $_SESSION['administrador'] ) ) {
	header( "Location: login.php" ) ; 
}

include( "classes/cls_connect.php" ) ;
include( "classes/cls_arquivo.php" ) ;
include( "classes/cls_dicas.php"  ) ;
include( "classes/cls_casos.php"  ) ;
include( "classes/cls_situacoes.php"  ) ;

if( isset( $_POST['Incluir'] ) ) {

	$trans = array("'" => "`");
								
	// Instanciando novo objeto banda.
	$obj_dica 			       = new cls_dicas ;
	$obj_dica->id_caso         = $_POST['obCaso'] ;
	$obj_dica->id_situacao     = $_POST['obSituacao'] ;
	$obj_dica->titulo          = ( trim( $_POST['obTxtTitulo'      ] ) != '' ) ? strtr($_POST['obTxtTitulo'      ], $trans) : die( condRetErrCampos . " >>> TITULO"          ) ;
	$obj_dica->texto           = ( trim( $_POST['obTaTexto'     ] ) != '' ) ? strtr($_POST['obTaTexto'     ], $trans) : die( condRetErrCampos . " >>> CHAMADA"         ) ;

	// Salva na base de dados.
	$obj_dica->salvar() ;
	$flag_login = 0 ;

	unset($obj_dica) ;
	
	?><script language="javascript"> alert( 'Dica cadastrada com sucesso !!!' ) ; 
	document.location.replace( "dicas_l.php" ); </script><?php
}

// A seleção de um caso é obrigatória. Caso não seja selecionado
// indicamos id_caso = 0
$id_caso = "" ;

if ( isset( $_GET['Caso'] ) ) {
	$id_caso = $_GET['Caso'] ;
}

if ( $id_caso == "" ) {
	$id_caso = 0 ;
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
			window.location = "dicas_c.php?Caso=" + valor ;
		}
		else if (op == "Situacao") {
			window.location = "dicas_c.php?Caso=<?php echo $id_caso ; ?>&Situacao=" + valor ;
		}
	}
//-->
</script>

</head>

<body>

<?php include( "include/menu.php"  ) ; ?>

<div id="divConteudo">
	<table class="texto" width="100%"  border="0" cellspacing="5" cellpadding="0">
		<tr><td height="40" valign="middle" class="titulo" bgcolor='#333333'>&nbsp; Dicas - Cadastro</td></tr>
		<tr>
			<td height="40" valign="bottom">
				<table width="100%"  border="0" cellspacing="0" cellpadding="0">
					<form name="BuscaForm" action="dicas_l.php" method="post">
					<tr>
						<td>&nbsp;<a href='dicas_l.php'>Listar Dicas</a></td>
						<td align="right">
							<input type="text" size="15" name="txtBusca"> &nbsp;<a href="#" onCLick="BuscaForm.submit();">Buscar</a>
						</td>
					</tr>
					</form>
				</table>
			</td>
		</tr>
		<tr><td bgcolor='#FFFFFF' height='3'><img src='imagens/spacer.gif' height='2'></td></tr>
		<form method="post" action="dicas_c.php" name="frm" >
		<input type="hidden" name="Incluir" value="1">
		<tr>
			<td>
				
				<table class="texto" width="100%"  border="0" cellspacing="0" cellpadding="5">
					<tr>
						<td align="right">
							<strong>* Caso</strong>
						</td>
						<td>
							<select id="obCaso" name="obCaso" style="width:220px;" onChange="RecarregaOpcoes('Caso',this.value);">
								<option value="">Selecione o caso...</option>
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
							<?php
								if ( ($id_caso != "") && ($id_caso != 0) ) {
							?>
							<strong>* Situação</strong>
						</td>
						<td>
							<select id="obSituacao" name="obSituacao" style="width:250px;">
								<option value="">Selecione uma situação...</option>
								<?php
									// Instanciando objeto
									$obj_situacoes = new cls_situacoes ;
			
									$where = "WHERE ( id_caso=" . $id_caso . " )" ;	
									$q = $obj_situacoes->listar_reg( $where ) ;
									
									// Caso exista algum caso cadastrada
									if ( mysql_num_rows( $q ) ) {
										while( $result = mysql_fetch_array( $q ) ) {
										
											$varSelected = "" ;
										
											if ( $id_situacao == $result['id_situacao'] ) {
												$varSelected = "selected" ;
											}
											
											echo "<option value=\"" . $result['id_situacao'] . "\" " . $varSelected . ">" . $result['identificador'] . "</option>" ;
										}
									}
								?>
							</select>
							
							<?php
								}
								else {
							?>
							<strong>* Situação</strong>
						</td>
						<td>
							<select id="obSituacao" name="obSituacao" style="width:220px;" disabled>
								<option value="">Selecione uma situação...</option>
							</select>
							<?php
								}
							?>
							
						</td>
					</tr>
					<tr>
						<td align="right"><strong>* Título</strong></td>
						<td align="left"><input name="obTxtTitulo" type="text" id="obTxtTitulo" size="50" maxlength="120"></td>
					</tr>
					<tr>
						<td align="right" valign="top"><strong>* Texto</strong></td>
						<td align="left"><textarea name="obTaTexto" id="obTaTexto" style="width:300px; height:100px;"></textarea></td>
					</tr>
				</table>

			</td>
		</tr>
		<tr><td bgcolor='#FFFFFF' height='2'><img src='imagens/spacer.gif' height='2'></td></tr>
		<tr>
			<td colspan="2" align="right">
				<input type="button" onClick="checkrequired('frm');" value="Cadastrar">&nbsp; 
				<input type="button" value="Cancelar" onClick="location.replace('dicas_l.php');">
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