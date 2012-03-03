<?php 
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=\"data.csv\"");
	$data="col1, col2,col3, \n";
	$data .= "data1, data2, data3";
	echo $data; 
?>