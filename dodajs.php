<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head profile="http://gmpg.org/xfn/11">
	<meta name="generator" content="RS v1.0">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<title>RS</title>
<link rel="index" title="RS Team" href="/">
<meta name="generator" content="RS v1.0">
</head>
<body class="classic-wptouch-bg ">
<form action="dodajs.php?dodaj" method="post" onsubmit="document.forms[0].submit.disabled = 'true'; document.forms[0].submit.value = 'Dodavanje u toku...';">
Adresa: <br /> <input type="text" name="adr" value="http://"> <br />
<input type="submit" value="Dodaj">
</form>
<? if isset($_GET['dodaj']) {
include("core.php");
include("config.php");
@session_start();
connectdb();

$adr = $_POST['adr'];
mysql_query("INSERT INTO fotkice SET adr='" . $adr . "'");?>
<big> Fotkica dodata. ^^ </big>
<br />
<a href="index.php?main">Vrati se na pocetnu.. :)</a>
</body>
</html>
