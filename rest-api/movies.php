<?php

/**
 * Récupération de tous les films
 * Méthode : GET
 */

// Retour d'en-tête
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');

// Récupération de la méthode
$method = $_SERVER['REQUEST_METHOD'];

/**
 * Si la méthode est différente de "GET", 
 * on retourne une erreur 405
 */
if ($method !== 'GET') {
    // Récupère ou définit le code de réponse HTTP
    http_response_code(405);

    echo json_encode([
        'status' => 405,
        'message' => 'Method Not Allowed'
    ]);

    exit;
}

// Connexion à la BDD
require_once 'connexion.php';

// Sélection de tous les films en BDD
$query = $db->query('SELECT * FROM movies');
$movies = $query->fetchAll();

// 200 - OK
http_response_code(200);

// Retourne les données au format JSON
echo json_encode($movies);