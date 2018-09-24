<?php


/**
 * Class Tribe__Events__Google__Maps_API_Key
 *
 * Handles support for the Google Maps API key.
 */
class Tribe__Events__Google__Maps_API_Key {

	/**
	 * @var string
	 */
	public static $api_key_option_name = 'google_maps_js_api_key';

	/**
	 * The Events Calendar's default Google Maps API Key, which supports the Basic Embed API.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public static $default_api_key = 'AIzaSyDkIctLJYEb6oYMOq5elQ-oGEh05ybHwSU';

	/**
	 * @var static
	 */
	protected static $instance;

	/**
	 * The class singleton constructor.
	 *
	 * @return Tribe__Events__Google__Maps_API_Key
	 */
	public static function instance() {
		if ( empty( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Adds Google Maps API key fields to the addon fields.
	 *
	 * @param array $addon_fields
	 *
	 * @return array
	 */
	public function filter_tribe_addons_tab_fields( array $addon_fields ) {
		$gmaps_api_fields = array(
			'gmaps-js-api-start' => array(
				'type' => 'html',
				'html' => '<h3>' . esc_html__( 'Google Maps API', 'the-events-calendar' ) . '</h3>',
			),

			'gmaps-js-api-info-box' => array(
				'type' => 'html',
				'html' => '<p>' . sprintf(
					__(
						'The Events Calendar comes with an API key for basic maps functionality. If you’d like to use more advanced features like custom map pins or dynamic map loads, you’ll need to get your own %1$s. %2$s.',
						'the-events-calendar'
					),
					'<a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank" rel="noopener noreferrer">' . esc_html__( 'Google Maps API key', 'the-events-calendar' ) . '</a>',
					'<a href="https://theeventscalendar.com/knowledgebase/setting-up-your-google-maps-api-key/" target="_blank" rel="noopener noreferrer">' . esc_html__( 'Read More', 'the-events-calendar' ) . '</a>'
				) . '</p>',
			),

			self::$api_key_option_name => array(
				'type'            => 'text',
				'label'           => esc_html__( 'Google Maps API key', 'the-events-calendar' ),
				'tooltip'         => sprintf( __( '<p>%s to create your Google Maps API key.', 'the-events-calendar' ),
					'<a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank"></p>' . __( 'Click here', 'the-events-calendar' ) . '</a>' ),
				'size'            => 'medium',
				'validation_type' => 'alpha_numeric_with_dashes_and_underscores',
				'can_be_empty'    => true,
				'parent_option'   => Tribe__Events__Main::OPTIONNAME,
			),
		);

		return array_merge( (array) $addon_fields, $gmaps_api_fields );
	}

	/**
	 * Adds the browser key api key to the Google Maps JavaScript API url if set by the user.
	 *
	 * @param string $js_maps_api_url
	 *
	 * @return string
	 */
	public function filter_tribe_events_google_maps_api( $js_maps_api_url ) {
		$key = tribe_get_option( self::$api_key_option_name, self::$default_api_key );

		if ( ! empty( $key ) ) {
			$js_maps_api_url = add_query_arg( 'key', $key, $js_maps_api_url );
		}

		return $js_maps_api_url;
	}

	public function filter_tribe_events_pro_google_maps_api( $js_maps_api_url ) {

	}

	/**
	 * Ensures the Google Maps API Key field in Settings > APIs is always populated with TEC's
	 * default API key if no user-supplied key is present.
	 *
	 * @since TBD
	 *
	 * @param string $value_string The original HTML string for the input's value attribute.
	 * @param string $value The literal value of the field itself; falls back to the option name if no value present.
	 * @return string The default license key as the input's new value.
	 */
	public function populate_field_with_default_api_key( $value_string, $value ) {

		if ( ! isset( $value ) || self::$api_key_option_name !== $value ) {
			return $value_string;
		}

		if ( empty( $value_string ) ) {
			remove_filter( 'tribe_field_value', array( $this, 'populate_field_with_default_api_key' ), 10, 2 );

			$value_string = self::$default_api_key;

			tribe_update_option( self::$api_key_option_name, self::$default_api_key );

			add_filter( 'tribe_field_value', array( $this, 'populate_field_with_default_api_key' ), 10, 2 );
		}

		return $value_string;
	}
}