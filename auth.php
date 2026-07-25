<?php
session_start();

function verifierClient() {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'client') {
        header("Location: connexion.php");
        exit;
    }
}

function verifierAdmin() {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
        header("Location: connexion.php");
        exit;
    }
} 