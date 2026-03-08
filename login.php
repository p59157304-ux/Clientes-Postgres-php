<?php
session_start();

require_once 'conexionpg.php';

$error = "";
$exito = "";
//Procesar cierre de sesión
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit;
}

//Si el usuario ya esta logueado, redirigir al dashboard
if (isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit;
}

//Procesar formulario de acceso
if(isset($_POST['acceder'])) {
   $nombre_usuario = isset($_POST['nombre_usuario']) ? pg_escape_string($conectar, $_POST['nombre_usuario']) : ' ';
   $contraseña = isset($_POST['contraseña']) ? $_POST['contraseña'] : ' ';

   //Validar que los campos no estén vacíos
   if (empty($nombre_usuario) || empty($contraseña)) {
       $error = "Por favor, complete todos los campos.";
   }else{
      //Buscar el usuario en la base de datos
      $sql = "SELECT id_usuario, nombre_usuario, contraseña, tipo_usuario FROM usuarios WHERE nombre_usuario = '$nombre_usuario'";
      $resultado = pg_query($conectar, $sql);

      if ($resultado && pg_num_rows($resultado) > 0) {
          $usuario = pg_fetch_array($resultado);

          //Verificar la contraseña
          if ($contraseña===$usuario['contraseña']) {
	      //Contraseña correcta - iniciar sesión
              $_SESSION['usuario_id'] = $usuario['id_usuario'];
	      $_SESSION['nombre_usuario'] = $usuario['nombre_usuario'];
	      $_SESSION['tipo_usuario'] = $usuario['tipo_usuario'];

	      header("Location: index.php");
	      exit;
	  } else {
	      //Contraseña incorrecta
	      $error = 'Nombre de usuario o contraseña incorrecta.';
          echo "Nombre de usuario o contraseña incorrecta....";
          }
      } else {
	  //Usuario no encontrado
	$error = "Nombre de usuario o contraseña incorrectos";
    echo "Nombre de usuario o contraseña incorrectos....";
      }
   }
}


?>

<!DOCTYPE html>
<HTML lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso al Sistema</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .contenedor_login {
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
            font-size: 28px;
        }

        .formulario {
            display: flex;
            flex-direction: column;
        }

        .grupo_formulario {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: bold;
            font-size: 14px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 5px rgba(102, 126, 234, 0.1);
        }

        .error {
            color: #d32f2f;
            background-color: #ffebee;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
            font-size: 14px;
            border-left: 4px solid #d32f2f;
        }

        .exito {
            color: #388e3c;
            background-color: #e8f5e9;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
            font-size: 14px;
            border-left: 4px solid #388e3c;
        }

        .boton_acceder {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .boton_acceder:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .boton_acceder:active {
            transform: translateY(0);
        }

        .enlace_ayuda {
            text-align: center;
            margin-top: 20px;
        }

        .enlace_ayuda a {
            color: #667eea;
            text-decoration: none;
            font-size: 14px;
        }

        .enlace_ayuda a:hover {
            text-decoration: underline;
        }

        .info_sistema {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #999;
            font-size: 12px;
        }
    </style>

</head>
<body>

    <div class="contenedor_login">
	<h1>Acceso al Sistema</h1>

	<?php if($error): ?>
	    <div class="error">
		    <?php echo htmlspecialchars($error); ?>
	    </div>
	<?php endif; ?>

	<?php if ($exito): ?>
	    <div class="éxito">
	            <?php echo htmlspecialchars($exito); ?>
	    </div>
    <?php endif; ?>    
	<form method="POST" action="login.php" class="formulario">
	    <input type="hidden" name="acceder" value="1">

	<div class="grupo_formulario">
	    <label for="nombre_usuario">Nombre de usuario:</label>
	    <input 
		type="text"
		id="nombre_usuario"
		name="nombre_usuario"
		placeholder="ingrese su usuario"
		
		autofocus
	    >
	</div>

	<div class="grupo_formulario">
	    <label for="contraseña">Contraseña:</label>
	    <input
		type="password"
		id="contraseña"
		name="contraseña"
		placeholder="Ingrese su contraseña"
		
	    >
	</div>

	<button type="submit" class="boton_acceder">Acceder</button>
     </form>

     <div class="enlace_ayuda">
	 <p>¿Olvidó su contraseña? <a href="#">Recuperar acceso</a></p>
     </div>

     <div class="info_sistema">
	 <p>Sistema de Gestión Educativa UDES</p>
	 <p>© 2026 - Todos los derechos reservados</p>
     </div>
  </div>

</body>
</html>

<?php
pg_close($conectar);
?>