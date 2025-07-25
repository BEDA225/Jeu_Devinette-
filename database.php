<?php
// Configuration de la base de données
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'jeu_devinette');

/**
 * Fonction pour obtenir une connexion à la base de données
 * @return mysqli
 * @throws Exception
 */
function getConnection() {
    try {
        // Connexion initiale sans base de données pour créer la DB si nécessaire
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS);
        
        if ($conn->connect_error) {
            throw new Exception("Erreur de connexion : " . $conn->connect_error);
        }
        
        // Définir le charset
        $conn->set_charset("utf8mb4");
        
        // Créer la base de données si elle n'existe pas
        $sql = "CREATE DATABASE IF NOT EXISTS " . DB_NAME . " CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci";
        if (!$conn->query($sql)) {
            throw new Exception("Erreur lors de la création de la base de données : " . $conn->error);
        }
        
        // Sélectionner la base de données
        $conn->select_db(DB_NAME);
        
        // Créer la table si elle n'existe pas
        createUserTable($conn);
        
        // Insérer les données de test si la table est vide
        insertTestData($conn);
        
        return $conn;
        
    } catch (Exception $e) {
        throw new Exception("Erreur de connexion à la base de données : " . $e->getMessage());
    }
}

/**
 * Créer la table compte_utilisateur si elle n'existe pas
 * @param mysqli $conn
 * @throws Exception
 */
function createUserTable($conn) {
    $sql = "CREATE TABLE IF NOT EXISTS compte_utilisateur (
        id INT(5) PRIMARY KEY AUTO_INCREMENT,
        nom_utilisateur VARCHAR(50) NOT NULL UNIQUE,
        mot_de_passe VARCHAR(255) NOT NULL
    ) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci";
    
    if (!$conn->query($sql)) {
        throw new Exception("Erreur lors de la création de la table : " . $conn->error);
    }
}

/**
 * Insérer les données de test si la table est vide
 * @param mysqli $conn
 * @throws Exception
 */
function insertTestData($conn) {
    // Vérifier si la table contient déjà des données
    $result = $conn->query("SELECT COUNT(*) as count FROM compte_utilisateur");
    $row = $result->fetch_assoc();
    
    if ($row['count'] == 0) {
        // Insérer les données de test
        $test_users = [
            ['sonic12345', 'hellomontreal'],
            ['asterix2023', 'helloquebec'],
            ['pokemon527', 'hellocanada']
        ];
        
        $stmt = $conn->prepare("INSERT INTO compte_utilisateur (nom_utilisateur, mot_de_passe) VALUES (?, ?)");
        
        foreach ($test_users as $user) {
            $username = $user[0];
            $password = password_hash($user[1], PASSWORD_DEFAULT);
            
            $stmt->bind_param("ss", $username, $password);
            
            if (!$stmt->execute()) {
                throw new Exception("Erreur lors de l'insertion des données de test : " . $stmt->error);
            }
        }
        
        $stmt->close();
    }
}

/**
 * Vérifier si un nom d'utilisateur existe déjà
 * @param string $username
 * @return bool
 */
function usernameExists($username) {
    try {
        $conn = getConnection();
        
        $stmt = $conn->prepare("SELECT id FROM compte_utilisateur WHERE nom_utilisateur = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $exists = $result->num_rows > 0;
        
        $stmt->close();
        $conn->close();
        
        return $exists;
        
    } catch (Exception $e) {
        return false;
    }
}
?>