<?php
/**
 * Update a movie
 * Methode: PUT
 */

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');

$method = $_SERVER['REQUEST_METHOD'];

/**
 * Si la méthode est différente de "PUT", 
 * on retourne une erreur
 */
if ($method !== 'PUT') {
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

/**
 * Si tous les champs sont bien remplis, on insère en BDD
 */
if (
    !empty($datas['title']) 
    && !empty($datas['description'])
    && !empty($datas['date'])
    && !empty($datas['time'])
    && !empty($datas['director'])
    && !empty($datas['image'])
    && !empty($datas['trailer'])
) {
   // Nettoie les données
    foreach ($datas as $key => $value) {
        $datas[$key] = htmlspecialchars(strip_tags($value)); 
    }

    // Connexion à la BDD
    require_once 'connexion.php'; 

    // Update en BDD
    $query = $db->prepare('UPDATE movies SET title = :title, description = :description, date = :date, time = :time, director = :director, image = :image, trailer = :trailer WHERE id = :id');
    $query->bindValue(':title', $datas['title']);
    $query->bindValue(':description', $datas['description']);
    $query->bindValue(':date', $datas['date']);
    $query->bindValue(':time', $datas['time'], PDO::PARAM_INT);
    $query->bindValue(':director', $datas['director']);
    $query->bindValue(':image', $datas['image']);
    $query->bindValue(':trailer', $datas['trailer']);
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

     http_response_code(200);

    // Retour de la ressource
    echo json_encode([
        'id' => $id,
        ...$datas
    ]);
}
// Sinon on retourne une erreur...
else {
    http_response_code(400);

    echo json_encode([
        'status' => 400,
        'message' => 'Bad Request'
    ]);

    exit;
}

