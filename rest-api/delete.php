<?php
/**
 * Update a movie
 * Methode: DELETE
 */

 // Connexion à la BDD
require_once 'connexion.php';

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');

$method = $_SERVER['REQUEST_METHOD'];

/**
 * Si la méthode est différente de "DELETE", 
 * on retourne une erreur
 */
if ($method !== 'DELETE') {
    // Récupère ou définit le code de réponse HTTP
    http_response_code(405);

    echo json_encode([
        'status' => 405,
        'message' => 'Method Not Allowed'
    ]);

    exit;
}

/**
 * Si le paramètre "id" n'existe pas dans l'URL,
 * on retourne une erreur 400
 */
if (empty($_GET['id'])) {
    http_response_code(400);

    echo json_encode([
        'status' => 400,
        'message' => 'Bad Request'
    ]);

    exit;
}

// Récupération de la valeur du paramètre "id"
$id = htmlspecialchars(strip_tags($_GET['id']));
// Récupère les données de BD par id
// Récupère les données envoyées en POST (permet de recuperer les data envoyees)
$datas = json_decode(file_get_contents('php://input'), true);

        $query = $db->prepare('DELETE FROM movies WHERE id = :id');
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $query->execute();

        if ($query->rowCount()===0) {
            http_response_code(404);

            echo json_encode([
                    'status' => 404,
                    'message' => 'Not Found'
                ]);

                exit;
        } 

        http_response_code(204);

       


