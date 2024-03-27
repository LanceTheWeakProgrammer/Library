<?php
require_once 'router.php';
session_destroy();

header('location: ../index.php');
