<?php
/**
 * List Widget
 *
 * @since   5.2.1
 *
 * @package Tribe\Events\Views\V2\Widgets
 */

namespace Tribe\Events\Views\V2\Widgets;

use Tribe\Events\Views\V2\Assets;
use Tribe__Context as Context;

/**
 * Class for the List Widget.
 *
 * @since   5.2.1
 *
 * @package Tribe\Events\Views\V2\Widgets
 */
class Widget_List extends Widget_Abstract {
	/**
	 * {@inheritDoc}
	 */
	protected static $widget_in_use;

	/**
	 * {@inheritDoc}
	 *
	 * @var string
	 */
	protected static $widget_slug = 'events-list';

	/**
	 * {@inheritDoc}
	 *
	 * @var string
	 */
	protected $view_slug = 'widget-events-list';

	/**
	 * {@inheritDoc}
	 *
	 * @var string
	 */
	protected static $widget_css_group = 'events-list-widget';

	/**
	 * {@inheritDoc}
	 *
	 * @var array<string,mixed>
	 */
	protected $default_arguments = [
		// View options.
		'view'                 => null,
		'should_manage_url'    => false,

		// Event widget options.
		'id'                   => null,
		'alias-slugs'          => null,
		'title'                => '',
		'limit'                => 5,
		'no_upcoming_events'   => false,
		'featured_events_only' => false,
		'jsonld_enable'        => true,
		'tribe_is_list_widget' => true,
	];

	/**
	 * {@inheritDoc}
	 */
	public static function get_default_widget_name() {
		return esc_html_x( 'Events List', 'The name of the List Widget.', 'the-events-calendar' );
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_default_widget_options() {
		return [
			'description' => esc_html_x( 'A widget that displays upcoming events.', 'The description of the List Widget.', 'the-events-calendar' ),
		];
	}

	/**
	 * {@inheritDoc}
	 */
	public function setup_view( $_deprecated ) {
		parent::setup_view( $_deprecated );

		add_filter( 'tribe_customizer_should_print_widget_customizer_styles', '__return_true' );
		add_filter( 'tribe_customizer_inline_stylesheets', [ $this, 'add_full_stylesheet_to_customizer' ], 12, 2 );
	}

	/**
	 * {@inheritDoc}
	 */
	protected function setup_default_arguments() {
		parent::setup_default_arguments();

		// Setup default title.
		$this->default_arguments['title'] = _x( 'Upcoming Events', 'The default title of the List Widget.', 'the-events-calendar' );

		return $this->default_arguments;
	}

	/**
	 * {@inheritDoc}
	 */
	public function update( $new_instance, $old_instance ) {
		$updated_instance = $old_instance;

		/* Strip tags (if needed) and update the widget settings. */
		$updated_instance['title']                = wp_strip_all_tags( $new_instance['title'] );
		$updated_instance['limit']                = $new_instance['limit'];
		$updated_instance['no_upcoming_events']   = ! empty( $new_instance['no_upcoming_events'] );
		$updated_instance['featured_events_only'] = ! empty( $new_instance['featured_events_only'] );
		$updated_instance['jsonld_enable']        = ! empty( $new_instance['jsonld_enable'] );
		$updated_instance['tribe_is_list_widget'] = ! empty( $new_instance['tribe_is_list_widget'] );

		return $this->filter_updated_instance( $updated_instance, $new_instance );
	}

	/**
	 * {@inheritDoc}
	 */
	public function setup_admin_fields() {
		return [
			'title'                => [
				'label' => _x( 'Title:', 'The label for the field of the title of the List Widget.', 'the-events-calendar' ),
				'type'  => 'text',
			],
			'limit'                => [
				'label'   => _x( 'Show:', 'The label for the amount of events to show in the List Widget.', 'the-events-calendar' ),
				'type'    => 'number',
				'default' => $this->default_arguments['limit'],
				'min'     => 1,
				'max'     => 10,
				'step'    => 1,
			],
			'no_upcoming_events'   => [
				'label' => _x( 'Hide this widget if there are no upcoming events.', 'The label for the option to hide the List Widget if no upcoming events.', 'the-events-calendar' ),
				'type'  => 'checkbox',
			],
			'featured_events_only' => [
				'label' => _x( 'Limit to featured events only', 'The label for the option to only show featured events in the List Widget', 'the-events-calendar' ),
				'type'  => 'checkbox',
			],
			'jsonld_enable'        => [
				'label' => _x( 'Generate JSON-LD data', 'The label for the option to enable JSON-LD in the List Widget.', 'the-events-calendar' ),
				'type'  => 'checkbox',
			],
		];
	}

	/**
	 * {@inheritDoc}
	 */
	protected function args_to_context( array $arguments, Context $context ) {
		$alterations = parent::args_to_context( $arguments, $context );

		// Only Featured Events.
		$alterations['featured'] = tribe_is_truthy( $arguments['featured_events_only'] );

		// Enable JSON-LD?
		$alterations['jsonld_enable'] = (int) tribe_is_truthy( $arguments['jsonld_enable'] );

		// Hide widget if no events.
		$alterations['no_upcoming_events'] = tribe_is_truthy( $arguments['no_upcoming_events'] );

		// Add posts per page.
		$alterations['events_per_page'] = (int) isset( $arguments['limit'] ) && $arguments['limit'] > 0 ?
			(int) $arguments['limit'] :
			5;

		return $this->filter_args_to_context( $alterations, $arguments );
	}

	/**
	 * Add full events list widget stylesheets to customizer styles array to check.
	 *
	 * @since 5.3.0
	 *
	 * @param array<string> $sheets       Array of sheets to search for.
	 * @param string        $css_template String containing the inline css to add.
	 *
	 * @return array Modified array of sheets to search for.
	 */
	public function add_full_stylesheet_to_customizer( $sheets, $css_template ) {
		return array_merge( $sheets, [ 'tribe-events-widgets-v2-events-list-full' ] );
	}
}
