<?php
/* Voltando a página de login, os dados da sessão são destruidos */
session_start();
session_unset();
session_destroy();
?>

<html>
<head>
<title>Sistema Administrativo Ronda Policial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<link href="include/style.css" rel="stylesheet" type="text/css">

<script language="javascript" src="include/commonScripts.js"></SCRIPT>

</head>

<body onLoad="LoginForm.txtLogin.focus();">
<table class="texto" width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center" valign="middle">
		
			<form action="verifica_login.php" method="post" name="LoginForm">
			<table class="texto" width="170"  border="0" cellspacing="5" cellpadding="0">
				<tr>
					<td colspan="2" height="170" valign="middle" align="center">
						<img src="imagens/logo.png" />
					</td>
				</tr>
				<tr><td colspan="2" bgcolor="#FFFFFF" height="2"><img src="imagens/spacer.gif" height="2"></td></tr>
				<tr>
					<td align="right"><strong>Login</strong></td>
					<td align="left"><input type="text" name="txtLogin" size="10"></td>
				</tr>
				<tr>
					<td align="right"><strong>Senha</strong></td>
					<td align="left"><input type="password" name="txtSenha" size="10"></td>
				</tr>
				<tr><td colspan="2" bgcolor="#FFFFFF" height="2"><img src="imagens/spacer.gif" height="2"></td></tr>
				<tr>
					<td align="center" colspan="2">
						<input type="submit" value="OK" style="width:80px;">
					</td>
				</tr>
			</table>
	  </form>

	</td>
  </tr>
</table>

</body>
</html>
