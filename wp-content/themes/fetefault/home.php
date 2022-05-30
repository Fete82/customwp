<?php if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Template Name: Demo Page
 */

?>
	
<div id="layout" class="layout-page demo-page">

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemtype="https://schema.org/WebPage" itemscope="itemscope">

		<div class="entry-content" itemprop="text">

			<div class="tile-row">

				<?php // do our api calls
				$binance= wpgetapi_endpoint( 'binance', 'price', array('debug' => false) );
				?>

				<div class="demo-tile binance">
					<div class="internal">
						<h2>Bitcoin Price <span>Cached for 30 seconds</span></h2>
						<p>$<?php echo number_format( $binance['price'], 2 ); ?></p>
					</div>
				</div>


			</div>
		</div>
	</article>

</div>
