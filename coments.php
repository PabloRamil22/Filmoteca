<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comments</title>
    <link rel="stylesheet" href="assets/css/comments.css">
</head>
<body>
    <button onclick="window.location.href='user.php'">Home</button>

    <h1 class="mt-5 mb-4">Comentarios</h1>

    <?php
    // Incluir el archivo de conexión a la base de datos
    include("conexion.php");

    // Consulta para obtener los comentarios existentes
    $sql = "SELECT p.comentario, u.email
    FROM imdb.puntuacion_peliculas p
    JOIN imdb.usuarios u ON p.idUsuarios = u.idUsuarios;";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Mostrar los comentarios existentes
    if ($stmt->rowCount() > 0) {
        echo "<div class='comments-container'>";
        foreach ($comments as $comment) {
            echo "<div class='comment'>";
            echo "<p><strong>Email:</strong> " . $comment['email'] . "</p>";
            echo "<p>" . $comment['comentario'] . "</p>";
            echo "</div>";
        }
        echo "</div>";
    } else {
        echo "<p>No comments yet.</p>";
    }
    ?>

    <!-- Formulario para escribir un nuevo comentario -->
    <h2>Add Comment</h2>
    <form action="add_comment.php" method="post">
        <label for="user">Your Name:</label><br>
        <input type="text" id="user" name="user"><br>
        <label for="comment">Your Comment:</label><br>
        <textarea id="comment" name="comment" rows="4" cols="50"></textarea><br>
        <button type="submit" name="submit">Submit</button>
    </form>

    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
