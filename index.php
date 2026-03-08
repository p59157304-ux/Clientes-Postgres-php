<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Gestión de Clientes</title>
    <link rel="stylesheet" href="misestilos.css">
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <h2>Menú Principal</h2>
            <p>Gestión de Clientes</p>
        </div>
        <nav>
            <ul class="sidebar-nav">
                <li>
                    <a href="index.php" class="active">
                        <i class="fas fa-home"></i>
                        Inicio
                    </a>
                </li>
                <li>
                    <a href="MostrarClientes.php">
                        <i class="fas fa-list"></i>
                        Ver clientes
                    </a>
                </li>
                <li>
                    <a href="AdicionarClientes.php">
                        <i class="fas fa-user-plus"></i>
                        Adicionar Cliente
                    </a>
                </li>
                <li>
                    <a href="ModificarClientes.php">
                        <i class="fas fa-edit"></i>
                        Modificar Cliente
                    </a>
                </li>
                <li>
                    <a href="EliminarClientes.php">
                        <i class="fas fa-trash-alt"></i>
                        Eliminar Cliente
                    </a>
                </li>
                <li>
                    <a href="MostrarUsuario.php">
                        <i class="fas fa-list"></i>
                        Ver Usuarios
                    </a>
                </li>
                <li>
                    <a href="AdicionarUsuario.php">
                        <i class="fas fa-user-plus"></i>
                        Adicionar Usuario
                    </a>
                </li>
                <li>
                    <a href="ModificarUsuario.php">
                        <i class="fas fa-edit"></i>
                        Modificar Usuario
                    </a>
                </li>
                <li>
                    <a href="EliminarUsuario.php">
                        <i class="fas fa-trash-alt"></i>
                        Eliminar Usuario
                    </a>
                </li>
                <a href="login.php? logout=1" class="boton_salir"> Cerrar Sesion </a> 
                
            </ul>
        </nav>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="main-content">
        <!-- HEADER -->
        <div class="header">
            <div>
                <h1><i class="fas fa-graduation-cap"></i> Dashboard</h1>
            </div>
            <div class="header-info">
                <p><strong>Bienvenido al Sistema</strong></p>
                <p>Gestión Integral de Clientes</p>
            </div>
        </div>

        <!-- STATS CARDS -->
        <div class="stats-container">
            <div class="stat-card">
                <i class="fas fa-users"></i>
                <h3>Total de Clientes</h3>
                <p>150</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-check-circle"></i>
                <h3>Activos</h3>
                <p>145</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-exclamation-circle"></i>
                <h3>Por Revisar</h3>
                <p>5</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-calendar"></i>
                <h3>Actualizados Hoy</h3>
                <p>12</p>
            </div>
        </div>

        <!-- MAIN CARDS -->
        <h2 class="cards-title">Acciones Rápidas</h2>
        <div class="cards-container">
            
            <a href="MostrarClientes.php" class="card">
                <div class="card-icon">
                    <i class="fas fa-list"></i>
                </div>
                <h3 class="card-title">Ver Clientes</h3>
                <p class="card-description">Visualiza la lista completa de todos los clientes registrados en el sistema.</p>
                <button class="card-button">Ir</button>
            </a>

            <a href="AdicionarClientes.php" class="card">
                <div class="card-icon">
                    <i class="fas fa-user-plus"></i>
                </div>
                <h3 class="card-title">Adicionar Cliente</h3>
                <p class="card-description">Registra un nuevo cliente en el sistema con todos sus datos completos.</p>
                <button class="card-button">Ir</button>
            </a>

            <a href="ModificarClientes.php" class="card">
                <div class="card-icon">
                    <i class="fas fa-edit"></i>
                </div>
                <h3 class="card-title">Modificar Cliente</h3>
                <p class="card-description">Actualiza la información de un cliente existente en el sistema.</p>
                <button class="card-button">Ir</button>
            </a>

            <a href="EliminarClientes.php" class="card">
                <div class="card-icon">
                    <i class="fas fa-trash-alt"></i>
                </div>
                <h3 class="card-title">Eliminar Cliente</h3>
                <p class="card-description">Elimina los registros de cliente que ya no están en el sistema.</p>
                <button class="card-button">Ir</button>
            </a>
        </div>

        <!-- FOOTER -->
        <div class="footer">
            <p>&copy; 2026 Sistema de Gestión de Clientes. Todos los derechos reservados.</p>
        </div>
    </main>
</body>
</html>