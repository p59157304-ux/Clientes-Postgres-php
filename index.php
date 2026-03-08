<?php
session_start();
require_once 'conexion.php';

// 1. Verificación de Seguridad: Si no hay sesión, regresa al login
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

// 2. Obtener estadísticas reales de la base de datos
try {
    $sql_total = "SELECT COUNT(*) FROM clientes"; 
    $total_clientes = $conn->query($sql_total)->fetchColumn();

    $total_hoy = 0; 
} catch (PDOException $e) {
    $total_clientes = "Error";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="misestilos.css">
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <aside class="sidebar">
        <div class="sidebar-header">
            <h2>UDES</h2>
            <p>Panel de Gestión</p>
        </div>
        <nav>
            <ul class="sidebar-nav">
                <li><a href="index.php" class="active"><i class="fas fa-home"></i> Inicio</a></li>
                
                <li class="nav-label">Clientes</li>
                <li><a href="MostrarClientes.php"><i class="fas fa-list"></i> Ver clientes</a></li>
                <li><a href="AdicionarClientes.php"><i class="fas fa-user-plus"></i> Adicionar Cliente</a></li>
                
                <li class="nav-label">Usuarios</li>
                <li><a href="MostrarUsuario.php"><i class="fas fa-users-cog"></i> Ver Usuarios</a></li>
                <li><a href="AdicionarUsuario.php"><i class="fas fa-user-shield"></i> Nuevo Usuario</a></li>
                <li><a href="EliminarUsuario.php"><i class="fas fa-user-minus"></i> Eliminar Usuario</a></li>
                <li><a href="ModificarUsuario.php"><i class="fas fa-user-edit"></i> Modificar Usuario</a></li>
                
                <br>
                <li>
                    <a href="login.php?logout=1" class="boton_salir" style="background: #e74c3c; color: white; padding: 10px; border-radius: 5px; text-align: center;">
                        <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                    </a>
                </li>
            </ul>
        </nav>
    </aside>

    <main class="main-content">
        <div class="header">
            <div>
                <h1><i class="fas fa-graduation-cap"></i> Dashboard Educativo</h1>
            </div>
            <div class="header-info">
                <p>Bienvenido, <strong><?php echo htmlspecialchars($_SESSION['nombre_usuario']); ?></strong></p>
                <p>Nivel: <?php echo ($_SESSION['tipo_usuario'] == 1) ? 'Administrador' : 'Personal'; ?></p>
            </div>
        </div>

        <div class="stats-container">
            <div class="stat-card">
                <i class="fas fa-users" style="color: #667eea;"></i>
                <h3>Total de Clientes</h3>
                <p><?php echo $total_clientes; ?></p>
            </div>
            <div class="stat-card">
                <i class="fas fa-check-circle" style="color: #2ecc71;"></i>
                <h3>Estado Sistema</h3>
                <p>Activo</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-database" style="color: #f1c40f;"></i>
                <h3>Servidor</h3>
                <p>SQL Server</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-user-tag" style="color: #e67e22;"></i>
                <h3>Tu Perfil</h3>
                <p>En Línea</p>
            </div>
        </div>

        <h2 class="cards-title">Accesos Directos</h2>
        <div class="cards-container">
            
            <a href="MostrarClientes.php" class="card">
                <div class="card-icon"><i class="fas fa-address-book"></i></div>
                <h3 class="card-title">Listado de Clientes</h3>
                <p class="card-description">Visualiza y busca en la base de datos de la institución.</p>
                <button class="card-button">Entrar</button>
            </a>

            <a href="AdicionarUsuario.php" class="card">
                <div class="card-icon"><i class="fas fa-user-plus"></i></div>
                <h3 class="card-title">Gestionar Accesos</h3>
                <p class="card-description">Registra nuevos usuarios para el personal administrativo.</p>
                <button class="card-button">Entrar</button>
            </a>

            <a href="ModificarUsuario.php" class="card">
                <div class="card-icon"><i class="fas fa-user-edit"></i></div>
                <h3 class="card-title">Editar Perfiles</h3>
                <p class="card-description">Actualiza contraseñas y permisos de los usuarios actuales.</p>
                <button class="card-button">Entrar</button>
            </a>
        </div>

        <div class="footer">
            <p>&copy; 2026 Sistema de Gestión Educativa UDES. El Alto, Bolivia.</p>
        </div>
    </main>
</body>
</html>