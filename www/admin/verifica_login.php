<?php
session_start();
// Inclui as definições das variáveis de ambiente.
include( "include/environment.php" ) ;

$trans = array("'" => "`");

if( isset( $_POST['txtLogin'] ) ) {
	$login = strtr($_POST['txtLogin'], $trans) ; 
	$senha = strtr($_POST['txtSenha'], $trans) ;

	if( ( $login == ADMIN_LOGIN ) && ( $senha == ADMIN_SENHA ) ) {		
		$_SESSION['administrador'] = true ;
		?><script>location.replace( 'casos_l.php' ) ;</script><?php
	}
	else {		
		// Caso aconteça qualquer erro o usuário é direcionado para a página de login.
		$_SESSION['administrador'] = false ;
		?><script>location.replace( 'login.php' ) ;</script><?php
	}
}
?>