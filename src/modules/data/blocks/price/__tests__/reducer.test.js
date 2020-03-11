/**
 * Internal dependencies
 */
import { actions } from '@moderntribe/events/data/blocks/price';
import reducer, { DEFAULT_STATE, setInitialState } from '@moderntribe/events/data/blocks/price/reducer';

const entityRecord = {
	meta: {
		_EventCurrencyPosition: 'prefix',
		_EventCurrencySymbol: '€',
		_EventCost: '15',
	},
};

describe( '[STORE] - Price reducer', () => {
	it( 'Should return the default state', () => {
		expect( reducer( undefined, {} ) ).toEqual( DEFAULT_STATE );
	} );

	it( 'Should set the cost', () => {
		expect( reducer( DEFAULT_STATE, actions.setCost( 10 ) ) ).toMatchSnapshot();
	} );

	it( 'Should set the symbol position', () => {
		expect( reducer( DEFAULT_STATE, actions.setPosition( 'prefix' ) ) ).toMatchSnapshot();
	} );

	it( 'Should set the cost symbol', () => {
		expect( reducer( DEFAULT_STATE, actions.setSymbol( '€' ) ) ).toMatchSnapshot();
	} );

	it( 'Should set the initial state', () => {
		setInitialState( entityRecord );
		expect( DEFAULT_STATE ).toMatchSnapshot();
	} );
} );
