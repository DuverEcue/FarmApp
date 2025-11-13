<?php
session_start();
require_once '../config/conexion.php';

$action = $_GET['action'] ?? $_POST['action'] ?? '';
$id = $_GET['id'] ?? 0;
$cantidad = $_GET['cantidad'] ?? 1;

switch($action) {
    case 'add':
        agregarAlCarrito($id);
        break;
    case 'update':
        actualizarCantidad($id, $cantidad);
        break;
    case 'remove':
        removerDelCarrito($id);
        break;
    case 'clear':
        limpiarCarrito();
        break;
    case 'crear_pedido':
        crearPedido();
        break;
}

function agregarAlCarrito($id_medicamento) {
    $db = Conexion::connect();
    $query = "SELECT m.*, i.nombre_imagen 
              FROM medicamentos m 
              LEFT JOIN imagenes i ON m.id_medicamento = i.id_medicamento_imagen 
              WHERE m.id_medicamento = ? 
              LIMIT 1";
    $stmt = $db->prepare($query);
    $stmt->bind_param("s", $id_medicamento); // Cambiado a "s" para string
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($result->num_rows > 0) {
        $producto = $result->fetch_assoc();
        
        $item = [
            'id' => $producto['id_medicamento'],
            'nombre' => $producto['nombre_medicamento'],
            'precio' => $producto['precio_medicamento'],
            'imagen' => $producto['nombre_imagen'] ?? 'default.jpg',
            'cantidad' => 1
        ];
        
        if(!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }
        
        $encontrado = false;
        foreach($_SESSION['carrito'] as &$producto_carrito) {
            if($producto_carrito['id'] == $id_medicamento) {
                $producto_carrito['cantidad']++;
                $encontrado = true;
                break;
            }
        }
        
        if(!$encontrado) {
            $_SESSION['carrito'][] = $item;
        }
        
        $_SESSION['mensaje'] = 'Producto agregado al carrito correctamente';
    } else {
        $_SESSION['error'] = 'Producto no encontrado';
    }
    
    header("Location: ../catalogo.php");
    exit;
}

function actualizarCantidad($id_medicamento, $cantidad) {
    if(isset($_SESSION['carrito'])) {
        foreach($_SESSION['carrito'] as &$item) {
            if($item['id'] == $id_medicamento) {
                if($cantidad <= 0) {
                    removerDelCarrito($id_medicamento);
                    return;
                }
                $item['cantidad'] = $cantidad;
                break;
            }
        }
    }
    header("Location: ../carrito.php");
    exit;
}

function removerDelCarrito($id_medicamento) {
    if(isset($_SESSION['carrito'])) {
        foreach($_SESSION['carrito'] as $key => $item) {
            if($item['id'] == $id_medicamento) {
                unset($_SESSION['carrito'][$key]);
                $_SESSION['carrito'] = array_values($_SESSION['carrito']);
                $_SESSION['mensaje'] = 'Producto removido del carrito';
                break;
            }
        }
    }
    header("Location: ../carrito.php");
    exit;
}

function limpiarCarrito() {
    unset($_SESSION['carrito']);
    $_SESSION['mensaje'] = 'Carrito vaciado correctamente';
    header("Location: ../carrito.php");
    exit;
}

function crearPedido() {
    if(!isset($_SESSION['usuario'])) {
        $_SESSION['error'] = 'Debes iniciar sesión para confirmar el pedido';
        header("Location: ../login.php");
        exit;
    }
    
    if(!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
        $_SESSION['error'] = 'El carrito está vacío';
        header("Location: ../carrito.php");
        exit;
    }
    
    $db = Conexion::connect();
    
    // Datos del formulario
    $direccion_entrega = $_POST['direccion_entrega'] ?? '';
    $telefono_contacto = $_POST['telefono_contacto'] ?? '';
    $notas_adicionales = $_POST['notas_adicionales'] ?? '';
    $id_usuario = $_SESSION['usuario']->id_usuario;
    
    // Calcular total
    $total = 0;
    foreach($_SESSION['carrito'] as $item) {
        $total += $item['precio'] * $item['cantidad'];
    }
    
    // Insertar pedido
    $query = "INSERT INTO pedidos (id_usuario_pedido, total_pedido, direccion_entrega, telefono_contacto, notas_adicionales, estado_pedido, fecha_pedido) 
              VALUES (?, ?, ?, ?, ?, 'pendiente', CURDATE())";
    $stmt = $db->prepare($query);
    $stmt->bind_param("sdsss", $id_usuario, $total, $direccion_entrega, $telefono_contacto, $notas_adicionales);
    
    if($stmt->execute()) {
        $id_pedido = $db->insert_id;
        
        // Insertar detalles del pedido - CORREGIDO para varchar
        $query_detalle = "INSERT INTO detalle_pedido (id_pedido_detalle, id_medicamento_detalle, cantidad, precio_detalle) 
                         VALUES (?, ?, ?, ?)";
        $stmt_detalle = $db->prepare($query_detalle);
        
        foreach($_SESSION['carrito'] as $item) {
            // Ya que id_medicamento es varchar(12), usamos "s" para string
            $stmt_detalle->bind_param("isid", $id_pedido, $item['id'], $item['cantidad'], $item['precio']);
            $stmt_detalle->execute();
        }
        
        // Limpiar carrito
        unset($_SESSION['carrito']);
        
        $_SESSION['pedido_exitoso'] = true;
        header("Location: ../pedido_exitoso.php?id=" . $id_pedido);
    } else {
        $_SESSION['error'] = 'Error al crear el pedido: ' . $db->error;
        header("Location: ../confirmar_pedido.php");
    }
    exit;
}

// Redirección por defecto
header("Location: ../index.php");
?>