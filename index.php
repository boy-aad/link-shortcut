<?php


if (!empty($_GET['q'])) {
	$shortcut = htmlspecialchars($_GET['q']);

	try {
		$bdd = new PDO(
			'pgsql:host=dpg-d1l8sq7diees73fcdn90-a;port=5432;dbname=linkshortcut_db',
			'linkshortcut_db_user',
			'kR5U7M4bHbKVJnlf0B8COIuG18fB7hE8'
		);
		$sql = $bdd-> prepare("CREATE TABLE  links (
        id SERIAL PRIMARY KEY,
        url TEXT NOT NULL,
        shortcuts VARCHAR(255) NOT NULL UNIQUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );");
		$sql->execute([$shortcut]);
		$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		// Vérifie si le lien existe
		$requete = $bdd->prepare('SELECT COUNT(*) AS nombreDeLigne FROM links WHERE shortcuts = ?');
		$requete->execute([$shortcut]);

		$resultat = $requete->fetch();
		if ($resultat['nombreDeLigne'] != 1) {
			header("Location: ./?error=true&message=Adresse url non connue");
			exit();
		}

		// Redirection vers l'URL d'origine
		$requete = $bdd->prepare('SELECT * FROM links WHERE shortcuts = ?');
		$requete->execute([$shortcut]);

		$resultat = $requete->fetch();
		header('Location: ' . $resultat['url']);
		exit();
	} catch (PDOException $e) {
		echo "Erreur de connexion : " . $e->getMessage();
		exit();
	}
}



if (!empty($_POST['url'])) {
	// variable
	$url = htmlspecialchars($_POST['url']);

	// Verifier le format de l'url en filtrant l'url
	if (!filter_var($url, FILTER_VALIDATE_URL)) {
		header('location: ./?error=true&message=Adresse url non valide');
		exit();
	}

	// creer le racourci
	$shortcut = crypt($url, rand());


	// verifier s'il y'a doublon d'url
	$bdd = new PDO(
		'pgsql:host=dpg-d1l8sq7diees73fcdn90-a;port=5432;dbname=linkshortcut_db',
		'linkshortcut_db_user',
		'kR5U7M4bHbKVJnlf0B8COIuG18fB7hE8'
	); //$bdd = new PDO('mysql:host=localhost;dbname=bitly;charset=utf8', 'root', '');
	// $requete = $bdd -> prepare('SELECT COUNT(*) AS nombreDeLigne FROM links WHERE url = ?');
	// $requete -> execute([$url]);

	// while ($resultat = $requete->fetch()) {
	// 	if ($resultat['nombreDeLigne'] != 0) {
	// 		header("location: ./?error=true&message=Adresse deja raccourcie");
	// 		exit();
	// 	}
	// }

	// ajout racourci
	$ajout = $bdd->prepare("INSERT INTO links(url,shortcuts) VALUES(?, ?)");
	$ajout->execute([$url, $shortcut]);

	header("location: ./?short=$shortcut");
	exit();
}





?>



<html>

<head>
	<meta charset="utf-8">
	<title>BITLY - Raccourcissez vos urls</title>
	<link rel="stylesheet" href="design/default.css">
	<link rel="icon" type="image/png" href="assets/favicon.png">
</head>

<body>

	<!-- PRESENTATION -->
	<section id="main">

		<!-- CONTAINER -->
		<div class="container">

			<!-- EN-TETE -->
			<?php require_once("src/header.php") ?>

			<!-- PROPOSITION -->
			<h1>Une url longue ? Raccourcissez-là ?</h1>
			<h2>Largement meilleur et plus court que les autres.</h2>

			<!-- FORM -->
			<form method="post" action="index.php">
				<input type="url" name="url" placeholder="Collez un lien à raccourcir">
				<input type="submit" value="Raccourcir">
			</form>

			<?php if (isset($_GET['error']) && isset($_GET['message'])) { ?>

				<div class="center">
					<div id="result">
						<b><?php echo htmlspecialchars($_GET['message']); ?></b>
					</div>
				</div>

			<?php } else if (isset($_GET['short'])) { ?>
				<div class="center">
					<div id="result">
						<b>URL RACCOURCIE : </b>
						<?php
						$baseUrl = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
						$baseUrl = preg_replace('/index\.php$/', '', $baseUrl);
						echo $baseUrl . "?q=" . htmlspecialchars($_GET['short']);
						?>
					</div>
				</div>
			<?php } ?>


		</div>

	</section>

	<!-- MARQUES -->
	<section id="brands">

		<!-- CONTAINER -->
		<div class="container">
			<h3>Ces marques nous font confiance</h3>
			<img src="assets/1.png" alt="1" class="picture">
			<img src="assets/2.png" alt="2" class="picture">
			<img src="assets/3.png" alt="3" class="picture">
			<img src="assets/4.png" alt="4" class="picture">
		</div>

	</section>

	<!-- PIED DE PAGE -->
	<?php require_once('src/footer.php'); ?>
</body>

</html>