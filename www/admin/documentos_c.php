<?php
session_start();

if( !isset( $_SESSION['administrador'] ) ) {
	header( "Location: login.php" ) ; 
}

include( "classes/cls_connect.php" ) ;
include( "classes/cls_arquivo.php" ) ;
include( "classes/cls_documentos.php"  ) ;
include( "classes/cls_casos.php"  ) ;

if( isset( $_POST['Incluir'] ) ) {

	$trans = array("'" => "`");
								
	// Instanciando novo objeto documento.
	$obj_documentos 			           = new cls_documentos ;
	$obj_documentos->id_caso      		   = ( trim( $_POST['obCaso'      ] ) != '' ) ? strtr($_POST['obCaso'      ], $trans) : die( condRetErrCampos . " >>> CASO"          ) ;
	$obj_documentos->arquivo       		   = ( trim( $_FILES['arquivo']['name']     ) != '' ) ? $_FILES['arquivo']['name']     : '' ;
	$obj_documentos->descricao      	   = ( trim( $_POST['obTaDescricao'     ] ) != '' ) ? strtr($_POST['obTaDescricao'     ], $trans) : die( condRetErrCampos . " >>> DESCRICAO"         ) ;
	$obj_documentos->url		           = ( trim( $_POST['url'     ] ) != '' ) ? strtr($_POST['url'     ], $trans) : '' ;
	$obj_documentos->titulo		           = ( trim( $_POST['titulo'     ] ) != '' ) ? strtr($_POST['titulo'     ], $trans) : '' ;
	$obj_documentos->titulo_adaptado       = ( trim( $_POST['titulo_adaptado'     ] ) != '' ) ? strtr($_POST['titulo_adaptado'     ], $trans) : '' ;
	$obj_documentos->palavras_chave        = ( trim( $_POST['TaPalavras'     ] ) != '' ) ? strtr($_POST['TaPalavras'     ], $trans) : '' ;

	// upload.
	$obj_documentos->nome_arquivo_temporario    = ( trim( $_FILES['arquivo']['name']     ) != '' ) ? $_FILES['arquivo']['name']     : '' ;
	$obj_documentos->arquivo_caminho_temporario = ( trim( $_FILES['arquivo']['tmp_name'] ) != '' ) ? $_FILES['arquivo']['tmp_name'] : '' ;
	
	// Salva na base de dados.
	$obj_documentos->salvar() ;
	$flag_login = 0 ;

	unset($obj_documentos) ;
	
	?><script language="javascript"> alert( 'Documento cadastrado com sucesso !!!' ) ; 
	document.location.replace( "documentos_l.php" ); </script><?php
}
?>

<html>
<head>
<title>Sistema Administrativo Ronda Policial - Documentos</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<link href="include/style.css" rel="stylesheet" type="text/css">

<script language="javascript" src="include/commonScripts.js"></SCRIPT>

</head>

<body>

<?php include( "include/menu.php"  ) ; ?>

<div id="divConteudo">
	<table class="texto" width="100%"  border="0" cellspacing="5" cellpadding="0">
		<tr><td height="40" valign="middle" class="titulo" bgcolor='#333333'>&nbsp; Documentos - Cadastro</td></tr>
		<tr>
			<td height="40" valign="bottom">
				<table width="100%"  border="0" cellspacing="0" cellpadding="0">
					<form name="BuscaForm" action="documentos_l.php" method="post">
					<tr>
						<td>&nbsp;<a href='dicas_l.php'>Listar Documentos</a></td>
						<td align="right">
							<input type="text" size="15" name="txtBusca"> &nbsp;<a href="#" onCLick="BuscaForm.submit();">Buscar</a>
						</td>
					</tr>
					</form>
				</table>
			</td>
		</tr>
		<tr><td bgcolor='#FFFFFF' height='3'><img src='imagens/spacer.gif' height='2'></td></tr>
		<tr>
			<td>
				<form method="post" action="documentos_c.php" enctype="multipart/form-data" name="frm" >
				<input type="hidden" name="Incluir" value="1">
				<table class="texto" width="100%"  border="0" cellspacing="0" cellpadding="5">
					<tr>
						<td align="right"><strong>* Caso</strong></td>
						<td align="left">
							<select id="obCaso" name="obCaso" style="width:220px;">
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
						<td align="right" valign="top"><strong>* Descrição</strong></td>
						<td align="left"><textarea name="obTaDescricao" id="obTaDescricao" style="width:300px; height:100px;"></textarea></td>
					</tr>
					<tr>
						<td align="right" valign="top"><strong>Palavras-Chave</strong></td>
						<td align="left"><textarea name="TaPalavras" id="TaPalavras" style="width:300px; height:100px;"></textarea></td>
					</tr>
					<tr>
						<td align="right" valign="top"><strong>Título</strong></td>
						<td align="left"><textarea name="titulo" id="titulo" style="width:300px; height:100px;"></textarea></td>
					</tr>
					<tr>
						<td align="right" valign="top"><strong>Título Adaptado</strong></td>
						<td align="left"><input type="text" name="titulo_adaptado" maxlength="500" style="width:300px;"></td>
					</tr>
					<tr>
						<td align="right"><strong>URL</strong></td>
						<td align="left"><input type="text" name="url" maxlength="255" style="width:300px;"></td>
					</tr>
					<tr>
						<td align="right"><strong>Arquivo</strong></td>
						<td align="left"><input type="file" name="arquivo" maxlength="80" style="width:300px;"></td>
					</tr>
				</table>
				</form>
			</td>
		</tr>
		<tr><td bgcolor='#FFFFFF' height='2'><img src='imagens/spacer.gif' height='2'></td></tr>
		<tr>
			<td colspan="2" align="right">
				<input type="button" onClick="checkrequired('frm');" value="Cadastrar">&nbsp; 
				<input type="button" value="Cancelar" onClick="location.replace('dicas_l.php');">
			</td>
		</tr>
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