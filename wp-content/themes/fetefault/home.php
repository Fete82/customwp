<?php if (!defined('ABSPATH')) {
    exit;
}

/**
 * Template Name: Demo Page
 */

?>

<div id="layout" class="layout-page demo-page">

    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemtype="https://schema.org/WebPage" itemscope="itemscope">

        <div class="entry-content" itemprop="text">

            <div class="tile-row">

                <?php // do our api calls
                $binance = wpgetapi_endpoint('binance', 'price', array('debug' => false));
                ?>

                <div class="demo-tile binance">
                    <div class="internal">
                        <h2>Bitcoin Price
                            <!-- <span>Cached for 30 seconds</span>-->
                        </h2>
                        <p>$<?php echo number_format($binance['price'], 2); ?></p>
                    </div>
                </div>


            </div>
        </div>
    </article>

</div>



<?php
function wcp_refresh_request()
{
    delete_transient('binance_data');

    $api_url = 'https://api.binance.com/api/v3/ticker/price';
    $json_data = file_get_contents($api_url);

    return set_transient('binance_data', $json_data, 60 * 60 * 3);
}

?>
<div>
    <form action="" method="POST">
        <?php
        wcp_refresh_request();
        ?>
        <button type="submit">Refresh</button>
    </form>
</div>
<?php

$response_data = json_decode(get_transient('binance_data'));

$data_one = $response_data[0];

echo ($data_one->price);

/*
    echo "<pre>";
    var_dump($response_data);
    echo "</pre>";
    */


/*
    $user_data = array_slice($user_data, 0, 9);

    foreach ($user_data as $user) {

    }
    */
?>
