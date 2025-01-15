<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>Connexion</title>
    </head>
    <body>
    <?php if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        exit('Méthode non autorisée');
    } ?>
        <form method="POST" action="verifAuth">
            <ul>
                <li>
                    <label for="pseudo">Pseudo : </label>
                    <input type="textarea" id="pseudo" name="pseudo" placeholder="Entrez votre pseudo">
                </li>
            </ul>
            <input type="submit" value="S'indentifier">
        </form>
        <form method="POST", action="inscrition">
            <ul>
                <li>
                    <label for="pseudo">Pseudo : </label>
                    <input type="textarea" id="pseudo" name="pseudo" placeholder="Entrez votre pseudo">
                </li>
            </ul>
            <input type="submit" value="S'inscrire">
        </form>
        
    </body>
</html>