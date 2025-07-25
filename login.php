<?php
session_start();
require_once 'database.php';

// Rediriger vers la page principale si l'utilisateur est déjà connecté
if (isset($_SESSION['user_id'])) {
    header('Location: index1.php');
    exit();
}

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['signin'])) {
        $username = trim($_POST['username']);
        $password = $_POST['password'];
        
        if (!empty($username) && !empty($password)) {
            try {
                $conn = getConnection();
                
                // Préparer la requête pour éviter les injections SQL
                $stmt = $conn->prepare("SELECT id, nom_utilisateur, mot_de_passe FROM compte_utilisateur WHERE nom_utilisateur = ?");
                $stmt->bind_param("s", $username);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if ($result->num_rows > 0) {
                    $user = $result->fetch_assoc();
                    
                    // Vérifier le mot de passe
                    if (password_verify($password, $user['mot_de_passe'])) {
                        // Connexion réussie
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['username'] = $user['nom_utilisateur'];
                        header('Location: index1.php');
                        exit();
                    } else {
                        $error_message = "Le nom d'utilisateur ou le mot de passe est incorrect.";
                    }
                } else {
                    $error_message = "Le nom d'utilisateur ou le mot de passe est incorrect.";
                }
                
                $stmt->close();
                $conn->close();
                
            } catch (Exception $e) {
                $error_message = "Erreur de connexion à la base de données : " . $e->getMessage();
            }
        } else {
            $error_message = "Veuillez remplir tous les champs.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Jeu de Devinette</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 400px;
            margin: 100px auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-bottom: 10px;
        }
        .btn-signin {
            background-color: #4CAF50;
            color: white;
        }
        .btn-signup {
            background-color: #2196F3;
            color: white;
        }
        button:hover {
            opacity: 0.8;
        }
        .error-message {
            color: #f44336;
            margin-bottom: 15px;
            padding: 10px;
            background-color: #ffebee;
            border-left: 4px solid #f44336;
            border-radius: 4px;
        }
        .nav-link {
            display: block;
            text-align: center;
            margin-top: 15px;
            text-decoration: none;
            color: #2196F3;
        }
        .nav-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Connexion</h1>
        
        <?php if (!empty($error_message)): ?>
            <div class="error-message">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Nom d'utilisateur</label>
                <input type="text" id="username" name="username" required 
                       value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit" name="signin" class="btn-signin">SE CONNECTER</button>
            <a href="register.php" style="text-decoration: none;">
                <button type="button" class="btn-signup">S'INSCRIRE</button>
            </a>
        </form>
        
        <div style="margin-top: 20px; padding: 10px; background-color: #e7f3ff; border-radius: 4px; font-size: 14px;">
            <strong>Comptes de test :</strong><br>
            - sonic12345 / hellomontreal<br>
            - asterix2023 / helloquebec<br>
            - pokemon527 / hellocanada
        </div>
    </div>
</body>
</html>