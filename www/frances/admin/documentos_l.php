<?php
Header('Cache-Control: no-cache');
Header('Pragma: no-cache');

session_start();

if ( !isset( $_SESSION['administrador'] ) ) {
	header( "Location: login.php" ) ; 
}

include( "classes/cls_connect.php" ) ;
include( "classes/cls_arquivo.php" ) ;
include( "classes/cls_documentos.php"  ) ;
include( "classes/cls_casos.php"  ) ;

$id_caso = "" ;

if (isset($_GET['Caso'])) {
	$id_caso = $_GET['Caso'] ;
}

if ( $id_caso == "" ) {
	$id_caso = 0 ;
}
?>


<html>
<head>
<title>Sistema Administrativo Ronda Policial - Documentos</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<link href="include/style.css" rel="stylesheet" type="text/css">

<script language="javascript" type="text/javascript">
<!--
	function RecarregaDocumentos(op,valor) {
		if (op == "Caso") {
			window.location = "documentos_l.php?Caso=" + valor ;
		}
		else if (op == "Situacao") {
			window.location = "documentos_l.php?Caso=<?php echo $id_caso ; ?>&Situacao=" + valor ;
		}
	}
//-->
</script>

</head>

<body>

<?php include( "include/menu.php"  ) ; ?>

<div id="divConteudo">
	<table class="texto" width="100%"  border="0" cellspacing="5" cellpadding="0">
		<tr><td height="40" valign="middle" class="titulo" bgcolor='#333333'>&nbsp; Documentos - Listagem</td></tr>
	  <tr>
			<td height="40" valign="bottom">
			
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<form name="BuscaForm" action="documentos_l.php?Caso=<?php $id_caso ; ?>" method="post">
					<tr>
						<td>&nbsp;<a href='documentos_c.php'>Cadastrar Novo Documento</a></td>
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
		<tr>
			<td>
				<strong>Caso</strong><br>
				<select id="Caso" name="Caso" style="width:220px;" onChange="RecarregaDocumentos('Caso',this.value);">
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
		</tr>
		<tr><td height="30" valign="bottom"> &nbsp;<strong>Documentos</strong></td></tr>
		<tr><td bgcolor='#FFFFFF' height='2'><img src='imagens/spacer.gif' height='2'></td></tr>
		
		<?php
			// Instanciando objeto para pesquisa
			$obj_documentos = new cls_documentos ;
			
			// Caso tenha sido feita alguma busca
			if ( isset( $_POST['txtBusca'] ) ) {
				$busca = $_POST['txtBusca'];
				$where = "WHERE id_caso=" . $id_caso . " AND ( ( nome LIKE '%" . $busca . "%' ) OR ( url LIKE '%" . $busca . "%' ) OR ( descricao LIKE '%" . $busca . "%' ) OR ( palavras_chave LIKE '%" . $busca . "%' ) )" ;	
				$q = $obj_documentos->listar_reg( $where ) ;
			}
			else {
				$where = "WHERE (id_caso=" . $id_caso . ")" ;
				$q = $obj_documentos->listar_reg( $where ) ;
			}
			
			// Caso exista algum opcao cadastrado
			if ( mysql_num_rows( $q ) ) {
				while( $result = mysql_fetch_array( $q ) ) {
					if ($result['titulo_adaptado'] != "") {
						$varChamada = $result['titulo_adaptado'] ;
					}
					elseif ($result['url'] != "") {
						$varChamada = $result['url'] ;
					}
					elseif ($result['nome'] != "") {
						$varChamada = $result['nome'] ;
					}
					else {
						$varChamada = "&lt; ver documento &gt;" ;
					}
					
					echo "<tr><td>&nbsp<a href='documentos_v.php?id=" . $result['id_documento'] . "'>" . $varChamada . "</a></td></tr>" ;
					echo "<tr><td bgcolor='#FFFFFF' height='1'><img src='imagens/spacer.gif' height='1'></td></tr>" ;
				}
			}
			//Caso não exista nenhuma opcao cadastrada
			elseif ($id_caso != 0) {
				echo "<tr><td height='20' valign='middle' align='center'>Nenhum Documento cadastrado para este Caso !</td></tr>";
			}
			else {
				echo "<tr><td height='20' valign='middle' align='center'>Selecione um Caso para listar os Documentos !</td></tr>";
			}
			
			unset($obj_documentos) ;
		?>
	</table>
</div>

</body>
</html>