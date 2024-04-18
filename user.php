<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="assets/css/user.css">
</head>
<body>
    <button onclick="window.location.href='addmovie.php'">Go to Add Movie</button>
    <button onclick="window.location.href='favorite.php'">Favorites movies</button>
    <div class="container">
        <h1 class="mt-5 mb-4">Home</h1>
        <div class="row">
            <?php
            // Incluir el archivo de conexión a la base de datos
            include("conexion.php");

            // Preparar la consulta SQL para seleccionar todas las películas
            $sql = "SELECT * FROM peliculas";
            $stmt = $conn->prepare($sql);

            // Ejecutar la consulta
            $stmt->execute();

            // Obtener y mostrar los datos de las películas
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $row['nombre']; ?></h5>
                            <p class="card-text">Genre: <?php echo $row['genero']; ?></p>
                            <p class="card-text">Director: <?php echo $row['director']; ?></p>
                            <p class="card-text">Duration: <?php echo $row['duracion']; ?> minutes</p>
                            <p class="card-text">Synopsis: <?php echo $row['sinopsis']; ?></p>
                        </div>
                        <img src="assets/img/<?php echo $row['image']; ?>" class="card-img-top img-fluid small-img" alt="Movie Image">
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
