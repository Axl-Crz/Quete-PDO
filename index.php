<?php

require_once '_connec.php';

$pdo = new \PDO(DSN, USER, PASS);

$query = "SELECT * FROM friend";
$statement = $pdo->query($query);
$friends = $statement->fetchAll(PDO::FETCH_ASSOC);

if (!empty($_POST)) {

    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);

    $query = 'INSERT INTO friend (firstname, lastname) VALUES (:firstname, :lastname)';
    $statement = $pdo->prepare($query);

    $statement->bindValue(':firstname', $firstname, \PDO::PARAM_STR);
    $statement->bindValue(':lastname', $lastname, \PDO::PARAM_STR);

    $statement->execute();
}
$errors = [];
if (!empty($_POST)) {
    $data = array_map('trim', $_POST);
    $firstname = htmlentities($data["firstname"]);
    $lastname = htmlentities($data["lastname"]);

    if (strlen($firstname) > 45) {
        $errors["firstname"] = "Ne dois pas dépasser 45 caractères";
    }

    if (strlen($lastname) > 45) {
        $errors["lastname"] = "Ne dois pas dépasser 45 caractères";
    }
    if (empty($errors)) {
        header('Location: index.php');
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Liste d'amis</title>
</head>

<body>

    <ul>
        <?php foreach ($friends as $friend) : ?>
            <li><?= $friend['firstname'] . ' ' . $friend['lastname']; ?></li>
        <?php endforeach; ?>
    </ul>

    <form method="POST">
        <div>
            <label for="firstname">Prénom :</label>
            <input type="text" name="firstname" id="firstname" placeholder="Prénom" maxlength="45" required>
            <span class="text-alert"><?= $errors["firstname"] ?? '' ?></span>
        </div>

        <div>
            <label for="lastname">Nom :</label>
            <input type="text" name="lastname" id="lastname" placeholder="Nom" maxlength="45" required>
            <span class="text-alert"><?= $errors["lastname"] ?? '' ?></span>
        </div>

        <button> Ajouter un ami</button>
    </form>

</body>

</html>