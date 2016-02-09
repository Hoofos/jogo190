<?php
Header('Cache-Control: no-cache');
Header('Pragma: no-cache');

session_start();

if ( !isset( $_SESSION['administrador'] ) ) {
	header( "Location: login.php" ) ; 
}

include( "classes/cls_connect.php" ) ;
include( "classes/cls_arquivo.php" ) ;
include( "classes/cls_boxes.php"  ) ;
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
<title>Sistema Administrativo Ronda Policial - Boxes</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<link href="include/style.css" rel="stylesheet" type="text/css">

<script language="javascript" type="text/javascript">
<!--
	function RecarregaOpcoes(op,valor) {
		if (op == "Caso") {
			window.location = "box_v.php?Caso=" + valor ;
		}
	}
//-->
</script>

</head>

<body>

<?php include( "include/menu.php"  ) ; ?>

<div id="divConteudo">
	<table class="texto" width="100%"  border="0" cellspacing="5" cellpadding="0">
		<tr><td height="40" valign="middle" class="titulo" bgcolor='#333333'>&nbsp; Boxes - Listagem</td></tr>
	  <tr>
			<td height="40" valign="bottom">
			
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<form name="BuscaForm" action="box_l.php" method="post">
					<tr>
						<td>&nbsp;<a href='box_c.php'>Cadastrar Novo Box</a></td>
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
			
				<table class="texto" width="100%" border="0" cellspacing="3" cellpadding="0">
					<tr>
						<td>
							<strong>Caso</strong><br>
							<select id="Caso" name="Caso" style="width:220px;" onChange="RecarregaOpcoes('Caso',this.value);">
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
				</table>
				
			</td>
		</tr>
	</table>
</div>

</body>
</html>