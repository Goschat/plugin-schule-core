<?php
/**
 * Blockstil „Slowenisch“: Die Redaktion wählt ihn in der Seitenleiste eines Absatzes,
 * einer Überschrift, Liste oder Gruppe; die Ausgabe bekommt dann lang="sl",
 * damit Screenreader die slowenische Aussprache verwenden (WCAG 3.1.2).
 *
 * @package SchulBank\Core
 */

namespace SchulBank\Core\Controller\Sprache;

use SchulBank\Core\Core\Controller;

defined( 'ABSPATH' ) || exit;

final class SlowenischMarkierung implements Controller {

	public const STIL   = 'sl';
	public const KLASSE = 'is-style-sl';

	private const BLOECKE = array( 'core/paragraph', 'core/heading', 'core/list', 'core/group', 'core/quote' );

	public function registriere(): void {
		add_action( 'init', array( $this, 'registriere_stil' ) );
		add_filter( 'render_block', array( $this, 'setze_sprache' ), 10, 2 );
	}

	public function registriere_stil(): void {
		foreach ( self::BLOECKE as $block ) {
			register_block_style(
				$block,
				array(
					'name'         => self::STIL,
					'label'        => 'Slowenisch · slovenščina',
					'inline_style' => '.editor-styles-wrapper .' . self::KLASSE . '{border-inline-start:3px dotted currentColor;padding-inline-start:.5em}',
				)
			);
		}
	}

	/**
	 * @param array<string, mixed> $block
	 */
	public function setze_sprache( string $html, array $block ): string {
		if ( ! in_array( $block['blockName'] ?? '', self::BLOECKE, true ) ) {
			return $html;
		}
		if ( ! str_contains( $block['attrs']['className'] ?? '', self::KLASSE ) ) {
			return $html;
		}

		$tags = new \WP_HTML_Tag_Processor( $html );
		if ( $tags->next_tag() ) {
			$tags->set_attribute( 'lang', 'sl' );
			$tags->remove_class( self::KLASSE );
		}
		return $tags->get_updated_html();
	}
}
