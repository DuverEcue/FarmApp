<?php
session_start();
require_once '../config/conexion.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch($action) {
    case 'registro':
        registrarUsuario();
        break;
    case 'login':
        loginUsuario();
        break;
    case 'logout':
        logoutUsuario();
        break;
}

function registrarUsuario() {
    $db = Conexion::connect();
    
    // Obtener datos del formulario
    $id_usuario = $_POST['id_usuario'] ?? '';
    $nombre = $_POST['nombre'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $genero = $_POST['genero'] ?? '';
    $direccion = $_POST['direccion'] ?? '';
    
    // Validaciones básicas
    if(empty($id_usuario) || empty($nombre) || empty($email) || empty($password)) {
        $_SESSION['error_registro'] = 'Todos los campos son obligatorios';
        header("Location: ../registro.php");
        exit;
    }
    
    // Verificar si el usuario ya existe
    $query = "SELECT * FROM usuarios WHERE id_usuario = ? OR email_usuario = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("ss", $id_usuario, $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($result->num_rows > 0) {
        $_SESSION['error_registro'] = 'El usuario o email ya existe';
        header("Location: ../registro.php");
        exit;
    }
    
    // Hash de contraseña
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    
    // Insertar usuario - USANDO clave_usuario
    $query = "INSERT INTO usuarios (id_usuario, nombre_usuario, email_usuario, clave_usuario, telefono_usuario, direccion_usuario, id_rol_usuario, id_genero_usuario) 
              VALUES (?, ?, ?, ?, ?, ?, 3, ?)";
    $stmt = $db->prepare($query);
    $stmt->bind_param("ssssssi", $id_usuario, $nombre, $email, $password_hash, $telefono, $direccion, $genero);
    
    if($stmt->execute()) {
        $_SESSION['registro'] = 'Usuario registrado exitosamente. Ahora puedes iniciar sesión.';
        header("Location: ../login.php");
    } else {
        $_SESSION['error_registro'] = 'Error al registrar usuario: ' . $db->error;
        header("Location: ../registro.php");
    }
    exit;
}

function loginUsuario() {
    $db = Conexion::connect();
    
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if(empty($email) || empty($password)) {
        $_SESSION['error_login'] = 'Email y contraseña son obligatorios';
        header("Location: ../login.php");
        exit;
    }
    
    $query = "SELECT u.*, r.nombre_rol 
              FROM usuarios u 
              INNER JOIN roles r ON u.id_rol_usuario = r.id_rol 
              WHERE u.email_usuario = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($result->num_rows === 1) {
        $usuario = $result->fetch_object();
        
        // Verificar contraseña - maneja tanto texto plano como hash
        if($usuario->clave_usuario === $password) {
            // Contraseña en texto plano (para usuarios existentes)
            $_SESSION['usuario'] = $usuario;
            $_SESSION['success'] = 'Bienvenido ' . $usuario->nombre_usuario;
            header("Location: ../index.php");
        } elseif (password_verify($password, $usuario->clave_usuario)) {
            // Contraseña hasheada (para nuevos registros)
            $_SESSION['usuario'] = $usuario;
            $_SESSION['success'] = 'Bienvenido ' . $usuario->nombre_usuario;
            header("Location: ../index.php");
        } else {
            $_SESSION['error_login'] = 'Contraseña incorrecta';
            header("Location: ../login.php");
        }
    } else {
        $_SESSION['error_login'] = 'Usuario no encontrado';
        header("Location: ../login.php");
    }
    exit;
}

function logoutUsuario() {
    session_destroy();
    header("Location: ../index.php");
    exit;
}

header("Location: ../index.php");
?>