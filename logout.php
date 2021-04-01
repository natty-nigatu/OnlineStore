<?php
session_start();

unset($_SESSION["account"]);
unset($_SESSION["type"]);

header("Location: index.php");