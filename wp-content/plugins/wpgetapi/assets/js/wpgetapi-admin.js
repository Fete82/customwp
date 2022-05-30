window.WpGetApi = window.WpGetApi || {};

(function (window, document, $, wpgetapi, undefined) {

    'use strict';

    /**
     * Start the JS.
     */
    wpgetapi.onReady = function () {

        var $page = wpgetapi.page();

        wpgetapi.functionsHelp();
        
        $page
            .on( 'change keyup', '.field-id input', wpgetapi.functionsHelp )
            .on( 'cmb2_remove_row cmb2_add_row cmb2_shift_row', wpgetapi.functionsHelp );

    }

    /**
     * Adds the endpoint into the functions help section.
     */
    wpgetapi.functionsHelp = function ( e ) {
        
        // change, keyup, add_row etc 
        if( e && e.type.length > 0 ) {

            var $this = $( this );
            var $group = $this.parents( '.cmb-repeatable-grouping' );

            // if we are adding a group, clear it
            if( e.type == 'cmb2_add_row' ) {
                $group = $( '.cmb-repeatable-grouping' ).last();
                $group.find( '.functions .endpoint_id' ).html( '' );
            }

            if( $group.length > 0 )
                $group.find( '.functions .endpoint_id' ).html( $this.val() );

        } else {

            $('.cmb-repeatable-grouping').each(function( index, value ) {
                
                var $group = $( this );
                var endpoint_value = $group.find( '.field-id input' ).val();
                $group.find( '.functions .endpoint_id' ).html( endpoint_value );

            });

        }

    }

	/**
     * Gets jQuery object containing all . Caches the result.
     *
     * @since  1.0.0
     *
     * @return {Object} jQuery object containing all.
     */
    wpgetapi.page = function() {
        if ( wpgetapi.$page ) {
            return wpgetapi.$page;
        }
        wpgetapi.$page = $('.wpgetapi');
        return wpgetapi.$page;
    };

    $( wpgetapi.onReady );

}(window, document, jQuery, window.WpGetApi));