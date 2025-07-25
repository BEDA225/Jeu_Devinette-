<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Inscription - Jeu de Devinette</title>
    <script src="https://cdn.tailwindcss.com"></script>
    
</head>
<!--<body class="bg-red-600 flex items-center justify-center min-h-screen"> -->
<body class ="bg-cyan-800 flex flex-col gap-2 rounded-lg bg-white p-6 shadow-md dark:bg-gray-800 items-center justify-center ">

<div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
    <h1 class="text-2xl font-bold text-center text-gray-800 mb-6">Inscription</h1>

    <?php if (!empty($error_message)): ?>
    <div class="bg-red-100 text-red-700 border-l-4 border-red-500 p-4 mb-4 rounded">
        <?= htmlspecialchars($error_message) ?>
    </div>
    <?php endif; ?>

    <?php if (!empty($success_message)): ?>
    <div class="bg-green-100 text-green-700 border-l-4 border-green-500 p-4 mb-4 rounded">
        <?= htmlspecialchars($success_message) ?>
    </div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="mb-4">
            <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Nom d'utilisateur</label>
            <input type="text" id="username" name="username" required
                   class="w-full border border-gray-300 px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                   value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>">
            <div id="username-status" class="text-sm mt-1"></div>
        </div>

        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Mot de passe</label>
            <input type="password" id="password" name="password" required
                   class="w-full border border-gray-300 px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="mb-6">
            <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-1">Confirmer le mot de passe</label>
            <input type="password" id="confirm_password" name="confirm_password" required
                   class="w-full border border-gray-300 px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <button type="submit" name="signup"
                class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded mb-2">
            S'inscrire
        </button>

        <a href="login.php">
            <button type="button"
                    class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                Se connecter
            </button>
        </a>
    </form>
</div>

<script>
    // AJAX pour vérifier le nom d'utilisateur
    document.getElementById('username').addEventListener('input', function () {
        const username = this.value;
        const statusDiv = document.getElementById('username-status');

        if (username.length > 0) {
            fetch('check_username.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({ username: username })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.exists) {
                        statusDiv.textContent = "Ce nom d'utilisateur existe déjà.";
                        statusDiv.className = "text-red-500";
                    } else {
                        statusDiv.textContent = "Ce nom d'utilisateur est disponible.";
                        statusDiv.className = "text-green-500";
                    }
                });
        } else {
            statusDiv.textContent = '';
            statusDiv.className = '';
        }
    });
</script>
</body>
</html>