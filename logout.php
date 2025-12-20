<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["logout"])) {
    session_destroy();
    header("Location: index.php");
}
exit;
<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["logout"])) {
    session_destroy();
    header("Location: index.php");
    exit(); // toujours mettre exit() après un header pour stopper le script
}
?>