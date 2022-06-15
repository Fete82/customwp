<?php if (!defined('ABSPATH')) {
    exit;
}
wp_head();
/*
function wcp_refresh_request()
{
    //delete_transient('binance_data');

    $api_url = 'https://api.binance.com/api/v3/ticker/price';
    $json_data = file_get_contents($api_url);

    return set_transient('binance_data', $json_data, 60 * 60 * 3);
}

?>
<div>
    <form action="" method="POST">
        <?php
        if(isset($_POST['submit'])) {
            wcp_refresh_request();
        }
        ?>
        <button type="submit" name='submit'>Refresh</button>
    </form>
</div>
<?php

$response_data = json_decode(get_transient('binance_data'));

$data_one = $response_data[11];


echo ($data_one->symbol);?>:<br><?php
echo round(($data_one->price)) ?> USD<?;



    echo "<pre>";
    var_dump($response_data);
    echo "</pre>";



/*
    $user_data = array_slice($user_data, 0, 9);

    foreach ($user_data as $user) {

    }
    */
wp_footer();

?>
