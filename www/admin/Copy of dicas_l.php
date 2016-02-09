<?php
session_start();

if ( !isset( $_SESSION['administrador'] ) ) {
	header( "Location: fotpnr_lgn_sec.php" ) ; 
}

include( "classes/cls_connect.php" ) ;
include( "classes/cls_arquivo.php" ) ;
include( "classes/cls_dicas.php"  ) ;
?>


<html>
<head>
<title>Sistema Administrativo Tem Peixe na Rede - Entrevistas</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<link href="include/style.css" rel="stylesheet" type="text/css">

</head>

<body>
<table class="texto" width="100%"  border="0" cellspacing="5" cellpadding="0">
	<tr><td height="40" valign="middle" class="titulo" bgcolor='#666666'>&nbsp; Entrevistas - Listagem</td></tr>
  <tr>
		<td height="40" valign="bottom">
		
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<form name="BuscaForm" action="entrevistas_l.php" method="post">
				<tr>
					<td>&nbsp;<a href='entrevistas_c.php'>Cadastrar Nova Entrevista</a></td>
					<td align="right">
						<input type="text" size="15" name="txtBusca">&nbsp;<a href="#" onCLick="BuscaForm.submit();">Buscar</a>
					</td>
				</tr>
				</form>
			</table>
						
		</td>
	</tr>
  <tr><td bgcolor='#666666' height='3'><img src='imagens/spacer.gif' height='2'></td></tr>
  <tr><td height="30" valign="bottom"> &nbsp;<strong>Entrevista</strong></td></tr>
  <tr><td bgcolor='#666666' height='2'><img src='imagens/spacer.gif' height='2'></td></tr>
	
	<?php
		// Instanciando objeto para pesquisa
		$obj_entrevistas = new cls_entrevistas ;
		// Caso tenha sido feita alguma busca
		if ( isset( $_POST['txtBusca'] ) ) {
			$busca = $_POST['txtBusca'];
			$where = "WHERE ((et_titulo LIKE '%" . $busca . "%') OR 
							 (et_chamada LIKE '%" . $busca . "%') OR 
							 (et_texto LIKE '%" . $busca . "%') OR 
							 (nome_entrevistado LIKE '%" . $busca . "%') )" ;	
			$q = $obj_entrevistas->listar_reg( $where ) ;
		}
		else {
			$q = $obj_entrevistas->listar_para_combo() ;
		}
		
		// Caso exista alguma banda cadastrada
		if ( mysql_num_rows( $q ) ) {
			while( $result = mysql_fetch_array( $q ) ) {
				echo "<tr><td>&nbsp<a href='entrevistas_v.php?id=" . $result['id_entrevista'] . "'>" . $result['et_titulo'] . "</a></td></tr>" ;
				echo "<tr><td bgcolor='#666666' height='1'><img src='imagens/spacer.gif' height='1'></td></tr>" ;
			}
		}
		//Caso não exista nenhuma banda cadastrada
		else {
			echo "<tr><td height='20' valign='middle' align='center'>" . condRetZeroEntrevistas . "</td></tr>";
		}
		
		unset($obj_entrevistas) ;
	?>
	
	<tr><td bgcolor='#666666' height='2'><img src='imagens/spacer.gif' height='2'></td></tr>
</table>
</body>
</html>