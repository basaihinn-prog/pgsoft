<!doctype html>
<?php
require_once __DIR__ . '/includes/auth.php';
$gamesOrigin = rtrim(getenv('GAMES_ORIGIN') ?: 'https://games.pgplay.online', '/');
?>
<html>

<head>
	<meta charset="utf-8">
	<?php include "template/head.php"; ?>
	<style>
		.game-card {
			cursor: pointer;
			transition: transform 0.2s, box-shadow 0.2s;
			position: relative;
		}
		
		.game-card:hover {
			transform: translateY(-5px);
			box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
		}
		
		.game-card img {
			height: 250px;
			width: 100%;
			object-fit: cover;
		}
		
		.game-launch-btn {
			position: absolute;
			top: 50%;
			left: 50%;
			transform: translate(-50%, -50%);
			background: rgba(0, 0, 0, 0.7);
			color: white;
			padding: 10px 20px;
			border-radius: 5px;
			text-decoration: none;
			opacity: 0;
			transition: opacity 0.3s;
			z-index: 10;
		}
		
		.game-card:hover .game-launch-btn {
			opacity: 1;
		}
		
		.game-card-wrapper {
			position: relative;
		}
		
		.game-title {
			font-weight: 600;
			margin-top: 8px;
		}
	</style>
</head>

<body>
	<div class="layout-wrapper layout-content-navbar  ">
		<div class="layout-container">
			<?php include "template/aside.php"; ?>
			<!-- Layout container -->
			<div class="layout-page">
				<!-- Navbar -->
				<?php include "template/top.php"; ?>
				<!-- Content wrapper -->
				<div class="content-wrapper">
					<div class="container-xxl flex-grow-1 container-p-y">
						<h4 class="py-3 mb-4">Jogos Disponíveis</h4>

						<div class="row">
							<?php
							// Get list of available games from api/public directory
							$gamesDir = '../api/public/';
							$games = [];
							
							if (is_dir($gamesDir)) {
								$dirs = scandir($gamesDir);
								foreach ($dirs as $dir) {
									// Only get numeric directories (game IDs)
									if (is_numeric($dir) && is_dir($gamesDir . $dir)) {
										$gamePath = $gamesDir . $dir . '/index.html';
										if (file_exists($gamePath)) {
											$games[] = $dir;
										}
									}
								}
								// Sort games numerically
								sort($games, SORT_NUMERIC);
							}
							
							// Game name mapping (can be expanded)
							$gameNames = [
								'1543462' => 'Fortune Rabbit',
								'1738001' => 'Fortune Rabbit',
								'1695365' => 'Wild Bounty Rush',
								'1682240' => 'Gates of Olympus',
								'1655268' => 'Chained Souls',
								'1615454' => 'Dragon Hatch',
								'1601012' => 'Starlight Princess',
								'1594259' => 'Madame Destiny',
								'1580541' => 'Omega Alchemist',
								'1572362' => 'Candy Bonanza',
								'1568554' => 'Bomb Bonanza',
								'1529867' => 'Sweet Bonanza XMas',
								'1513328' => 'Floating Dragon',
								'1489936' => 'Firebird Spirit',
								'1473388' => 'Wisdom of Athena',
								'1448762' => 'Queens of Glory',
								'1432733' => 'Dark Vortex',
								'1420892' => 'PG Soft Game',
								'1418544' => 'PG Soft Game',
								'1402846' => 'PG Soft Game',
								'1397455' => 'PG Soft Game',
								'1381200' => 'PG Soft Game',
								'1372643' => 'PG Soft Game',
								'1368367' => 'PG Soft Game',
								'1340277' => 'PG Soft Game',
								'1338274' => 'PG Soft Game',
								'1312883' => 'PG Soft Game',
							];
							
							// Display games
							foreach ($games as $gameId):
								$gameName = isset($gameNames[$gameId]) ? $gameNames[$gameId] : 'Game ' . $gameId;
								$gameImage = 'assets/img/jogos/Fortunerabbit.gif'; // Default image
							?>
							<div class="col-sm-4 col-lg-2 mb-4">
								<div class="card game-card">
									<div class="game-card-wrapper">
										<img class="card-img-top" src="<?php echo $gameImage; ?>" alt="<?php echo htmlspecialchars($gameName); ?>">
										<a href="<?php echo htmlspecialchars($gamesOrigin . '/' . rawurlencode($gameId) . '/index.html', ENT_QUOTES, 'UTF-8'); ?>" class="game-launch-btn" target="_blank" rel="noopener">Jogar</a>
									</div>
									<div class="card-body">
										<h5 class="card-title game-title"><?php echo htmlspecialchars($gameName); ?></h5>
										<p class="card-text"><small class="text-muted">ID: <?php echo $gameId; ?></small></p>
									</div>
								</div>
							</div>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
				<!-- Footer -->
				<footer class="content-footer footer bg-footer-theme">
					<div class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
						<div class="mb-2 mb-md-0">
							©
							<script>
								document.write(new Date().getFullYear())
							</script>
							, Todos os direitos reservados
						</div>
					</div>
				</footer>
			</div>
			<!-- / Layout page -->
		</div>
	</div>
	<?php include "template/footer.php"; ?>
</body>

</html>
