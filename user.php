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
    <div class="container">
        <h1 class="mt-5 mb-4">IMDB</h1>
        <div class="row">
            <?php
            session_start();

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

            // Preparar la consulta SQL para seleccionar todas las películas
            $sql = "SELECT p.nombre as nombre,p.genero as genero,p.director as director,
            p.duracion as duracion, p.sinopsis as sinopsis, pu.puntuacion as puntuacion,p.image as image,
            p.idPelicula as idPelicula 
            FROM imdb.peliculas as p inner join puntuacion_peliculas as pu on p.idPelicula=pu.idPelicula;
            ";
            $stmt = $conn->prepare($sql);

            // Ejecutar la consulta
            $stmt->execute();

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
                        <div class="puntuacion-recuadro">
                            Mi puntuación: <span id="puntuacion_seleccionada"><?php echo $row['puntuacion']; ?></span>
                        </div>
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