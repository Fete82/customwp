<?php

if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Handles custom field typea.
 */
class WpGetApi_Parameter_Field extends CMB2_Type_Base {


	public static function init_parameter() {
		add_filter( 'cmb2_render_class_parameter', array( __CLASS__, 'class_name' ) );
		add_filter( 'cmb2_sanitize_parameter', array( __CLASS__, 'maybe_save_split_values' ), 12, 4 );
		/**
		 * The following snippets are required for allowing the users field
		 * to work as a repeatable field, or in a repeatable group
		 */
		add_filter( 'cmb2_sanitize_parameter', array( __CLASS__, 'sanitize' ), 10, 5 );
		add_filter( 'cmb2_types_esc_parameter', array( __CLASS__, 'escape' ), 10, 4 );
	}

	public static function class_name() { return __CLASS__; }
	

	/**
	 * Handles outputting the users field.
	 */
	public function render() {

		// make sure we assign each part of the value we need.
		$value = wp_parse_args( $this->field->escaped_value(), array(
			'name' 	=> '',
			'value' => '',
		) );

		ob_start();
		// Do html
		?>

		<div class="name input-wrap">
			<label for="<?php esc_attr_e( $this->_id( '_name' ) ); ?>">
				<?php esc_html_e( $this->_text( 'parameter_name_text', __( 'Name', 'wpgetapi' ) ) ); ?>
			</label>

			<?php echo $this->types->input( array(
				'name'  => $this->_name( '[name]' ),
				'id'    => $this->_id( '_name' ),
				'value' => $value['name'],
				'desc'  => '',
				'placeholder'  => __( 'Name of the parameter', 'wpgetapi' ),
			) ); ?>
		</div><!--
		
		--><div class="value input-wrap">
			<label for="<?php esc_attr_e( $this->_id( '_value' ) ); ?>">
				<?php esc_html_e( $this->_text( 'parameter_value_text', __( 'Value', 'wpgetapi' ) ) ); ?>
			</label>

			<?php echo $this->types->textarea( array(
				'name'  => $this->_name( '[value]' ),
				'id'    => $this->_id( '_value' ),
				'value' => stripslashes($value['value']),
				'desc'  => '',
				'rows'  => 2,
				'placeholder'  => __( 'Value of the parameter', 'wpgetapi' ),
			) ); ?>
		</div>
		
		<?php

		// grab the data from the output buffer.
		return $this->rendered( ob_get_clean() );
	}


	/**
	 * Optionally save the values into separate fields
	 */
	public static function maybe_save_split_values( $override_value, $value, $object_id, $field_args ) {

		// Don't do the override
		if ( ! isset( $field_args['split_values'] ) || ! $field_args['split_values'] )
			return $override_value;
	
		$encryption = new WpGetApi_Encryption();

		$parameter_keys = array( 'name', 'value' );
		foreach ( $parameter_keys as $key ) {
			if ( ! empty( $value[ $key ] ) ) {
				$updated_value = $encryption->encrypt( $value[ $key ] );
				update_post_meta( $object_id, $field_args['id'] . 'parameter_'. $key, $updated_value );
			}
		}

		remove_filter( 'cmb2_sanitize_parameter', array( __CLASS__, 'sanitize' ), 10, 5 );
		// Tell CMB2 we already did the update
		return true;
	}


	public static function sanitize( $check, $meta_value, $object_id, $field_args, $sanitize_object ) {
		
		// if not repeatable, bail out.
		if ( ! is_array( $meta_value ) || ! $field_args['repeatable'] )
			return $check;

		$encryption = new WpGetApi_Encryption();

		foreach ( $meta_value as $i => $param ) {

			// if both empty, unset and continue
			if( $param['name'] == '' && $param['value'] == '' ) {
				unset( $meta_value[ $i ] );
				continue;
			}

			foreach ( $param as $key => $val ) {
				$meta_value[ $i ][ $key ] = $key === 'name' ? $val : $encryption->encrypt( $val );
			}

		}
		
		return array_filter($meta_value);
	}


	public static function escape( $check, $meta_value, $field_args, $field_object ) {
		
		// if not repeatable, bail out.
		if ( ! is_array( $meta_value ) || ! $field_args['repeatable'] )
			return $check;

		$encryption = new WpGetApi_Encryption();

		foreach ( $meta_value as $i => $param ) {

			// if both empty, unset and continue
			if( $param['name'] == '' && $param['value'] == '' ) {
				unset( $meta_value[ $i ] );
				continue;
			}

			foreach ( $param as $key => $val ) {
				$meta_value[ $i ][ $key ] = $key === 'name' ? $val : $encryption->decrypt( $val );
			}

		}

		return array_filter($meta_value);
	}


}