<?php
session_start();
require_once 'database.php';

// Rediriger vers la page principale si l'utilisateur est déjà connecté
if (isset($_SESSION['user_id'])) {
    header('Location: index1.php');
    exit();
}

$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['signup'])) {
        $username = trim($_POST['username']);
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        
        // Validation des champs
        if (empty($username) || empty($password) || empty($confirm_password)) {
            $error_message = "Veuillez remplir tous les champs.";
        } elseif ($password !== $confirm_password) {
            $error_message = "Vous avez entré 2 mots de passe différents.";
        } else {
            try {
                $conn = getConnection();
                
                // Vérifier si l'utilisateur existe déjà
                $stmt = $conn->prepare("SELECT id FROM compte_utilisateur WHERE nom_utilisateur = ?");
                $stmt->bind_param("s", $username);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if ($result->num_rows > 0) {
                    $error_message = "Ce nom d'utilisateur existe déjà.";
                } else {
                    // Hasher le mot de passe
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    
                    // Insérer le nouvel utilisateur
                    $stmt = $conn->prepare("INSERT INTO compte_utilisateur (nom_utilisateur, mot_de_passe) VALUES (?, ?)");
                    $stmt->bind_param("ss", $username, $hashed_password);
                    
                    if ($stmt->execute()) {
                        $success_message = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
                        // Optionnel : rediriger automatiquement vers la page de connexion après 3 secondes
                        header("refresh:3;url=login.php");
                    } else {
                        $error_message = "Erreur lors de l'inscription. Veuillez réessayer.";
                    }
                }
                
                $stmt->close();
                $conn->close();
                
            } catch (Exception $e) {
                $error_message = "Erreur de connexion à la base de données : " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Jeu de Devinette</title>
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
        .btn-signup {
            background-color: #4CAF50;
            color: white;
        }
        .btn-signin {
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
        .success-message {
            color: #4CAF50;
            margin-bottom: 15px;
            padding: 10px;
            background-color: #e8f5e8;
            border-left: 4px solid #4CAF50;
            border-radius: 4px;
        }
        .ajax-error {
            color: #f44336;
            font-size: 14px;
            margin-top: 5px;
        }
        .username-status {
            font-size: 14px;
            margin-top: 5px;
        }
        .username-available {
            color: #4CAF50;
        }
        .username-taken {
            color: #f44336;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Inscription</h1>
        
        <?php if (!empty($error_message)): ?>
            <div class="error-message">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($success_message)): ?>
            <div class="success-message">
                <?php echo htmlspecialchars($success_message); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Nom d'utilisateur</label>
                <input type="text" id="username" name="username" required 
                       value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                <div id="username-status" class="username-status"></div>
            </div>
            
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirmer Mot de passe</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            
            <button type="submit" name="signup" class="btn-signup">S'INSCRIRE</button>
            <a href="login.php" style="text-decoration: none;">
                <button type="button" class="btn-signin">SE CONNECTER</button>
            </a>
        </form>
    </div>

    <script>
        // AJAX pour vérifier en temps réel si le nom d'utilisateur existe
        document.getElementById('username').addEventListener('input', function() {
            const username = this.value;
            const statusDiv = document.getElementById('username-status');
            
            if (username.length > 0) {
                // Créer une requête AJAX
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'check_username.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        const response = JSON.parse(xhr.responseText);
                        
                        if (response.exists) {
                            statusDiv.textContent = 'Ce nom d\'utilisateur existe déjà.';
                            statusDiv.className = 'username-status username-taken';
                        } else {
                            statusDiv.textContent = 'Ce nom d\'utilisateur est disponible.';
                            statusDiv.className = 'username-status username-available';
                        }
                    }
                };
                
                xhr.send('username=' + encodeURIComponent(username));
            } else {
                statusDiv.textContent = '';
                statusDiv.className = 'username-status';
            }
        });
    </script>
</body>
</html>