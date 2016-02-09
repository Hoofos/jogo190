<?php
session_start();

if( !isset( $_SESSION['administrador'] ) ) {
	header( "Location: login.php" ) ; 
}

include( "classes/cls_connect.php" ) ;
include( "classes/cls_campanhas.php"  ) ;
include( "classes/cls_casos.php"  ) ;

if( isset( $_POST['Incluir'] ) ) {

	$trans = array("'" => "`");
								
	// Instanciando novo objeto banda.
	$obj_campanha 			       = new cls_campanhas ;
	$obj_campanha->id_caso         = $_POST['obCaso'] ;
	$obj_campanha->id_situacao     = $_POST['obSituacao'] ;
	$obj_campanha->titulo          = ( trim( $_POST['obTxtTitulo'      ] ) != '' ) ? strtr($_POST['obTxtTitulo'      ], $trans) : die( condRetErrCampos . " >>> TITULO"          ) ;
	$obj_campanha->texto           = ( trim( $_POST['obTaTexto'     ] ) != '' ) ? strtr($_POST['obTaTexto'     ], $trans) : die( condRetErrCampos . " >>> CHAMADA"         ) ;

	// Salva na base de dados.
	$obj_campanha->salvar() ;
	$flag_login = 0 ;

	// Seleciona o id do caso cadastrado
	$obj_connect = new cls_connect ;
	$maxId = $obj_connect->maxReg( 'id_campanha', 'vr_campanhas' ) ;
	
	// Cadastra o nível inicia do caso para cada incampanhador
	$obj_casos = new cls_casos ;
	
	$q = $obj_casos->listar_tudo() ;
	
	if ( mysql_num_rows( $q ) ) {
	
		// Primeiro apagamos todos os relacionamentos
		$deleteSql = "delete from vr_campanhas_casos where id_campanha=" . $maxId ;
		mysql_query( $insertSql );
		
		// Depois preenchemos novamente com os novos valores
		while( $result = mysql_fetch_array( $q ) ) {
			if ( $_POST['caso_' . $result['id_caso']] == "1" ) {
				$insertSql = "INSERT INTO vr_campanhas_casos(id_campanha,id_caso) VALUES(" . $maxId . "," . $result['id_caso'] . ")" ;
				mysql_query( $insertSql );
			}
		}
	}

	unset($obj_connect) ;
	unset($obj_casos) ;
	unset($obj_campanha) ;
	
	?><script language="javascript"> alert( 'Campanha cadastrada com sucesso !!!' ) ; 
	document.location.replace( "campanhas_l.php" ); </script><?php
}
?>

<html>
<head>
<title>Sistema Administrativo Ronda Policial - Campanhas</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<link href="include/style.css" rel="stylesheet" type="text/css">

<script language="javascript" src="include/commonScripts.js"></SCRIPT>

</head>

<body>

<?php include( "include/menu.php"  ) ; ?>

<div id="divConteudo">
	<table class="texto" width="100%"  border="0" cellspacing="5" cellpadding="0">
		<tr><td height="40" valign="middle" class="titulo" bgcolor='#333333'>&nbsp; Campanhas - Cadastro</td></tr>
		<tr>
			<td height="40" valign="bottom">
				<table width="100%"  border="0" cellspacing="0" cellpadding="0">
					<form name="BuscaForm" action="campanhas_l.php" method="post">
					<tr>
						<td>&nbsp;<a href='campanhas_l.php'>Listar Campanhas</a></td>
						<td align="right">
							<input type="text" size="15" name="txtBusca"> &nbsp;<a href="#" onCLick="BuscaForm.submit();">Buscar</a>
						</td>
					</tr>
					</form>
				</table>
			</td>
		</tr>
		<tr><td bgcolor='#FFFFFF' height='3'><img src='imagens/spacer.gif' height='2'></td></tr>
		<form method="post" action="campanhas_c.php" name="frm" >
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
						<td align="right">
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
										echo "<input type=\"checkbox\" value=\"1\" name=\"caso_" . $result['id_caso'] . "\"> " . $result['titulo'] . "<br />" ;
									}
								}
							?>
						</td>
					</tr>
				</table>

			</td>
		</tr>
		<tr><td bgcolor='#FFFFFF' height='2'><img src='imagens/spacer.gif' height='2'></td></tr>
		<tr>
			<td colspan="2" align="right">
				<input type="button" onClick="checkrequired('frm');" value="Cadastrar">&nbsp; 
				<input type="button" value="Cancelar" onClick="location.replace('campanhas_l.php');">
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