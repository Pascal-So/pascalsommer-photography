<?php

$sql_user = "pascalsommer_ch";
$sql_pwd = "Nosvctxk";
$sql_db = "pascalsommer_ch";

$tableName_participants = "map";
$tableName_helpers = "helpers";

$path_to_connection_start = "connect.php";

function abort(){
	session_unset();
	session_destroy();
	exit();
}

function redirectToConnectionStart(){ //send the user back to the site for generating the AES key
	global $path_to_connection_start;
	session_unset();
	session_destroy();
	header("Location: " . $path_to_connection_start);	
	exit();
}

function aesDecrypt($cyphertext, $key){
	set_include_path(get_include_path().PATH_SEPARATOR .'phpseclib');
	include('Crypt/AES.php');
	$aes=new Crypt_AES(CRYPT_AES_MODE_ECB);
	$aes->setKey(hex2bin($key));
	$suffix = substr(bin2hex($aes->encrypt(hex2bin("10101010101010101010101010101010"))), 32); //need to add this suffix in order for it to decrypt
	$decoded = $aes->decrypt(hex2bin($cyphertext . $suffix))."\n";
	return $decoded;
}


?>