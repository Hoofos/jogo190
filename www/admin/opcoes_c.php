<?php
session_start();

if( !isset( $_SESSION['administrador'] ) ) {
	header( "Location: login.php" ) ; 
}

include( "classes/cls_connect.php" ) ;
include( "classes/cls_arquivo.php" ) ;
include( "classes/cls_opcoes.php"  ) ;
include( "classes/cls_casos.php"  ) ;
include( "classes/cls_situacoes.php"  ) ;
include( "classes/cls_indicadores.php"  ) ;

if( isset( $_POST['Incluir'] ) ) {
	$trans = array("'" => "`");
								
	// Instanciando novo objeto do indicador.
	$obj_opcoes = new cls_opcoes ;

	$obj_opcoes->id_caso       			 = $_POST['obCaso'] ;
	$obj_opcoes->id_situacao   			 = $_POST['obSituacao'] ;
	$obj_opcoes->id_situacao_destino     = $_POST['obSituacaoDestino'] ;
	$obj_opcoes->titulo      			 = $_POST['obTxtTitulo'] ;
	$obj_opcoes->texto   		         = $_POST['obTaTexto'] ;
	$obj_opcoes->aviso  		         = $_POST['TaAviso'] ;
	$obj_opcoes->tipo  		             = $_POST['obTipo'] ;
	
	// Salva na base de dados.
	$obj_opcoes->salvar() ;
	$flag_login = 0 ;

	// Seleciona o id da  cadopção cadastrada
	$obj_connect = new cls_connect ;
	$maxId = $obj_connect->maxReg( 'id_opcao', 'vr_opcoes' ) ;
	$obj_connect->connect();
	
	// Cadastra o variação dos indicadores para a opção
	$obj_indicadores = new cls_indicadores ;
	
	$q = $obj_indicadores->listar_tudo() ;
	
	if ( mysql_num_rows( $q ) ) {
		while( $result = mysql_fetch_array( $q ) ) {
			$nivel_indicador = $_POST['indicador_' . $result['id_indicador']] ;
			
			$insertSql = "INSERT INTO vr_indicadores_opcoes(id_indicador,id_opcao,nivel_indicador) VALUES(" . $result['id_indicador'] . "," . $maxId . "," . $nivel_indicador . ")" ;
			$obj_connect->RunSql( $insertSql );
		}
	}

	unset($obj_connect) ;
	unset($obj_indicadores) ;
	unset($obj_opcoes) ;
	
	?><script language="javascript"> alert( 'Opção cadastrado com sucesso !!!' ) ; 
	document.location.replace( "opcoes_l.php" ); </script><?php
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

</head>

<body>

<?php include( "include/menu.php"  ) ; ?>

<div id="divConteudo">
	<table class="texto" width="100%"  border="0" cellspacing="5" cellpadding="0">
		<tr><td height="40" valign="middle" class="titulo" bgcolor='#333333'>&nbsp; Opções - Cadastro</td></tr>
		<tr>
			<td height="40" valign="bottom">
			
				<table width="100%"  border="0" cellspacing="0" cellpadding="0">
					<form name="BuscaForm" action="opcoes_l.php" method="post">
					<tr>
						<td>&nbsp;<a href='opcoes_l.php'>Listar Opções</a></td>
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
		<form method="post" action="opcoes_c.php" enctype="multipart/form-data" name="frm" >
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
							<select id="obSituacao" name="obSituacao" style="width:250px;" disabled>
								<option value="">Selecione uma situação...</option>
							</select>
							<?php
								}
							?>
							
						</td>
					</tr>
					<tr>
						<td align="right">
							<?php
								if ( ($id_caso != "") && ($id_caso != 0) ) {
							?>
							<strong>* Situação Destino</strong>
						</td>
						<td>
							<select id="obSituacaoDestino" name="obSituacaoDestino" style="width:250px;">
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
							<strong>* Situação Destino</strong>
						</td>
						<td>
							<select id="obSituacaoDestino" name="obSituacaoDestino" style="width:220px;" disabled>
								<option value="">Selecione uma situação...</option>
							</select>
							<?php
								}
							?>
							
						</td>
					</tr>
					<tr>
						<td align="right"><strong>* Tipo</strong></td>
						<td align="left">
							<select name="obTipo">
								<option value="">Selecione...</option>
								<option value="B">Bom</option>
								<option value="M">Médio</option>
								<option value="R">Ruim</option>
							</select>
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
					<tr>
						<td align="right" valign="top"><strong>Aviso</strong></td>
						<td align="left"><textarea name="TaAviso" id="TaAviso" style="width:300px; height:100px;"></textarea></td>
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
											
											for ($i=-1; $i<=1; $i=$i+0.5) {
											
												$varSelected = "" ;
												
												if ($i==0) {
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
			<td align="right">
				<input type="button" onClick="checkrequired('frm');" value="Cadastrar">&nbsp; 
				<input type="button" value="Cancelar" onClick="location.replace('opcoes_l.php');">
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