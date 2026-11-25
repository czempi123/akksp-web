<?php
/**
 * Vyhledávací formulář tématu (použije se, když Ivory Search není aktivní).
 *
 * @package AKKSP
 */

defined( 'ABSPATH' ) || exit;

$akksp_sid = wp_unique_id( 'ak-s-' );
?>
<form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label for="<?php echo esc_attr( $akksp_sid ); ?>"><span class="ak-sr"><?php esc_html_e( 'Hledat na webu', 'akksp' ); ?></span>
		<input type="search" id="<?php echo esc_attr( $akksp_sid ); ?>" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php esc_attr_e( 'Např. smlouva, pohledávky, Polsko', 'akksp' ); ?>" autocomplete="off">
	</label>
	<button type="submit" aria-label="<?php esc_attr_e( 'Hledat', 'akksp' ); ?>"><?php echo akksp_icon( 'search' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></button>
</form>
