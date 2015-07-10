<?php 
//Conexion a base de datos con mysqli
Class Connect{
	public static function conx(){
		$db = mysqli_connect("localhost","root","clave","base_de_datos");
		if (!$db) {
			die("Mysqli dice: ".mysqli_connect_error());
			exit();
		}
		return $db;
	}
} 
//Clases Extras
Class Extras{
	//Script de la base de datos
	public static function Scrip_db($sql){
		$res = mysqli_query(Connect::conx(),$sql);
		return $res;
	}
	//Encriptaion de la contraseña o informacion
	public static function Encr($pass){
		$pw = md5($pass);
		$pw = sha1($pw);
		$pw = crypt($pass,gi);
		return $pw;
	}
	//Crear Imagen thumbnail o avatar
	public static function Thumb_avatar(){
		$num_aleatorio = mt_rand(0,99999999999);//Numeracion aleatoria
			$temp_foto = $_FILES['foto']['tmp_name'];//Nombre temporal para el servidor
			$nom_foto = $_FILES['foto']['name']; //Nombre original de la imagen 
			$nom_foto = trim($_FILES['foto']['name']);//nombre de la imagen quitando espacios u otro tipo de caracteres
			$nom_foto = str_replace(" ","",$nom_foto);//Esto remplaza los espacios en blanco y los unira
			$nom_foto = $num_aleatorio.'_'.$nom_foto;//Union de numero aleatorio con foto 
			$nom_foto = "img/". $nom_foto;//Nombre foto unido con la ruta.. Nombre de la carpeta "img/"
			$type = $_FILES['foto']['type'];;//Para ver que extencion tiene 
			$size = $_FILES['foto']['size'];//El tamaño de la imagen ej: 300kb = 300,000       
			move_uploaded_file($temp_foto, $nom_foto);//Subimos nuetras imagen a la carpeta
			
			//Revisamos la extension de la imagen y la convertimos
			 if($type == "image/jpeg" || $type == "image/jpg") {
		        $img = imagecreatefromjpeg ($nom_foto);
		    } else if ($type == "image/gif") {
		        $img = imagecreatefromgif ($nom_foto);
		    } else if ($type == "image/png") {
		        $img = imagecreatefrompng ($nom_foto);
		    }else{
		    	echo "No puede este formato";
		    	exit();
		    }
		    	$wx = imagesx($img); //Ancho original de la imagen siglas width_x
			    $hy = imagesy($img); //Altura Original de la imagen sigla height_y  
			    $nx = 100; //Tamaño que queremos que tenga la imagen
			    $ny = floor($hy * ($nx / $wx)); //Formula para una altura proporcionar  
			    $nm = imagecreatetruecolor($nx, $ny); //Funcion crea una imagen negra con el tamaño dado
			  	imagecopyresized($nm, $img, 0,0,0,0,$nx,$ny,$wx,$hy); //Funcion larga hay que explicar mucho simplemente mande asi 
			    imagejpeg($nm, $nom_foto);//Exporta la imagen a la carpeta

	}
}
//Esto es un ejemplo de la clase trabajo donde estaran nuestras funciones
Class Trabajo{
	//Ejmplo para insertar
	private $name = array();
	public function insertar_info($user,$pass){
		$pw = Extras::Encr($pass); //Nuestro password fue enviado a encriptar a la funcion de arriba
		$sql = Extras::Script_db("call nombre_de_nuestro_store_procedure");//Esto enviara a nuestro funcion estatica una llamada de store procedure
		$sql_2 = Extras::Script_db("SELECT*FROM name_dbase");//Un query normal para base de datos

		//Aqui llamaremos todos los archivos de la db
		while ($res = mysqli_fetch_array($sql)) {
			$this -> name[] = $res; // se vaya almacenando en un array
		}
		return $this -> name; // Enviamos los datos.
	}
}

/*-------------------------------------------------
	FUNCIONES Y LLAMADAS A LAS BASE DE DATOS MYSQL
---------------------------------------------------*/

//Como llamar un store procedure
//$res = Connect::Script_db("call listar_adultos"); 				--> llamamos y mandamos el script a nuestra funcino statica
//$res = Connect::Script_db("call introducir_persona('Mario',19)"); --> Asi le mandamos parametros

/*--------------------------------------
Como crear un procedimiento en mysql
----------------------------------------

DELIMITER //							--> Cabeza
CREATE PROCEDURE listar_ninos()			--> Nombre de nuestro procedimiento
BEGIN 									--> Inicio del query
SELECT * FROM nino;						--> Script que se realizara
END;									--> Final del query

--------------------------------------------------
Ejemplo de condicion con un procedimiento en mysql
--------------------------------------------------
DELIMITER //
CREATE PROCEDURE introducir_persona
(IN nombre VARCHAR(100), IN edad INT(15))	--> Estos son los parametros que va a recibir "IN"(entrada) "nombre"(variable) y el tipo del formato
BEGIN
IF edad < 18 THEN							--> En mysql no se usan los parentesis para la condicion		
INSERT INTO nino VALUES(NULL,nombre,edad);
ELSE
INSERT INTO adulto VALUES(NULL,nombre,edad);
END IF;										--> Se finaliza con END IF
END;
*/
?>