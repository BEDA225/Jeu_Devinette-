<?php
session_start();

// Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Traitement du formulaire de jeu
$message = '';
$game_numbers = [];
$user_numbers = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['play'])) {
        // Récupérer les nombres choisis par l'utilisateur
        $user_numbers = [
            (int)$_POST['number1'],
            (int)$_POST['number2'],
            (int)$_POST['number3'],
            (int)$_POST['number4'],
            (int)$_POST['number5']
        ];
        
        // Générer 5 nombres aléatoirement distincts entre 0 et 12
        $game_numbers = [];
        while (count($game_numbers) < 5) {
            $rand_num = rand(0, 12);
            if (!in_array($rand_num, $game_numbers)) {
                $game_numbers[] = $rand_num;
            }
        }
        
        // Comparer les nombres
        $correct_count = 0;
        foreach ($user_numbers as $user_num) {
            if (in_array($user_num, $game_numbers)) {
                $correct_count++;
            }
        }
        
        // Préparer le message selon le scénario
        $message = "<div class='game-result'>";
        $message .= "<p>1- Nous avons généré les nombres " . implode(', ', $game_numbers) . "</p>";
        $message .= "<p>2- Vous avez deviné les nombres " . implode(', ', $user_numbers) . "</p>";
        
        if ($correct_count === 5) {
            $message .= "<p>3- Résultat : Vous avez deviné tous les chiffres que nous avons générés ! Vous êtes un EXCELLENT devin !</p>";
        } elseif ($correct_count > 0) {
            $message .= "<p>3- Résultat : Vous avez deviné $correct_count des chiffres que nous avons générés ! Vous êtes un BON devin !</p>";
        } else {
            $message .= "<p>3- Résultat : Vous n'avez deviné aucun des chiffres que nous avons générés ! Réessayez, ça marchera la prochaine fois!</p>";
        }
        $message .= "</div>";
    }
    
    if (isset($_POST['logout'])) {
        session_destroy();
        header('Location: login.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jeu de Devinette</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 50px auto;
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
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        .numbers-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 10px;
            margin-bottom: 20px;
        }
        button {
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin: 5px;
        }
        .btn-play {
            background-color: #4CAF50;
            color: white;
        }
        .btn-logout {
            background-color: #f44336;
            color: white;
        }
        button:hover {
            opacity: 0.8;
        }
        .game-result {
            margin-top: 20px;
            padding: 15px;
            background-color: #e7f3ff;
            border-left: 4px solid #2196F3;
            border-radius: 4px;
        }
        .game-result p {
            margin: 5px 0;
        }
        .welcome {
            text-align: center;
            margin-bottom: 20px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Jeu de Devinette</h1>
        
        <div class="welcome">
            <p>Bienvenue, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
            <p>Devinez 5 nombres entre 0 et 12</p>
        </div>
        
        <form method="POST" action="">
            <div class="numbers-grid">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <div class="form-group">
                        <label for="number<?php echo $i; ?>">Nombre <?php echo $i; ?></label>
                        <select name="number<?php echo $i; ?>" id="number<?php echo $i; ?>" required>
                            <?php for ($j = 0; $j <= 12; $j++): ?>
                                <option value="<?php echo $j; ?>"
                                    <?php echo (isset($user_numbers[$i-1]) && $user_numbers[$i-1] == $j) ? 'selected' : ''; ?>>
                                    <?php echo $j; ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                <?php endfor; ?>
            </div>
            
            <div style="text-align: center;">
                <button type="submit" name="play" class="btn-play">JOUER</button>
                <button type="submit" name="logout" class="btn-logout">SE DÉCONNECTER</button>
            </div>
        </form>
        
        <?php if (!empty($message)): ?>
            <?php echo $message; ?>
        <?php endif; ?>
    </div>
</body>
</html>