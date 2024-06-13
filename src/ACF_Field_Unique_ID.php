<?php

namespace PhilipNewcomer\ACF_Unique_ID_Field;

use acf_field;

class ACF_Field_Unique_ID extends acf_field {

	/**
	 * Initialize the class.
	 */
	public static function init() {
		add_action(
			'acf/include_field_types',
			function() {
				if ( ! class_exists( 'acf_field' ) ) {
					return;
				}

				new static();
			}
		);
	}

	/**
	 * Initialize the field.
	 */
	public function __construct() {
		$this->name     = 'unique_id';
		$this->label    = 'Unique ID';
		$this->category = 'basic';

		parent::__construct();
	}

	/**
	 * Render the HTML field.
	 *
	 * @param array $field The field data.
	 */
	public function render_field( $field ) {
		printf(
			'<input type="text" name="%s" value="%s" readonly>',
			esc_attr( $field['name'] ),
			esc_attr( $field['value'] )
		);
	}

	/**
	 * Define the unique ID if one does not already exist.
	 *
	 * @param string $value   The field value.
	 * @param int    $post_id The post ID.
	 * @param array  $field   The field data.
	 *
	 * @return string The filtered value.
	 */
	public function update_value( $value, $post_id, $field ) {

		if ( ! empty( $value ) ) {
			return $value;
		}

		return uuidv4();
	}

	/**
         * Function to create a unique UUID
	 */
	function uuidv4() {
		$data = random_bytes(16);
		
		$data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
		$data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
		
		return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
	}
}
