<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Películas Pendientes</title>
    <link rel="stylesheet" href="assets/css/pendiente.css">
    <button onclick="window.location.href='user.php'">Pendientes</button>

    <style>
        .movie-img {
            width: 150px; /* Ajusta el tamaño de la imagen según tu preferencia */
        }
    </style>
</head>
<body>
    <h1>Películas Pendientes</h1>

    <div class="container">
        <div class="row">
            <?php
            session_start();
            include("conexion.php");

            // Verificar si el usuario está logeado
            if (!isset($_SESSION['iduser'])) {
                header("Location: login.php"); // Redireccionar al usuario a la página de inicio de sesión si no está logeado
                exit();
            }

            // Obtener el ID del usuario actual
            $usuario_id = $_SESSION['iduser'];

            // Obtener las películas pendientes del usuario actual
            $sql = "SELECT p.nombre AS nombre, p.image AS image
                    FROM pendiente AS pe
                    INNER JOIN peliculas AS p ON pe.idPelicula = p.idPelicula
                    WHERE pe.idUsuario = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$usuario_id]);
            $pendientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Mostrar las películas pendientes
            foreach ($pendientes as $pendiente) {
                echo '<div class="col-md-3 mb-4">';
                echo '<div class="card">';
                echo '<h5 class="card-title">' . $pendiente['nombre'] . '</h5>';
                echo '<div class="card-body">';
                echo '<img src="assets/img/' . $pendiente['image'] . '" class="card-img-top movie-img" alt="' . $pendiente['nombre'] . '">';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
            ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
