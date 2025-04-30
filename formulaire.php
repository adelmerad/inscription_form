<?php 
session_start();
$nom = $prenom = "";
$errors = [];

function traiter_input($data) {
    $data = trim($data);
    $data = stripcslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = traiter_input($_POST["nom"]);
    $prenom = traiter_input($_POST["prenom"]);
    $email = traiter_input($_POST["email"]);
    $age = traiter_input($_POST["age"]);
    $password = traiter_input($_POST["password"]);
    $confirm = traiter_input($_POST["confirm_password"]);

    // Verification des champs obligatoires
    if (empty($nom)) {
        $errors['nom'] = "Le champ 'Nom' est obligatoire.";
    }
    if (empty($email)) {
        $errors['email'] = "Le champ 'Email' est obligatoire.";
    } 
    if (empty($password)) {
        $errors['password'] = "Le champ 'Mot de passe' est obligatoire.";
    }
    if (empty($confirm)) {
        $errors['confirm'] = "Le champ 'Confirmation du mot de passe' est obligatoire.";
    }

    // Verification que l'âge est une valeur numérique
    if (!empty($age) && !is_numeric($age)) {
        $errors['age'] = "L'âge doit être une valeur numérique.";
    }

    // Verification que l'email est valide
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Email invalide";
    }

    // Verification que le mot de passe a au moins 6 caractères
    if (strlen($password) < 6) {
        $errors['password'] = "Le mot de passe doit contenir au moins 6 caractères.";
    }

    // Verification que la confirmation du mot de passe correspond
    if ($password !== $confirm) {
        $errors['confirm'] = "La confirmation du mot de passe ne correspond pas.";
    }

    // Affichage des résultats
    if (!empty($errors)) {
        echo "<h2>Erreurs détectées :</h2>";
        echo "<ul>";
        foreach ($errors as $error) {
            echo "<li style='color: red;'>$error</li>";
        }
        echo "</ul>";
        echo '<p><a href="formulaire.php">Retour au formulaire</a></p>';
    }
    else {
      /*  // Pour la question 2
        $donnees = [
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
            'age' => $age
        ];

        $chaine = urlencode(serialize($donnees));

        header("Location: nouvelle_session.php?data=$chaine");
        exit();*/

        $con = mysqli_connect("localhost", "root", "", "inscription");
        
        if (mysqli_connect_errno()) {
            echo "Échec de connexion à MySQL : " . mysqli_connect_error();
            exit();
        }

        $nom = mysqli_real_escape_string($con, $nom);
        $prenom = mysqli_real_escape_string($con, $prenom);
        $email = mysqli_real_escape_string($con, $email);

        $query = "SELECT COUNT(*) as count FROM client WHERE email = '$email'";
        $result = mysqli_query($con, $query);
        $row = mysqli_fetch_assoc($result);
        
        if ($row['count'] > 0) {
            $errors['email'] = "Cet email est déjà enregistré.";
        } else {
            $query = "INSERT INTO client (nom, prenom, email) VALUES ('$nom', '$prenom', '$email')";
            if (!mysqli_query($con, $query)) {
                $errors['database'] = "Erreur lors de l'insertion : " . mysqli_error($con);
            }
        }
        
        mysqli_close($con);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TP3</title>
    <style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f4f6f9;
        padding: 40px;
        margin: 0;
        color: #2c3e50;
    }

    section#form1 {
        max-width: 500px;
        margin: auto;
        background-color: #ffffff;
        padding: 30px 40px;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    h2 {
        text-align: center;
        margin-bottom: 25px;
        color: #34495e;
    }

    label {
        display: block;
        margin-top: 15px;
        margin-bottom: 5px;
        font-weight: bold;
    }

    input[type="text"],
    input[type="password"] {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 16px;
        box-sizing: border-box;
        transition: border 0.3s ease;
    }

    input[type="text"]:focus,
    input[type="password"]:focus {
        border-color: #3498db;
        outline: none;
    }

    .error {
        color: #e74c3c;
        font-size: 14px;
        display: block;
        margin-top: 5px;
    }

    input[type="submit"] {
        background-color: #3498db;
        color: white;
        border: none;
        padding: 12px;
        width: 100%;
        font-size: 16px;
        margin-top: 20px;
        border-radius: 6px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    input[type="submit"]:hover {
        background-color: #2980b9;
    }

    button {
        background-color: transparent;
        border: none;
        margin-top: 15px;
        display: block;
        width: 100%;
        text-align: center;
    }

    .insert-link {
        display: inline-block;
        padding: 10px 20px;
        background-color: #2ecc71;
        color: white;
        text-decoration: none;
        border-radius: 6px;
        font-weight: bold;
        transition: background-color 0.3s ease, transform 0.2s ease;
    }

    .insert-link:hover {
        background-color: #27ae60;
        transform: translateY(-2px);
    }

    @media (max-width: 600px) {
        section#form1 {
            padding: 20px;
        }
    }
</style>

</head>
<body>
    <section id="form1">
        <h2>Formulaire d'inscription</h2>

        <form id="form" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="nom">Nom :</label>
            <input type="text" id="nom" name="nom"><br>
            <span class="error">* <?= $errors['nom'] ?? '' ?></span><br>

            <label for="prenom">Prénom :</label>
            <input type="text" id="prenom" name="prenom"><br>
            <span class="error">* <?= $errors['prenom'] ?? '' ?></span><br>

            <label for="age">Âge :</label>
            <input type="text" id="age" name="age" min="1"><br>
            <span class="error">* <?= $errors['age'] ?? '' ?></span><br>

            <label for="email">Email :</label>
            <input type="text" id="email" name="email"><br>
            <span class="error">* <?= $errors['email'] ?? '' ?></span><br>

            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password"><br>
            <span class="error">* <?= $errors['password'] ?? '' ?></span><br>

            <label for="confirm_password">Confirmer le mot de passe :</label>
            <input type="password" id="confirm_password" name="confirm_password"><br>
            <span class="error">* <?= $errors['confirm'] ?? '' ?></span><br>

            <input type="submit" value="S'inscrire">
            <a class="insert-link" href="insert_client.php">Afficher Clients</a>
        </form>
    </section>
</body>
</html>
