<?php

function bd_connect()
{
	$r = mysql_connect("127.0.0.1","root","1q2w3e");
	if($r && mysql_select_db("tempeixenarede"))
		return($r);
	return(FALSE);
}

?>