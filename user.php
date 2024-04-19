<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User</title>
    <link rel="stylesheet" href="assets/css/user.css">
</head>

<body>
    <button onclick="window.location.href='addmovie.php'">Go to Add Movie</button>
    <button onclick="window.location.href='coments.php'">Comments</button>
    <button onclick="window.location.href='pendiente.php'">Pendientes</button>

    <div class="container">
        <h1 class="mt-5 mb-4">IMDB</h1>
        <div class="row">
            <?php
            session_start();
            $exists = false;

            // Incluir el archivo de conexión a la base de datos
            include("conexion.php");



            // Verificar si se ha enviado una puntuación
            if (isset($_POST['puntuacion_peliculas'])) {
                $pelicula_id = $_POST['pelicula_id'];
                $puntuacion = $_POST['puntuacion'];

                $usuario_id = (int)$_SESSION['iduser'];
                // Insertar la puntuación en la base de datos
                $stmt = $conn->prepare("
                INSERT INTO puntuacion_peliculas (idUsuarios, idPelicula, puntuacion) 
                VALUES (?, ?, ?)
                ON DUPLICATE KEY UPDATE puntuacion = VALUES(puntuacion)
                ");
                $stmt->execute([$usuario_id, $pelicula_id, $puntuacion]);
            }

            // Verificar si se ha enviado una solicitud para agregar o quitar de pendientes
            if (isset($_POST['add_to_watchlist']) && isset($_SESSION['iduser'])) {
                // Obtener el ID de usuario de la sesión y el ID de película enviado por el formulario
                $usuario_id = $_SESSION['iduser'];
                $pelicula_id = $_POST['pelicula_id'];

                // Verificar si la película ya está en la lista de pendientes
                $stmt = $conn->prepare("SELECT * FROM pendiente WHERE idUsuario = ? AND idPelicula = ?");
                $stmt->execute([$usuario_id, $pelicula_id]);
                $exists = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($exists) {
                    // Si la película ya está en la lista, la eliminamos
                    $stmt = $conn->prepare("DELETE FROM pendiente WHERE idUsuario = ? AND idPelicula = ?");
                    $stmt->execute([$usuario_id, $pelicula_id]);
                    // Mensaje de éxito o redirección
                    // header("Location: user.php"); // Descomenta esta línea si deseas redireccionar
                    // echo "Película eliminada de la lista de pendientes."; // O muestra un mensaje
                } else {
                    // Si la película no está en la lista, la agregamos
                    $stmt = $conn->prepare("INSERT INTO pendiente (idUsuario, idPelicula) VALUES (?, ?)");
                    $stmt->execute([$usuario_id, $pelicula_id]);
                    // Mensaje de éxito o redirección
                    // header("Location: user.php"); // Descomenta esta línea si deseas redireccionar
                    // echo "Película añadida a la lista de pendientes."; // O muestra un mensaje
                }
            }

            // Preparar la consulta SQL para seleccionar todas las películas con la puntuación del usuario logeado
            $sql = "SELECT 
            p.idPelicula,
            p.nombre,
            p.director,
            p.duracion,
            p.genero,
            p.sinopsis,
            p.image,            
            pp.puntuacion,
            CASE 
                WHEN pp.idPelicula IS NOT NULL THEN 'Puntuada'
                ELSE 'No Puntuada'
            END AS estado_puntuacion,
            CASE 
                WHEN pe.idPelicula IS NOT NULL THEN 'Pendiente'
                ELSE 'No Pendiente'
            END AS estado_pendiente
        FROM 
            peliculas p
        LEFT JOIN 
            puntuacion_peliculas pp ON p.idPelicula = pp.idPelicula AND pp.idUsuarios = ?
        LEFT JOIN 
            pendiente pe ON p.idPelicula = pe.idPelicula AND pe.idUsuario = ?;";

            $stmt = $conn->prepare($sql);
            
            $usuario_id = $_SESSION['iduser'];
           
            $stmt->execute([$usuario_id,$usuario_id]);
          

            // Obtener y mostrar los datos de las películas
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
              
            ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Título: <?php echo $row['nombre']; ?></h5>
                            <p class="card-text">Genero: <?php echo $row['genero']; ?></p>
                            <p class="card-text">Director: <?php echo $row['director']; ?></p>
                            <p class="card-text">Duración: <?php echo $row['duracion']; ?> minutos</p>
                            <p class="card-text">Sinopsis: <?php echo $row['sinopsis']; ?></p>
                        </div>
                        <?php if ($row['puntuacion'] !== null) : ?>
                            <div class="puntuacion-recuadro">
                                Mi puntuación: <span id="puntuacion_seleccionada"><?php echo $row['puntuacion']; ?></span>
                            </div>
                        <?php endif; ?>
                        <img src="assets/img/<?php echo $row['image']; ?>" class="card-img-top img-fluid small-img" alt="Movie Image">
                        <form method="post">
                            <input type="hidden" name="pelicula_id" value="<?php echo $row['idPelicula']; ?>">
                            <label for="puntuacion">Puntuación:</label>
                            <select name="puntuacion" id="puntuacion">
                                <?php
                                $puntuacion_actual = $row["puntuacion"]; // Obtener el valor de puntuación de la base de datos
                                for ($i = 0; $i <= 10; $i++) {
                                    // Verificar si el valor actual es igual al valor de la iteración
                                    $selected = ($i == $puntuacion_actual) ? 'selected' : '';
                                    echo "<option value='$i' $selected>$i</option>";
                                }
                                ?>
                            </select>
                            <button type="submit" name="puntuacion_peliculas">Puntuar</button>
                        </form>
                        <form method="post">
                            <input type="hidden" name="pelicula_id" value="<?php echo $row['idPelicula']; ?>">
                            <button type="submit" name="add_to_watchlist">
                                <?php
                                // Mostrar el texto del botón según si la película está en la lista de pendientes o no
                                $watchlist_btn_text = $row['estado_pendiente']=="Pendiente" ? "Quitar de Pendientes" : "Añadir a Pendientes";
                                echo $watchlist_btn_text;
                                ?>
                            </button>
                        </form>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/user.js"></script>
</body>

</html>