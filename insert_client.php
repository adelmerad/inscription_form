<?php
// Démarrer la session si nécessaire
session_start();

// Connexion à la base de données
$con = mysqli_connect("localhost", "root", "", "inscription");

if (mysqli_connect_errno()) {
    die("Échec de connexion à MySQL : " . mysqli_connect_error());
}

// Récupérer les clients
$query = "SELECT nom, prenom, email FROM client ORDER BY id DESC";
$result = mysqli_query($con, $query);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Clients</title>
    <style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f4f6f9;
        margin: 0;
        padding: 40px;
        color: #333;
    }

    h2 {
        text-align: center;
        margin-bottom: 30px;
        font-size: 28px;
        color: #2c3e50;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background-color: #ffffff;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        border-radius: 8px;
        overflow: hidden;
    }

    thead {
        background-color: #34495e;
        color: #ecf0f1;
    }

    th, td {
        padding: 14px 18px;
        text-align: left;
    }

    tbody tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    tbody tr:hover {
        background-color: #e1ecf4;
        transition: background-color 0.3s ease;
    }

    .back-link {
        display: block;
        margin: 30px auto;
        width: max-content;
        padding: 10px 20px;
        background-color: #3498db;
        color: white;
        text-decoration: none;
        border-radius: 6px;
        font-weight: bold;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        transition: background-color 0.3s ease, transform 0.2s ease;
    }

    .back-link:hover {
        background-color: #2980b9;
        transform: translateY(-2px);
    }

    @media (max-width: 768px) {
        table, thead, tbody, th, td, tr {
            display: block;
        }

        thead {
            display: none;
        }

        tr {
            margin-bottom: 15px;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            border-radius: 5px;
            padding: 10px;
        }

        td {
            position: relative;
            padding-left: 50%;
        }

        td::before {
            content: attr(data-label);
            position: absolute;
            left: 15px;
            font-weight: bold;
            color: #555;
        }
    }
</style>

</head>
<body>

    <h2>Liste des clients enregistrés</h2>

    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['nom']) ?></td>
                        <td><?= htmlspecialchars($row['prenom']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3">Aucun client enregistré.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <a class="back-link" href="formulaire.php">&larr; Retour au formulaire</a>

</body>
</html>

<?php
mysqli_close($con);
?>
