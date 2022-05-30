<div class="wrap">
    <h1><?php
		echo esc_html( get_admin_page_title() ); ?></h1>
    <form action="options.php" method="post">
		<?php

		// output security fields for the registered setting "wcm_menu"
		settings_fields( 'wcm_menu' );
		// output setting sections and their fields
		// (sections are registered for "wcm_menu", each field is registered to a specific section)
		do_settings_sections( 'wcm_menu' );
		// output save settings button
		submit_button( __( 'Save Settings', 'textdomain' ) );
		?>
    </form>

    <div class="wrap">
        <p>Exempel 1</p>
    <form method="post" id="delete_trans_form_1">
        <!-- Lägg till en $nonce för security. -->
		<?php $nonce = wp_create_nonce( "wcm_repos_nonce" ); ?>
        <button id="del_trans" name="delete_transients"
                data-nonce="<?php echo $nonce; ?>">Delete Trans
        </button>
    </form>
    </div>

	<?php
	/**
	 *   Detta är exempel 2, där vi lagt action direkt på <form>
	 *  Vi lägger också till nonce som ett hidden input istället för data-attribut.
	 */
?>
    <div class="wrap">
        <p>Exempel 2</p>
    <form id="delete_trans_form_2" method="post" action="<?php echo admin_url( 'admin-ajax.php' ); ?>">

        <input type="hidden" name="nonce"
               value="<?php echo wp_create_nonce( "wcm_repos_nonce" ); ?>"/>
        <!-- eller med WordPress inbyggda metod-->
		<!-- <?php wp_nonce_field( 'nonce', 'wcm_repos_nonce' ); ?>-->

        <input type="hidden" name="action" value="wcm_del_repos_action" />

        <button id="del_trans" name="delete_transients">Delete Trans</button>
    </form>
    </div>

    <div class="wrap">
        <h2>Ladda repos igen</h2>
    <form method="post">
        <button name="get_repos">Load Repos</button>
    </form>
    </div>

    <div class="wrap">
	<?php
	if ( ! empty( $repos ) ) {
		echo '<ul id="repo_list">';
		foreach ( $repos as $repo ) {
			echo '<li><a href="' . $repo['url'] . '" target="_blank">' . $repo['name'] . '</a></li>';
		}
		echo '</ul>';
	}
	?>
    </div>
</div>
