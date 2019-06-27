/**
 * Makes sure we have all the required levels on the Tribe Object
 *
 * @since TBD
 *
 * @type   {PlainObject}
 */
tribe.events = tribe.events || {};
tribe.events.views = tribe.events.views || {};

/**
 * Configures Events Bar Object in the Global Tribe variable
 *
 * @since TBD
 *
 * @type   {PlainObject}
 */
tribe.events.views.eventsBar = {};

/**
 * Initializes in a Strict env the code that manages the Event Views
 *
 * @since TBD
 *
 * @param  {PlainObject} $   jQuery
 * @param  {PlainObject} obj tribe.events.views.manager
 *
 * @return {void}
 */
( function( $, obj ) {
	'use strict';
	var $document = $( document );

	/**
	 * Selectors used for configuration and setup
	 *
	 * @since TBD
	 *
	 * @type {PlainObject}
	 */
	obj.selectors = {
		input: '[data-js="tribe-events-events-bar-input-control-input"]',
		inputWrapper: '[data-js="tribe-events-events-bar-input-control"]',
		inputKeywordWrapper: '.tribe-common-c-search__input-control--keyword',
		inputKeywordWrapperFocus: '.tribe-common-c-search__input-control--keyword-focus',
		inputLocationWrapper: '.tribe-common-c-search__input-control--location',
		inputLocationWrapperFocus: '.tribe-common-c-search__input-control--location-focus',
	};

	/**
	 * Toggles input class if it has a value
	 *
	 * @since TBD
	 *
	 * @param {Event} event event object of click event
	 *
	 * @return {void}
	 */
	obj.setInputFocusClass = function( event ) {
		var $input = event.data.target;
		var $wrapper = event.data.wrapper;

		// Set the focus class if it has content.
		$wrapper.toggleClass( event.data.inputClassFocus, '' !== $input.val().trim() );
	};

	/**
	 * Bind events for the events bar input, on focus and according to their value.
	 *
	 * @since TBD
	 *
	 * @param {jQuery} $container jQuery object of container.
	 * @param {String} inputWrapper selector of the input wrapper.
	 * @param {String} inputWrapperFocus the focus class we'd like to apply.
	 *
	 * @return {void}
	 */
	obj.bindEventsInputFocus = function( $container, inputWrapper, inputWrapperFocus ) {

		var $wrapper = $container.find( inputWrapper );
		var $input = $wrapper.find( obj.selectors.input );


		$wrapper.toggleClass( inputWrapperFocus.className(), '' !== $input.val().trim() );

		$input.on( 'change', { target: $input, wrapper: $wrapper, inputClassFocus: inputWrapperFocus.className() }, obj.setInputFocusClass );
	}

	/**
	 * Binds events for the events bar change listeners
	 *
	 * @since TBD
	 *
	 * @param {Event} event event object for 'afterSetup.tribeEvents' event
	 * @param {integer} index jQuery.each index param from 'afterSetup.tribeEvents' event
	 * @param {jQuery} $container jQuery object of view container
	 * @param {object} data data object passed from 'afterSetup.tribeEvents' event
	 *
	 * @return {void}
	 */
	obj.bindEvents = function( event, index, $container, data ) {
		// Bind event for the keyword input.
		obj.bindEventsInputFocus( $container, obj.selectors.inputKeywordWrapper, obj.selectors.inputKeywordWrapperFocus );

		// Bind event for the location input.
		obj.bindEventsInputFocus( $container, obj.selectors.inputLocationWrapper, obj.selectors.inputLocationWrapperFocus );
	};

	/**
	 * Handles the initialization of the accordions when Document is ready
	 *
	 * @since TBD
	 *
	 * @return {void}
	 */
	obj.ready = function() {
		$document.on( 'afterSetup.tribeEvents', tribe.events.views.manager.selectors.container, obj.bindEvents );

		/**
		 * @todo: do below for ajax events
		 */
		// on 'beforeAjaxBeforeSend.tribeEvents' event, remove all listeners
		// on 'afterAjaxError.tribeEvents', add all listeners
	};

	// Configure on document ready
	$document.ready( obj.ready );
} )( jQuery, tribe.events.views.eventsBar );
