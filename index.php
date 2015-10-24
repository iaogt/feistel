<?php
	require_once "feistel2.php";
	$e=null;
	$o=null;
	$errorCifrar="";
	$errorDescifrar="";
	if($_POST['cifrar']){
		$cipher = new Feistel2();
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
		$e=array();
		$tmptexto = $_POST['txtTexto'];
		if($_FILES['txtFile']){
			if($_FILES['txtFile']['size']>0)
				$tmptexto = file_get_contents($_FILES['txtFile']['tmp_name']);
		}
		$longitud = strlen($tmptexto);
		$cantBloques = ceil($longitud/16);
		for($x=0;$x<$cantBloques;$x++){
			$texto = substr($tmptexto,($x*16),16);
			$longitud = strlen($texto);
			if($longitud<16){
				for($pad=0;$pad<(16-$longitud);$pad++){		//Forzar a una longitud de 16
					$texto.=chr(9);
				}
			}
			if($errorCifrar=="")
				$tmp= $cipher->encriptar($texto,$clave,8);
				$e[0].=$tmp[0];
				$e[1]=$tmp[1];
		}
		if($_POST['chkDescarga']==1){
			header('Content-disposition:attachment;filename=encriptado.txt');
			header('Content-type:text/plain;');
			echo "clave:".$e[1]."\r\n";
			echo $e[0];
			die();
		}
	}
	if($_POST['descifrar']){
		$cipher = new Feistel2();
		$tmptexto = $_POST['txtTexto'];
		if($_FILES['txtFile']){
			if($_FILES['txtFile']['size']>0)
				$tmptexto = file_get_contents($_FILES['txtFile']['tmp_name']);
		}
		$clave = $_POST['txtClave'];  
		$longitud = strlen($clave);
		if($longitud!=8){
			$errorDescifrar = "La clave debe ser de 8 caracteres";
		}
		$o=array();
		if($errorDescifrar==""){
			$longitud = strlen($tmptexto);
			$cantBloques = ceil($longitud/32);
			for($x=0;$x<$cantBloques;$x++){
				$texto = substr($tmptexto,($x*32),32);
				$tmp= $cipher->desencriptar($texto,$clave,8);	//8 rondas definidas
				$o[0].=$tmp[0];
				$o[1]=$tmp[1];
			}
		}
		$o[0]=str_replace(chr(9),'', $o[0]);		//Elimina los pads
		if($_POST['chkDescarga']==2){
			header('Content-disposition:attachment;filename=desencriptado.txt');
			header('Content-type:text/plain;');
			echo "clave:".$o[1]."\r\n";
			echo $o[0];
			die();
		}
	}
?>
<html>
	<head>
		<title>Cifrado de Feistel</title>
	</head>
	<body>
		<h1>Cifrado de Feistel</h1>
		<h2>Encriptar</h2>
		<form method="post" enctype="multipart/form-data">
			<textarea name="txtTexto" rows="10" cols="100"><?php
				if($o!=null){
					 echo $o[0];
				}
			?></textarea><br/>
			<label>O carga un archivo de texto:</label><input type="file" name="txtFile"/><br/>
			<label>Clave:</label><input name="txtClave" value="<?php echo $o[1]?>"/><br/>
			<input type="checkbox" name="chkDescarga" value="1"/><label>Descargar archivo encriptado</label><br/>
			<input type="submit" name="cifrar" value="cifrar"/>
		</form>
		<?php
			if($errorCifrar!=""){
				echo '<div><p>'.$errorCifrar.'</p></div>';
			}
		?>
		<h2>Desencriptar</h2>
		<form method="post" enctype="multipart/form-data">
			<textarea cols="100" rows="10" name="txtTexto"><?php
				if($e!=null){
					 echo $e[0];
				}
			?></textarea><br/>
			<label>O carga un archivo de texto:</label><input type="file" name="txtFile"/><br/>
			<label>Clave:</label><input name="txtClave" value="<?php echo $e[1];?>"/><br/>
			<input type="checkbox" name="chkDescarga" value="2"/><label>Descargar archivo encriptado</label><br/>
			<input type="submit" value="descifrar" name="descifrar"/>
		</form>
		<?php
			if($errorDescifrar!=""){
				echo '<div><p>'.$errorDescifrar.'</p></div>';
			}
		?>
	</body>
</html>