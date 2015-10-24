<?php
	require_once "feistel2.php";
	$e=null;
	$o=null;
	$errorCifrar="";
	$errorDescifrar="";
	if($_POST['cifrar']){
		$cipher = new Feistel2();
		$texto = $_POST['txtTexto'];
		$longitud = strlen($texto);
		if($longitud<16){
			for($pad=0;$pad<(16-$longitud);$pad++){		//Forzar a una longitud de 16
				$texto.=chr(9);
			}
		}
		$clave = $_POST['txtClave'];
		$longitud = strlen($_POST['txtClave']);
		if($longitud<=8){
			for($pad=0;$pad<(8-$longitud);$pad++){		//Forzar a una longitud de 16
				$clave.=chr(9);
			}
		}
		if($longitud>8){
			$clave = substr($_POST['txtClave'], 0,8);
		}
		if($errorCifrar=="")
			$e = $cipher->encriptar($texto,$clave,2);
	}
	if($_POST['descifrar']){
		$cipher = new Feistel2();
		$longitud = strlen($clave);
		if($longitud!=8){
			$errorDescifrar = "La clave debe ser de 8 caracteres";
		}
		if($errorDescifrar=="")
			$o = $cipher->desencriptar($texto,$clave,8);	//8 rondas definidas
		$o[0]=str_replace(chr(9),'', $o[0]);		//Elimina los pads
	}
?>
<html>
	<head>
		<title>Cifrado de Feistel</title>
	</head>
	<body>
		<h1>Cifrado de Feistel</h1>
		<h2>Encriptar</h2>
		<form method="post">
			<textarea name="txtTexto" rows="10" cols="100"><?php
				if($o!=null){
					 echo $o[0];
				}
			?></textarea><br/>
			<label>Clave:</label><input name="txtClave" value="<?php echo $o[1]?>"/><br/>
			<input type="submit" name="cifrar" value="cifrar"/>
		</form>
		<?php
			if($errorCifrar!=""){
				echo '<div><p>'.$errorCifrar.'</p></div>';
			}
		?>
		<h2>Desencriptar</h2>
		<form method="post">
			<textarea cols="100" rows="10" name="txtTexto"><?php
				if($e!=null){
					 echo $e[0];
				}
			?></textarea><br/>
			<label>Clave:</label><input name="txtClave" value="<?php echo $e[1];?>"/><br/>
			<input type="submit" value="descifrar" name="descifrar"/>
		</form>
		<?php
			if($errorDescifrar!=""){
				echo '<div><p>'.$errorDescifrar.'</p></div>';
			}
		?>
	</body>
</html>