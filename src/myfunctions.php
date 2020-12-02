<?php

function GET($fieldname, &$flag){
	$v = $_GET [$fieldname];
	$v = trim ( $v );
	if ($v == "") 
	  { $flag = true ; echo "<br><br>$fieldname is empty." ; return  ;} ;
	return $v;
}
?>