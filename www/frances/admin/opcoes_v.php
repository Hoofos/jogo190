<?php
Header('Cache-Control: no-cache');
Header('Pragma: no-cache');

session_start();

if( !isset($_SESSION['administrador']) ) {
	header("Location: login.php"); 
}

include("classes/cls_connect.php");
include("classes/cls_arquivo.php");
include("classes/cls_opcoes.php");
include("classes/cls_casos.php");
include("classes/cls_situacoes.php");
include( "classes/cls_indicadores.php"  ) ;


// Main --------------------------------------------------------------
$obj_opcoes = new cls_opcoes ;

if( isset( $_GET['excluir'] ) || isset( $_POST['Excluir'] ) ) {
	$obj_opcoes->id_opcao = $_SESSION['id_opcao'] ;
	$obj_opcoes->excluir() ;
	
	unset($obj_opcoes) ;
	
	?><script language="javascript"> alert('Opção excluída com sucesso !!!'); 
	document.location.replace("opcoes_l.php"); </script><?php
}
elseif( isset( $_GET['alterar'] ) || isset( $_POST['Alterar'] ) ) {
	$trans = array("'" => "`");
	
	// Instanciando novo objeto banda.
	$obj_opcoes->id_opcao	  			 = $_SESSION['id_opcao'] ;
	$obj_opcoes->id_caso	   			 = $_POST['Caso'] ;
	$obj_opcoes->id_situacao   			 = $_POST['Situacao'] ;
	$obj_opcoes->id_situacao_destino     = $_POST['SituacaoDestino'] ;
	$obj_opcoes->titulo     		     = $_POST['obTxtTitulo'] ;
	$obj_opcoes->texto     		 	     = $_POST['obTaTexto'] ;
	$obj_opcoes->aviso     			     = $_POST['TaAviso'] ;
	$obj_opcoes->tipo     			     = $_POST['tipo'] ;
	
	// Salva na base de dados.
	$obj_opcoes->salvar() ;
	$flag_login = 0 ;

	// Atualiza a variação de cada indicador para a opção
	$obj_indicadores = new cls_indicadores ;
	$obj_connect = new cls_connect ;
	$obj_connect->connect() ;
	
	$q = $obj_indicadores->listar_tudo() ;
	
	if ( mysql_num_rows( $q ) ) {
	
		// Primeiro apagamos todas as variações da opção
		$deleteSql = "DELETE FROM fr_indicadores_opcoes WHERE id_opcao=" . $_SESSION['id_opcao'] ;
		$obj_connect->RunSql( $deleteSql );
		
		while( $result = mysql_fetch_array( $q ) ) {
			$nivel_indicador = $_POST['indicador_' . $result['id_indicador']] ;
			
			$insertSql = "INSERT INTO fr_indicadores_opcoes(id_indicador,id_opcao,nivel_indicador) VALUES(" . $result['id_indicador'] . "," . $_SESSION['id_opcao'] . "," . $nivel_indicador . ")" ;
			$obj_connect->RunSql( $insertSql );
		}
	}

	unset($obj_indicadores) ;
	unset($obj_opcoes) ;
	
	?><script language="javascript"> alert( 'Dados da Opção alterados com sucesso !!!' ) ; 
	document.location.replace( "opcoes_l.php" ); </script><?php
}
else {
	$_SESSION['id_opcao']        = $_GET['id'] ;
	$obj_opcoes->id_opcao        = $_GET['id'] ;
	$aOpcao			             = mysql_fetch_assoc( $obj_opcoes->listar_reg() ) ;
	
	if ( isset($_GET['Caso']) ) {
		$id_caso = $_GET['Caso'] ;
	}
	else {
		$id_caso = $aOpcao['id_caso'] ;
	}
	
	unset($obj_opcoes) ;
}
?>

<html>
<head>
<title>Sistema Administrativo Ronda Policial - Opções</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<link href="include/style.css" rel="stylesheet" type="text/css">

<script language="javascript" src="include/commonScripts.js"></SCRIPT>

<script language="javascript" type="text/javascript">
<!--
	function RecarregaOpcoes(op,valor) {
		if (op == "Caso") {
			window.location = "opcoes_c.php?Caso=" + valor ;
		}
		else if (op == "Situacao") {
			window.location = "opcoes_c.php?Caso=<?php echo $id_caso ; ?>&Situacao=" + valor ;
		}
	}
//-->
</script>

<script src="../Scripts/AC_RunActiveContent.js" type="text/javascript"></script>

</head>

<body>

<?php include( "include/menu.php"  ) ; ?>

<div id="divConteudo">
	<table class="texto" width="100%"  border="0" cellspacing="5" cellpadding="0">
		<tr><td height="40" valign="middle" class="titulo" bgcolor='#333333'>&nbsp; Opções - Visualização</td></tr>
		<tr>
			<td height="40" valign="bottom">
				<table width="100%"  border="0" cellspacing="0" cellpadding="0">
					<form name="BuscaForm" action="opcoes_l.php" method="post">
					<tr>
						<td>
							<a href='#' onClick="frm.action='opcoes_v.php?excluir=true'; frm.submit();">Excluir</a> |
							<a href='#' onClick="frm.action='opcoes_v.php?alterar=true'; frm.submit();">Alterar</a> |
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
		<form name="frm" action="opcoes_v.php" enctype="multipart/form-data" method="post">
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
										
											if ( $result['id_situacao'] == $aOpcao['id_situacao'] ) {
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
						<td align="right">
							<strong>* Situação Destino</strong>
						</td>
						<td>
							<select id="SituacaoDestino" name="SituacaoDestino" style="width:250px;">
								<?php
									// Instanciando objeto
									$obj_situacoes = new cls_situacoes ;
			
									$where = "WHERE ( id_caso=" . $id_caso . " )" ;	
									$q = $obj_situacoes->listar_reg( $where ) ;
									
									// Caso exista algum caso cadastrada
									if ( mysql_num_rows( $q ) ) {
										while( $result = mysql_fetch_array( $q ) ) {
										
											$varSelected = "" ;
										
											if ( $result['id_situacao'] == $aOpcao['id_situacao_destino'] ) {
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
						<td align="right"><strong>* Tipo</strong></td>
						<td align="left">
						
							<?php
								$varBlected = "" ;
								$varMlected = "" ;
								$varRlected = "" ;
							
								if ($aOpcao['tipo'] == "B") {
									$varBlected = "selected" ;
								}
								elseif ($aOpcao['tipo'] == "M") {
									$varMlected = "selected" ;
								}
								elseif ($aOpcao['tipo'] == "R") {
									$varRlected = "selected" ;
								}
							?>
						
							<select name="tipo">
								<option value="B" <?php echo $varBlected ; ?>>Bom</option>
								<option value="M" <?php echo $varMlected ; ?>>Médio</option>
								<option value="R" <?php echo $varRlected ; ?>>Ruim</option>
							</select>
						</td>
					</tr>
					<tr>
						<td align="right"><strong>* Título</strong></td>
						<td align="left"><input name="obTxtTitulo" type="text" id="obTxtTitulo" value="<?php echo $aOpcao['titulo'] ; ?>" size="50" maxlength="120"></td>
					</tr>
					<tr>
						<td align="right" valign="top"><strong>* Texto</strong></td>
						<td align="left"><textarea name="obTaTexto" id="obTaTexto" style="width:300px; height:100px;"><?php echo $aOpcao['texto'] ; ?></textarea></td>
					</tr>
					<tr>
						<td align="right" valign="top"><strong>Aviso</strong></td>
						<td align="left"><textarea name="TaAviso" id="TaAviso" style="width:300px; height:100px;"><?php echo $aOpcao['aviso'] ; ?></textarea></td>
					</tr>
					<tr>
						<td align="right" valign="top"><strong>Variação Indicadores</strong></td>
						<td align="left">
							
							<table border="0" cellspacing="0" cellpadding="5">
							
								<?php
									$obj_indicadores = new cls_indicadores ;
									
									// Caso exista algum indicador cadastrado
									
									$q = $obj_indicadores->listar_tudo() ;
									
									if ( mysql_num_rows( $q ) ) {
										while( $result = mysql_fetch_array( $q ) ) {
											echo "<tr><td>&nbsp<a href='indicadores_v.php?id=" . $result['id_indicador'] . "'>" . $result['nome'] . "</a></td>" ;
											echo "<td>" ;
											echo "<select name=\"indicador_" . $result['id_indicador'] . "\" style=\"width:60px;\">" ;
											
											$variacoesSQL = "SELECT nivel_indicador FROM fr_indicadores_opcoes WHERE id_opcao=" . $_SESSION['id_opcao'] . " AND id_indicador=" . $result['id_indicador'] ;
											$variacoes = mysql_query($variacoesSQL);
											
											if (!$variacoes) {
												$valorVariacao = 0 ;
											}
											else {
												$row = mysql_fetch_assoc($variacoes)  ;
												$valorVariacao = $row['nivel_indicador'] ;
											}
											
											unset($variacoes) ;
											
											for ($i=-1; $i<=1; $i=$i+0.5) {
											
												$varSelected = "" ;
												
												if ($i==$valorVariacao) {
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
				</table>
				
			</td>
		</tr>
		<tr><td bgcolor='#FFFFFF' height='2'><img src='imagens/spacer.gif' height='2'></td></tr>
		<tr>
			<td colspan="2" align="right">
				<input type="button" value="Excluir" onClick="frm.action='opcoes_v.php?excluir=true'; frm.submit();">&nbsp;
				<input type="button" value="Alterar" onClick="frm.action='opcoes_v.php?alterar=true'; checkrequired('frm');">&nbsp;
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