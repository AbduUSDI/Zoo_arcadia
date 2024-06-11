<?php
session_start();
require 'functions.php';

// Connection à la base données

$db = (new Database())->connect();
$user = new User($db);

// Récupération des données ajoutées au formulaire POST (login) et redirection en fonction du rôle de l'utilisateur avec un message d'erreur en cas de champs incorrect

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    echo "Email: $email<br>";
    echo "Password: $password<br>";

    $userData = $user->getUtilisateurParEmail($email);

    // Ici utilisation d'un if pour afficher les message correspondants en cas de réussite ou en cas d'erreur

    if ($userData) {
        echo "Utilisateur trouvé : " . print_r($userData, true) . "<br>";
    } else {
        echo "Aucun utilisateur trouvé pour cet email.<br>";
    }
    
    // Ici c'est pour vérifier le mot de passe et rediriger vers la bonne page grâce au role_id de la base de données

    if ($userData && password_verify($password, $userData['password'])) {
        echo "Mot de passe vérifié.<br>";
        $_SESSION['user'] = $userData;
        if ($userData['role_id'] == 1) {
            header('Location: admin/index.php');
        } elseif ($userData['role_id'] == 2) {
            header('Location: employee/index.php');
        } elseif ($userData['role_id'] == 3) {
            header('Location: vet/index.php');
        } else {

            header('Location: index.php');
        }
        exit;

    // Message d'erreur en cas d'erreur de saisie

    } else {
        $error = "Email ou mot de passe incorrect.";
    }
}

include 'templates/header.php';
include 'templates/navbar_visitor.php';
?>
<style>
body {
  padding-top: 48px;
}
</style>
<!-- Container pour afficher le formulaire de connexion -->
<div class="container">
    <h1 class="my-4">Connexion</h1>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    <form action="login.php" method="POST">
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" autocomplete="email" required>
        </div>
        <div class="form-group">
            <label for="password">Mot de passe</label>
            <div class="input-group">
                <input type="password" class="form-control" id="password" name="password" autocomplete="current-password" required>
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="button" id="togglePassword"><i class="fas fa-eye"></i></button>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Se connecter</button>
    </form>
</div>
<script>
    /* Ce script permet l'utilisation de fontawesome pour afficher/désafficher le mot de passe */
    document.addEventListener('DOMContentLoaded', function() {
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');

    togglePassword.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);

        const eyeIcon = this.querySelector('i');
        if (type === 'password') {
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
        } else {
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        }
    });
});

</script>
<script src="https://kit.fontawesome.com/a076d05399.js"></script>

<?php include 'templates/footer.php'; ?>
