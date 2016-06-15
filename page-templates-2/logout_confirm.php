<?php
session_start();
session_destroy();
header('Location: success.php?message=' . htmlspecialchars("You've logged out!"));
?>
