<?php
session_start();
// Inclui as defini��es das vari�veis de ambiente.
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
		// Caso aconte�a qualquer erro o usu�rio � direcionado para a p�gina de login.
		$_SESSION['administrador'] = false ;
		?><script>location.replace( 'login.php' ) ;</script><?php
	}
}
?>