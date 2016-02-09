<?php
Header('Cache-Control: no-cache');
Header('Pragma: no-cache');

session_start();

if ( !isset( $_SESSION['administrador'] ) ) {
	header( "Location: login.php" ) ; 
}

include( "classes/cls_connect.php" ) ;
include( "classes/cls_arquivo.php" ) ;
include( "classes/cls_dicas.php"  ) ;
include( "classes/cls_casos.php"  ) ;
include( "classes/cls_situacoes.php"  ) ;

$id_caso = "" ;
$id_situacao = "" ;

if (isset($_GET['Caso'])) {
	$id_caso = $_GET['Caso'] ;
}

if ( $id_caso == "" ) {
	$id_caso = 0 ;
}

if (isset($_GET['Situacao'])) {
	$id_situacao = $_GET['Situacao'] ;
}

if ( $id_situacao == "" ) {
	$id_situacao = 0 ;
}
?>


<html>
<head>
<title>Sistema Administrativo Ronda Policial - Dicas</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<link href="include/style.css" rel="stylesheet" type="text/css">

<script language="javascript" type="text/javascript">
<!--
	function RecarregaOpcoes(op,valor) {
		if (op == "Caso") {
			window.location = "dicas_l.php?Caso=" + valor ;
		}
		else if (op == "Situacao") {
			window.location = "dicas_l.php?Caso=<?php echo $id_caso ; ?>&Situacao=" + valor ;
		}
	}
//-->
</script>

</head>

<body>

<?php include( "include/menu.php"  ) ; ?>

<div id="divConteudo">
	<table class="texto" width="100%"  border="0" cellspacing="5" cellpadding="0">
		<tr><td height="40" valign="middle" class="titulo" bgcolor='#333333'>&nbsp; Dicas - Listagem</td></tr>
	  <tr>
			<td height="40" valign="bottom">
			
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<form name="BuscaForm" action="dicas_l.php?Situacao=<?php $id_situacao ; ?>" method="post">
					<tr>
						<td>&nbsp;<a href='dicas_c.php'>Cadastrar Nova Dica</a></td>
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
						<td>
							<?php
								if ( ($id_caso != "") && ($id_caso != 0) ) {
							?>
							<strong>Situação</strong><br>
							<select id="Situacao" name="Situacao" style="width:220px;" onChange="RecarregaOpcoes('Situacao',this.value);">
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
							Situação<br>
							<select id="obSituacao" name="obSituacao" style="width:220px;" disabled onChange="RecarregaOpcoes('Situacao',this.value);">
								<option value="">Selecione uma situação...</option>
							</select>
							<?php
								}
							?>
							
						</td>
					</tr>
				</table>
				
			</td>
		</tr>
		<tr><td height="30" valign="bottom"> &nbsp;<strong>Dica</strong></td></tr>
		<tr><td bgcolor='#FFFFFF' height='2'><img src='imagens/spacer.gif' height='2'></td></tr>
		
		<?php
			// Instanciando objeto para pesquisa
			$obj_dicas = new cls_dicas ;
			
			// Caso tenha sido feita alguma busca
			if ( isset( $_POST['txtBusca'] ) ) {
				$busca = $_POST['txtBusca'];
				$where = "WHERE id_situacao=" . $id_situacao . " AND ( ( titulo LIKE '%" . $busca . "%' ) OR ( texto LIKE '%" . $busca . "%' ) )" ;	
				$q = $obj_dicas->listar_reg( $where ) ;
			}
			else {
				$where = "WHERE (id_situacao=" . $id_situacao . ")" ;
				$q = $obj_dicas->listar_reg( $where ) ;
			}
			
			// Caso exista algum opcao cadastrado
			if ( mysql_num_rows( $q ) ) {
				while( $result = mysql_fetch_array( $q ) ) {
					echo "<tr><td>&nbsp<a href='dicas_v.php?id=" . $result['id_dica'] . "'>" . $result['titulo'] . "</a></td></tr>" ;
					echo "<tr><td bgcolor='#FFFFFF' height='1'><img src='imagens/spacer.gif' height='1'></td></tr>" ;
				}
			}
			//Caso não exista nenhuma opcao cadastrada
			elseif ($id_situacao != 0) {
				echo "<tr><td height='20' valign='middle' align='center'>Nenhuma Dica cadastrada para esta Situação !</td></tr>";
			}
			else {
				echo "<tr><td height='20' valign='middle' align='center'>Selecione um Caso e uma Situação para listar as Dicas !</td></tr>";
			}
			
			unset($obj_dicas) ;
		?>
	</table>
</div>

</body>
</html>