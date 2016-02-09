<?php
Header('Cache-Control: no-cache');
Header('Pragma: no-cache');

session_start();

if( !isset($_SESSION['administrador']) ) {
	header("Location: login.php"); 
}

include("classes/cls_connect.php");
include("classes/cls_arquivo.php");
include("classes/cls_campanhas.php");
include("classes/cls_casos.php");

// Main --------------------------------------------------------------
$obj_campanhas = new cls_campanhas ;

if( isset( $_GET['excluir'] ) || isset( $_POST['Excluir'] ) ) {
	$obj_campanhas->id_campanha = $_SESSION['id_campanha'] ;
	$obj_campanhas->excluir() ;
	
	unset($obj_campanhas) ;
	
	?><script language="javascript"> alert('Caso excluído com sucesso !!!'); 
	document.location.replace("campanhas_l.php"); </script><?php
}
elseif( isset( $_GET['alterar'] ) || isset( $_POST['Alterar'] ) ) {
	$trans = array("'" => "`");

	// Instanciando novo objeto banda.
	$obj_campanhas->id_campanha	    = $_SESSION['id_campanha'] ;
	//$obj_campanhas->titulo      = ( trim( $_POST['obTxtTitulo'      ] ) != '' ) ? strtr($_POST['obTxtTitulo'      ], $trans) : die( condRetErrCampos  . " >>> TITULO"          ) ;
	//$obj_campanhas->descricao   = ( trim( $_POST['obTaDescricao'     ] ) != '' ) ? strtr($_POST['obTaDescricao'     ], $trans) : die( condRetErrCampos  . " >>> DESCRICAO"         ) ;
	$obj_campanhas->variacao_bom    = ( trim( $_POST['variacao_bom'     ] ) != '' ) ? strtr($_POST['variacao_bom'     ], $trans) : '' ;
	$obj_campanhas->variacao_medio  = ( trim( $_POST['variacao_medio'     ] ) != '' ) ? strtr($_POST['variacao_medio'     ], $trans) : '' ;
	$obj_campanhas->variacao_ruim   = ( trim( $_POST['variacao_ruim'     ] ) != '' ) ? strtr($_POST['variacao_ruim'     ], $trans) : '' ;
	$obj_campanhas->termometro      = ( trim( $_POST['termometro'     ] ) != '' ) ? strtr($_POST['termometro'     ], $trans) : '' ;

	// upload.
	//$obj_campanhas->nome_icone_temporario    = ( trim( $_FILES['icone']['name']     ) != '' ) ? $_FILES['icone']['name']     : '' ;
	//$obj_campanhas->icone_caminho_temporario = ( trim( $_FILES['icone']['tmp_name'] ) != '' ) ? $_FILES['icone']['tmp_name'] : '' ;
	
	// Salva na base de dados.
	$obj_campanhas->salvar() ;
	$flag_login = 0 ;

	// Cadastra o nível inicia do caso para cada incampanhador
	$obj_casos = new cls_casos ;
	$obj_connect = new cls_connect ;
	$obj_connect->connect() ;
	
	$q = $obj_casos->listar_tudo() ;
	
	if ( mysql_num_rows( $q ) ) {
	
		// Primeiro apagamos todos os relacionamentos
		$deleteSql = "delete from fr_campanhas_casos where id_campanha=" . $_SESSION['id_campanha'] ;
		$obj_connect->RunSql( $deleteSql );
		
		// Depois preenchemos novamente com os novos valores
		while( $result = mysql_fetch_array( $q ) ) {
			if ( isset($_POST['caso_' . $result['id_caso']]) ) {
				$insertSql = "INSERT INTO fr_campanhas_casos(id_campanha,id_caso) VALUES(" . $_SESSION['id_campanha'] . "," . $result['id_caso'] . ")" ;
				$obj_connect->RunSql( $insertSql );
			}
		}
	}

	unset($obj_connect) ;
	unset($obj_casos) ;
	unset($obj_campanhas) ;
	
	?><script language="javascript"> alert( 'Dados da campanha alterados com sucesso !!!' ) ; 
	document.location.replace( "campanhas_v.php?id=<?php echo $_SESSION['id_campanha'] ; ?>" ); </script><?php
}
else {
	$_SESSION['id_campanha'] = $_GET['id'] ;
	$obj_campanhas->id_campanha  = $_GET['id'] ;
	$aCampanha               = mysql_fetch_assoc( $obj_campanhas->listar_reg() ) ;
	
	unset($obj_campanhas) ;
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
		<tr><td height="40" valign="middle" class="titulo" bgcolor='#333333'>&nbsp; Casos - Visualização</td></tr>
			<tr>
				<td height="40" valign="bottom">
					<table width="100%"  border="0" cellspacing="0" cellpadding="0">
						<form name="BuscaForm" action="campanhas_l.php" method="post">
							<tr>
								<td>
									<a href='#' onClick="frm.action='campanhas_v.php?excluir=true'; frm.submit();">Excluir</a> |
									<a href='#' onClick="frm.action='campanhas_v.php?alterar=true'; frm.submit();">Alterar</a> |
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
		<tr><td bgcolor='#FFFFFF' height='3'><img src='imagens/spacer.gif' height='2'></td></tr>
		<form name="frm" action="campanhas_v.php" enctype="multipart/form-data" method="post">
		<tr>
			<td>

				<table class="texto" width="100%" cellpadding="5" cellspacing="0"  border="0">
					<!--tr>
						<td align="right"><strong>* Título</strong></td>
						<td align="left"><input name="obTxtTitulo" type="text" id="obTxtTitulo" value="<?php echo $aCampanha['titulo'] ; ?>" size="50" maxlength="120"></td>
					</tr>
					<tr>
						<td align="right" valign="top"><strong>* Descrição</strong></td>
						<td align="left"><textarea name="obTaDescricao" style="height:100px; width:300px"><?php echo $aCampanha['descricao'] ; ?></textarea></td> 
					</tr-->
					<tr>
						<td align="right" valign="top">
							<strong>Casos</strong>
						</td>
						<td>
							<?php
								// Instanciando objeto
								$obj_casos = new cls_casos ;
								$q = $obj_casos->listar_tudo() ;
								
								// Caso exista algum caso cadastrado
								if ( mysql_num_rows( $q ) ) {
									while( $result = mysql_fetch_array( $q ) ) {
									
										$obj_connect = new cls_connect ;
										$obj_connect->connect() ;
									
										$selectSql = "SELECT * FROM fr_campanhas_casos WHERE id_caso=" . $result['id_caso'] . " AND id_campanha=" . $_SESSION['id_campanha'] ;
										$Rs = mysql_fetch_row($obj_connect->RunSql( $selectSql ));
										
										$varSelected = "" ;
											
										if ($Rs) {
											$varSelected = "checked" ;
										}
									
										unset($Rs) ;
										unset($obj_connect) ;
									
										echo "<input type=\"checkbox\" value=\"1\" name=\"caso_" . $result['id_caso'] . "\" " . $varSelected . "> " . $result['titulo'] . "<br />" ;
									}
								}
							?>
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
													
													if ( $i == $aCampanha['termometro'] ) {
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
													
													if ($i==$aCampanha['variacao_bom']) {
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
													
													if ($i==$aCampanha['variacao_medio']) {
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
													
													if ($i==$aCampanha['variacao_ruim']) {
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
					<!--tr>
						<td align="right"><strong>* Ícone</strong></td>
						<td align="left"><input type="file" name="icone" maxlength="80" style="width:300px;"></td>
					</tr-->
				</table>

			</td>
		</tr>
		<tr><td bgcolor='#FFFFFF' height='2'><img src='imagens/spacer.gif' height='2'></td></tr>
		<tr>
			<td colspan="2" align="right">
				<!--input type="button" value="Excluir" onClick="frm.action='campanhas_v.php?excluir=true'; frm.submit();">&nbsp;-->
				<input type="button" value="Alterar" onClick="frm.action='campanhas_v.php?alterar=true'; checkrequired('frm');">&nbsp;
				<!--input type="button" name="Voltar" value="Voltar" onClick="javascript:history.back()"-->
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

<?php unset($aCampanha) ; ?>