<?php
	class Feistel2 {
		
		protected $rondas;
		protected $arrIteracion;
		
		public function __construct(){
			$this->arrIteracion = array();
		}		
		private static function abinario($texto){
			$binario = "";
			$longitud = strlen($texto);
			for($i=0;$i<$longitud;$i++){
				$letra = substr($texto,$i,1);
				$tmp = decbin(ord($letra));
				$longBin =strlen($tmp); 
				if($longBin<8){
					for($j=0;$j<(8-$longBin);$j++){
						$tmp="0".$tmp;
					}
				}
				$binario.=$tmp;
			}
			return $binario;
		}
		

		
		/**
		 * Operador XOR
		 *
		 * */
		public static function operarXOR($p1,$p2){
			$resultado = "";
			for($x=0;$x<strlen($p1);$x++){
				$bit1 = substr($p1,$x,1);
				$bit2 = substr($p2,$x,1);
				if($bit1 xor $bit2) $tmp="1"; else $tmp="0";
				$resultado=$resultado.$tmp;
			}
			return $resultado;
		}


		/**
		 * Rota la clave de forma circular
		 * */
		public static function rotarClave($clave){
			$resultado="";
			for($j=1;$j<strlen($clave);$j++){
				$resultado.= substr($clave,$j,1);
			}
			$resultado.=substr($clave,0,1);
			return $resultado;
		}		
		
		/**
		 * Rota la clave de forma circular
		 * */
		public static function rotarClave2($clave){
			$resultado="";
			for($j=0;$j<(strlen($clave)-1);$j++){
				$resultado.= substr($clave,$j,1);
			}
			$resultado=substr($clave,(strlen($clave)-1),1).$resultado;
			return $resultado;
		}
		
		/**
		 * itera ronda por ronda
		 * */
		public function iterar($rondas){
			for($i=0;$i<$rondas;$i++){
				$right = $this->arrIteracion[$i][1];		//Parte derecha
				$k = $this->arrIteracion[$i][2];			//Clave perteneciente
				$left = $this->arrIteracion[$i][0];		//Parte izquierda
				$resultado = self::operarXOR($right,$k); 
				$nuevoright = self::operarXOR($left,$resultado);
				$nuevaclave = self::rotarClave($k);
				array_push($this->arrIteracion,array($right,$nuevoright,$nuevaclave));
			}
		}
		
		public function iterarBack($rondas){
			for($i=0;$i<$rondas;$i++){
				$left = $this->arrIteracion[$i][0];
				$right = $this->arrIteracion[$i][1];		//Parte derecha
				$k = $this->arrIteracion[$i][2];			//Clave perteneciente
				$nuevaclave= self::rotarClave2($k);
				$resultado = self::operarXOR($nuevaclave,$left);
				$nuevoizquierda = self::operarXOR($right,$resultado);
				array_push($this->arrIteracion,array($nuevoizquierda,$left,$nuevaclave));
			}
		}
		
		public static function enAscii($binario){
			$longitud = strlen($binario);
			$cantLetras = $longitud/8;
			$resultado="";
			for($i=0;$i<$cantLetras;$i++){
				$byte = substr($binario,($i*8),8);
				$char = chr(bindec($byte));
				$resultado.=$char;
			}
			return $resultado;
		}
		
		
		/**
		 * Encripta  
		 * */
		public function encriptar($texto,$clave,$rondas){ 
			$encriptado="";
			$binario = self::abinario($texto);
			$clave = self::abinario($clave);
			$mitad = strlen($binario)/2;
			$izquierda = substr($binario,0,$mitad);
			$derecha = substr($binario,$mitad);
			if(strlen($clave)!=strlen($derecha)){
				echo "no se puede operar, longitud de texto: ".strlen($derecha).", longitud de clave:".strlen($clave);
			}else{
				array_push($this->arrIteracion,array($izquierda,$derecha,$clave));
				$this->iterar($rondas);
				$arrDatosFinales = $this->arrIteracion[count($this->arrIteracion)-1];//array_pop($this->arrIteracion);
				$encriptado = self::enAscii($arrDatosFinales[0]).self::enAscii($arrDatosFinales[1]);
			}
			return array($encriptado,self::enAscii($arrDatosFinales[2]));
		}
		
		/**
		 * Desencripta
		 * */
		public function desencriptar($texto,$clave,$rondas){
			$desencriptado = "";
			$binario = self::abinario($texto);
			$clave = self::abinario($clave);
			
			$mitad = strlen($binario)/2;
			$izquierda = substr($binario,0,$mitad);
			$derecha = substr($binario,$mitad);
			if(strlen($clave)!=strlen($derecha)){
				echo "no se puede operar, las longitudes de clave no coinciden";
			}else{
				array_push($this->arrIteracion,array($izquierda,$derecha,$clave));
				$this->iterarBack($rondas);
				$arrDatosFinales = $this->arrIteracion[count($this->arrIteracion)-1];
				$encriptado = self::enAscii($arrDatosFinales[0]).self::enAscii($arrDatosFinales[1]);
			}
			return array($encriptado,self::enAscii($arrDatosFinales[2]));
			
		}
	}
?>