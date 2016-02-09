<?php
Header('Cache-Control: no-cache');
Header('Pragma: no-cache');

session_start();

if ( !isset( $_SESSION['administrador'] ) ) {
	header( "Location: login.php" ) ; 
}

include( "classes/cls_connect.php" ) ;
include( "classes/cls_arquivo.php" ) ;
include( "classes/cls_indicadores.php"  ) ;

?>


<html>
<head>
<title>Sistema Administrativo Ronda Policial - Indicadores</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<link href="include/style.css" rel="stylesheet" type="text/css">

</head>

<body>

<?php include( "include/menu.php"  ) ; ?>

<div id="divConteudo">
	<table class="texto" width="100%"  border="0" cellspacing="5" cellpadding="0">
		<tr><td height="40" valign="middle" class="titulo" bgcolor='#333333'>&nbsp; Indicadores - Listagem</td></tr>
	  <tr>
			<td height="40" valign="bottom">
			
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<form name="BuscaForm" action="indicadores_l.php" method="post">
					<tr>
						<td>&nbsp;<a href='indicadores_c.php'>Cadastrar Novo Indicador</a></td>
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
	  <tr><td height="30" valign="bottom"> &nbsp;<strong>Indicador</strong></td></tr>
	  <tr><td bgcolor='#FFFFFF' height='2'><img src='imagens/spacer.gif' height='2'></td></tr>
		
		<?php
			// Instanciando objeto para pesquisa
			$obj_indicadores = new cls_indicadores ;
			
			// Caso tenha sido feita alguma busca
			if ( isset( $_POST['txtBusca'] ) ) {
				$busca = $_POST['txtBusca'];
				$where = "WHERE ( ( nome LIKE '%" . $busca . "%' ) OR ( descricao LIKE '%" . $busca . "%' ) )" ;	
				$q = $obj_indicadores->listar_reg( $where ) ;
			}
			else {
				$q = $obj_indicadores->listar_para_combo() ;
			}
			
			// Caso exista algum indicador cadastrado
			if ( mysql_num_rows( $q ) ) {
				while( $result = mysql_fetch_array( $q ) ) {
					echo "<tr><td>&nbsp<a href='indicadores_v.php?id=" . $result['id_indicador'] . "'>" . $result['nome'] . "</a></td></tr>" ;
					echo "<tr><td bgcolor='#FFFFFF' height='1'><img src='imagens/spacer.gif' height='1'></td></tr>" ;
				}
			}
			//Caso não exista nenhum indicador cadastrado
			else {
				echo "<tr><td height='20' valign='middle' align='center'>Nenhum Indicador cadastrado !</td></tr>";
			}
			
			unset($obj_indicadores) ;
		?>
	</table>
</div>

</body>
</html>