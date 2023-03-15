<?php
include("config.php");
include("includes/classes/SiteResultsProvider.php");
include("includes/classes/ImageResultsProvider.php");


$term = isset($_GET["term"]) ? $_GET["term"] : "You must enter a search term";
$type = isset($_GET["type"]) ? $_GET["type"] : "sites";
$page = isset($_GET["page"]) ? $_GET["page"] : 1;


?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Explorer</title>
	<!-- Fancybox CSS Link -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.css" />
	<!--CSS Link -->
	<link rel="stylesheet" type="text/css" href="assets/css/style.css">
	<!--Import the jQuery File to ajax function -->
	<script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
</head>
<body>

	<div class="wrapper">

		<!-- Header Section -->
		<div class="header">
			<div class="headerContent">

				<div class="logoContainer">
					<a href="index.php">
						<img src="assets/images/logo/logo.png">
					</a>
				</div>

				<div class="searchContainer">
					<form action="search.php" method="GET">
						<div class="searchBarContainer">
							<input type="hidden" name="type" value="<?php echo $type?>">
							<input class="searchBox" type="text" name="term" value="<?php echo $term;?>">
							<button class="searchButton">
								<img src="assets/images/icons/search.png">
							</button>
						</div>
					</form>
				</div>
			</div>

			<div class="tabsContainer">

				<ul class="tabList">

					<li class="<?php echo $type == 'sites' ? 'active' : '' ?>">
						<a href='<?php echo "search.php?term=$term&type=sites"; ?>'>
							Sites
						</a>
					</li>

					<li class="<?php echo $type == 'images' ? 'active' : '' ?>">
						<a href='<?php echo "search.php?term=$term&type=images"; ?>'>
							Images
						</a>
					</li>
				</ul>
			</div>
		</div>

		<!-- Show Results Query Section -->
		<div class="mainResultsSection">

			<?php
				if($type == "sites") {
					$resultsProvider = new SiteResultsProvider($con);
					$pageSize = 20;
				}
				else {
					$resultsProvider = new ImageResultsProvider($con);
					$pageSize = 30;
				}

				$numResults = $resultsProvider->getNumResults($term);

				echo "<p class='resultsCount'>$numResults Resultados Encontrados</p>";



				echo $resultsProvider->getResultsHtml($page, $pageSize, $term);
			?>
		</div>

		<!-- Pagination Section -->
		<div class="paginationSection">
			<div class="showPages">
				<?php


					$pagesToShow = 20; // numbers of links founded that we have to show foreach page
					$numPages = ceil($numResults/$pagesToShow); // the count that we have to do is divide the numbers of links funded to number of links to show foreach page | the 'ceil' function will round up the number of this count
					$pagesLeft = min($pagesToShow, $numPages);  // will return the minimum value of the result of $numPages variable
					$currentPage = $page - floor( $pagesToShow / 2); // our current page show the page that we are and calculate the way between the end results and the start | the 'floor' function will round down the number of this count

					if($currentPage < 1){
						$currentPage = 1;
					}

					if($currentPage + $pagesLeft > $numPages + 1){
						$currentPage = $numPages + 1 - $pagesLeft;
					}

					while($pagesLeft != 0 && $currentPage <= $numPages){


						if($currentPage == $page ){
							echo "
								<div class='numbersPages'>
									<button class='currentButtonPage'>
										$currentPage
									</button>
								</div>
							";
						} else {
							echo "
								<div class='numbersPages'>
									<a href='search.php?term=$term&type=$type&page=$currentPage'>
										<button class='buttonPages'>
											$currentPage
										</button>
									</a>
								</div>
							";
						}

						
						$currentPage = $currentPage + 1;
						$pagesLeft = $pagesLeft - 1;
					}
				?>
			</div>
		</div>
	</div>		
	<!-- JQuery Fancybox Library Link -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.js"></script>
	<!-- JQuery Masonry Library Link -->
	<script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.min.js"></script>
	<script type="text/javascript" src="assets/js/script.js"></script>
</body>
</html> 