<?php
session_start();

if( !isset( $_SESSION['administrador'] ) ) {
	header( "Location: login.php" ) ; 
}

include( "classes/cls_connect.php" ) ;
include( "classes/cls_arquivo.php" ) ;
include( "classes/cls_casos.php"  ) ;
include( "classes/cls_indicadores.php"  ) ;

if( isset( $_POST['Incluir'] ) ) {

	$trans = array("'" => "`");
								
	// Instanciando novo objeto banda.
	$obj_caso 			       = new cls_casos ;
	$obj_caso->titulo          = ( trim( $_POST['obTxtTitulo'      ] ) != '' ) ? strtr($_POST['obTxtTitulo'      ], $trans) : die( condRetErrCampos . " >>> TITULO"          ) ;
	$obj_caso->descricao       = ( trim( $_POST['obTaDescricao'     ] ) != '' ) ? strtr($_POST['obTaDescricao'     ], $trans) : die( condRetErrCampos . " >>> DESCRIÇÃO"         ) ;
	$obj_caso->resumo	       = ( trim( $_POST['obTaResumo'     ] ) != '' ) ? strtr($_POST['obTaResumo'     ], $trans) : die( condRetErrCampos . " >>> RESUMO"         ) ;
	$obj_caso->variacao_bom    = ( trim( $_POST['variacao_bom'     ] ) != '' ) ? strtr($_POST['variacao_bom'     ], $trans) : '' ;
	$obj_caso->variacao_medio  = ( trim( $_POST['variacao_medio'     ] ) != '' ) ? strtr($_POST['variacao_medio'     ], $trans) : '' ;
	$obj_caso->variacao_ruim   = ( trim( $_POST['variacao_ruim'     ] ) != '' ) ? strtr($_POST['variacao_ruim'     ], $trans) : '' ;
	$obj_caso->termometro      = ( trim( $_POST['termometro'     ] ) != '' ) ? strtr($_POST['termometro'     ], $trans) : '' ;

	// upload.
	$obj_caso->nome_icone_temporario    = ( trim( $_FILES['icone']['name']     ) != '' ) ? $_FILES['icone']['name']     : '' ;
	$obj_caso->icone_caminho_temporario = ( trim( $_FILES['icone']['tmp_name'] ) != '' ) ? $_FILES['icone']['tmp_name'] : '' ;
	
	// Salva na base de dados.
	$obj_caso->salvar() ;
	$flag_login = 0 ;

	// Cadastra o valor máximo dos indicadores para o caso
	$obj_indicadores = new cls_indicadores ;
	
	$q = $obj_indicadores->listar_tudo() ;
	
	if ( mysql_num_rows( $q ) ) {
		while( $result = mysql_fetch_array( $q ) ) {
			$nivel_indicador = $_POST['indicador_' . $result['id_indicador']] ;
			
			$obj_connect = new cls_connect ;
			$maxId = $obj_connect->maxReg( 'id_caso', 'fr_casos' ) ;
			$insertSql = "INSERT INTO fr_indicadores_casos(id_indicador,id_caso,nivel_indicador) VALUES(" . $result['id_indicador'] . "," . $maxId . "," . $nivel_indicador . ")" ;
			$obj_connect->RunSql( $insertSql );
		}
	}

	unset($obj_connect) ;
	unset($obj_indicadores) ;
	unset($obj_caso) ;
	
	?><script language="javascript"> alert( 'Caso cadastrada com sucesso !!!' ) ; 
	document.location.replace( "casos_l.php" ); </script><?php
}
?>

<html>
<head>
<title>Sistema Administrativo Ronda Policial - Casos</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<link href="include/style.css" rel="stylesheet" type="text/css">

<script language="javascript" src="include/commonScripts.js"></SCRIPT>

</head>

<body>

<?php include( "include/menu.php"  ) ; ?>

<div id="divConteudo">
	<table class="texto" width="100%"  border="0" cellspacing="5" cellpadding="0">
		<tr><td height="40" valign="middle" class="titulo" bgcolor='#032867'>&nbsp; Casos - Cadastro</td></tr>
		<tr>
			<td height="40" valign="bottom">
				<table width="100%"  border="0" cellspacing="0" cellpadding="0">
					<form name="BuscaForm" action="casos_l.php" method="post">
					<tr>
						<td>&nbsp;<a href='casos_l.php'>Listar Casos</a></td>
						<td align="right">
							<input type="text" size="15" name="txtBusca"> &nbsp;<a href="#" onCLick="BuscaForm.submit();">Buscar</a>
						</td>
					</tr>
					</form>
				</table>
			</td>
		</tr>
		<tr><td bgcolor='#032867' height='3'><img src='imagens/spacer.gif' height='2'></td></tr>
		<form method="post" action="casos_c.php" enctype="multipart/form-data" name="frm" >
		<input type="hidden" name="Incluir" value="1">
		<tr>
			<td>

				<table class="texto" width="100%"  border="0" cellspacing="0" cellpadding="5">
					<tr>
						<td align="right"><strong>* Título</strong></td>
						<td align="left"><input name="obTxtTitulo" type="text" id="obTxtTitulo" size="50" maxlength="120"></td>
					</tr>
					<tr>
						<td align="right" valign="top"><strong>* Descrição</strong></td>
						<td align="left"><textarea name="obTaDescricao" id="obTaDescricao" style="width:300px; height:100px;"></textarea></td>
					</tr>
					<tr>
						<td align="right" valign="top"><strong>* Resumo</strong></td>
						<td align="left"><textarea name="obTaResumo" id="obTaResumo" style="width:300px; height:50px;"></textarea></td>
					</tr>
					<tr>
						<td align="right" valign="top"><strong>Indicadores Máximos</strong></td>
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
											echo "<select name=\"indicador_" . $result['id_indicador'] . "\" style=\"width:40px;\">" ;
											
											for ($i=$result['nivel_min']; $i<=$result['nivel_max']; $i++) {
												echo "<option value=\"" . $i . "\">" . $i . "</option>" ;
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
									<td>
										<select name="termometro">
											<?php
												for ($i=0; $i<101; $i=$i+10) {
													echo "<option value=\"" . $i . "\">" . $i . "</option>" ;
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
													
													if ($i==0) {
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
													
													if ($i==0) {
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
													
													if ($i==0) {
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
						<td align="right"><strong>* Ícone</strong></td>
						<td align="left"><input type="file" name="icone" maxlength="80" style="width:300px;"></td>
					</tr>
				</table>
				
			</td>
		</tr>
		<tr><td bgcolor='#032867' height='2'><img src='imagens/spacer.gif' height='2'></td></tr>
		<tr>
			<td align="right">
				<input type="button" onClick="checkrequired('frm');" value="Cadastrar">&nbsp; 
				<input type="button" value="Cancelar" onClick="location.replace('casos_l.php');">
			</td>
		</tr>
		</form>
	</table>
	
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
</div>

</body>
</html>