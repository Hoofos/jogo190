<?php
Header('Cache-Control: no-cache');
Header('Pragma: no-cache');

session_start();

if ( !isset( $_SESSION['administrador'] ) ) {
	header( "Location: login.php" ) ; 
}

include( "classes/cls_connect.php" ) ;
include( "classes/cls_arquivo.php" ) ;
include( "classes/cls_situacoes.php"  ) ;
include( "classes/cls_casos.php"  ) ;

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
<title>Sistema Administrativo Ronda Policial - Situações</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<link href="include/style.css" rel="stylesheet" type="text/css">

<script language="javascript" type="text/javascript">
<!--
	function RecarregaOpcoes(op,valor) {
		if (op == "Caso") {
			window.location = "situacoes_l.php?Caso=" + valor ;
		}
		else if (op == "Situacao") {
			window.location = "situacoes_l.php?Caso=<?php echo $id_caso ; ?>&Situacao=" + valor ;
		}
	}
//-->
</script>

</head>

<body>

<?php include( "include/menu.php"  ) ; ?>

<div id="divConteudo">
	<table class="texto" width="100%"  border="0" cellspacing="5" cellpadding="0">
		<tr><td height="40" valign="middle" class="titulo" bgcolor='#333333'>&nbsp; Situações - Listagem</td></tr>
	  <tr>
			<td height="40" valign="bottom">
			
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<form name="BuscaForm" action="situacoes_l.php" method="post">
					<tr>
						<td>&nbsp;<a href='situacoes_c.php'>Cadastrar Nova Situação</a></td>
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
		<tr><td height="30" valign="bottom"> &nbsp;<strong>Situação</strong></td></tr>
		<tr><td bgcolor='#FFFFFF' height='2'><img src='imagens/spacer.gif' height='2'></td></tr>
		
		<?php
			// Instanciando objeto para pesquisa
			$obj_situacoes = new cls_situacoes ;
			
			// Caso tenha sido feita alguma busca
			if ( isset( $_POST['txtBusca'] ) ) {
				$busca = $_POST['txtBusca'];
				$where = "(narrativa LIKE '%" . $busca . "%')" ;	
				$q = $obj_situacoes->listar_reg( $where ) ;
			}
			else {
				$where = "WHERE (id_caso=" . $id_caso . ")" ;
				$q = $obj_situacoes->listar_reg( $where ) ;
			}
			
			// Caso exista alguma situacao cadastrada
			if ( mysql_num_rows( $q ) ) {
				while( $result = mysql_fetch_array( $q ) ) {
					echo "<tr><td>&nbsp<a href='situacoes_v.php?id=" . $result['id_situacao'] . "'>" . $result['identificador'] . "</a></td></tr>" ;
					echo "<tr><td bgcolor='#FFFFFF' height='1'><img src='imagens/spacer.gif' height='1'></td></tr>" ;
				}
			}
			//Caso não exista nenhuma banda cadastrada
			elseif ($id_caso != 0) {
				echo "<tr><td height='20' valign='middle' align='center'>Nenhuma situação cadastrada para este caso !</td></tr>";
			}
			//Caso não exista nenhuma banda cadastrada
			else {
				echo "<tr><td height='20' valign='middle' align='center'>Selecione um caso para listar as situações !</td></tr>";
			}
			
			unset($obj_situacoes) ;
		?>
	</table>
</div>

</body>
</html>