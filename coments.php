<?php
// Iniciar sesión si aún no está iniciada
session_start();

// Incluir el archivo de conexión a la base de datos
include("conexion.php");

// Verificar si se ha enviado un nuevo comentario
if(isset($_POST['submit']) && isset($_POST['comment']) && isset($_SESSION['iduser'])) {
    // Obtener el comentario enviado
    $nuevo_comentario = $_POST['comment'];
    
    // Insertar el nuevo comentario en la base de datos
    $stmt = $conn->prepare("INSERT INTO comentario (comentario, idUsuarios) VALUES (?, ?)");
    $stmt->execute([$nuevo_comentario, $_SESSION['iduser']]);
}

// Consulta para obtener los comentarios existentes
$sql = "SELECT c.comentario, u.email 
FROM imdb.comentario AS c 
INNER JOIN imdb.usuarios AS u ON c.idUsuarios = u.idUsuarios";
$stmt = $conn->prepare($sql);
$stmt->execute();
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

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
        echo "<p>No hay comentarios aún.</p>";
    }
    ?>

    <!-- Formulario para escribir un nuevo comentario -->
    <h2>Add Comment</h2>
    <form action="" method="post">
        <label for="comment">Comentarios:</label><br>
        <textarea id="comment" name="comment" rows="4" cols="50"></textarea><br>
        <button type="submit" name="submit">Submit</button>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
