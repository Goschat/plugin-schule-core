/**
 * Editor-Ansicht des Blocks „Nächste Termine“. Ohne Build-Schritt: reines JavaScript
 * mit den globalen wp.*-Paketen. Die eigentliche Ausgabe rendert der Server.
 */
( function ( wp ) {
	const el = wp.element.createElement;
	const { InspectorControls, useBlockProps } = wp.blockEditor;
	const { PanelBody, RangeControl, TextControl } = wp.components;
	const ServerSideRender = wp.serverSideRender;

	wp.blocks.registerBlockType( 'sbd/termine', {
		edit: function ( props ) {
			const { attributes, setAttributes } = props;

			return el(
				'div',
				useBlockProps(),
				el(
					InspectorControls,
					null,
					el(
						PanelBody,
						{ title: 'Einstellungen' },
						el( RangeControl, {
							label: 'Anzahl Termine',
							min: 1,
							max: 12,
							value: attributes.anzahl,
							onChange: ( wert ) => setAttributes( { anzahl: wert } ),
						} ),
						el( TextControl, {
							label: 'Text, wenn keine Termine eingetragen sind',
							help: 'Leer lassen für „Derzeit sind keine Termine eingetragen.“',
							value: attributes.leertext,
							onChange: ( wert ) => setAttributes( { leertext: wert } ),
						} )
					)
				),
				el( ServerSideRender, { block: 'sbd/termine', attributes: attributes } )
			);
		},
		save: function () {
			return null;
		},
	} );
} )( window.wp );
