<?php
header('Content-Type: application/json');
require_once 'database.php';

// Vérifier si la requête est POST et contient le nom d'utilisateur
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'])) {
    $username = trim($_POST['username']);
    
    // Vérifier si le nom d'utilisateur n'est pas vide
    if (!empty($username)) {
        try {
            $exists = usernameExists($username);
            
            // Retourner la réponse JSON
            echo json_encode([
                'exists' => $exists,
                'username' => $username
            ]);
            
        } catch (Exception $e) {
            // En cas d'erreur, retourner false pour éviter de bloquer l'inscription
            echo json_encode([
                'exists' => false,
                'error' => $e->getMessage()
            ]);
        }
    } else {
        echo json_encode([
            'exists' => false,
            'error' => 'Nom d\'utilisateur vide'
        ]);
    }
} else {
    echo json_encode([
        'exists' => false,
        'error' => 'Requête invalide'
    ]);
}
?>