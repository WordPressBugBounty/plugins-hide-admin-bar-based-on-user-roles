<?php
/**
 * A grid of items to hide: one row per item, one column per role.
 *
 * Expects $hab_panel_section (settings section), $hab_panel_items (list of array( 'id', 'label',
 * 'children' )), $hab_panel_hidden (role => hidden item IDs), $hab_grid_label (heading of the
 * item column) and $roles from the settings page. Every role's choices are visible at once.
 *
 * @link       https://wpankit.com/
 * @since      7.5.0
 *
 * @package    hab_Hide_Admin_Bar_Based_On_User_Roles
 * @subpackage hab_Hide_Admin_Bar_Based_On_User_Roles/modules/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Submenu items follow their menu, as their own rows.
$hab_grid_rows = array();
foreach ( $hab_panel_items as $hab_item ) {
	$hab_grid_rows[] = array(
		'id'    => $hab_item['id'],
		'label' => $hab_item['label'],
		'child' => false,
	);
	if ( ! empty( $hab_item['children'] ) ) {
		foreach ( $hab_item['children'] as $hab_child ) {
			$hab_grid_rows[] = array(
				'id'    => $hab_child['id'],
				'label' => $hab_child['label'],
				'child' => true,
			);
		}
	}
}

$hab_role_labels = array();
foreach ( $roles as $hab_role_key => $hab_role_name ) {
	$hab_role_labels[ $hab_role_key ] = translate_user_role( $hab_role_name );
}

ob_start();
?>
<tr>
	<th scope="col" class="hab-grid-item"><?php echo esc_html( $hab_grid_label ); ?></th>
	<?php foreach ( $hab_role_labels as $hab_role_label ) : ?>
		<th scope="col" class="hab-grid-role"><?php echo esc_html( $hab_role_label ); ?></th>
	<?php endforeach; ?>
</tr>
<?php
$hab_grid_header = ob_get_clean();
?>
<div class="hab-grid-wrap">
	<table class="hab-grid">
		<thead><?php echo $hab_grid_header; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped above. ?></thead>
		<tbody>
			<?php foreach ( $hab_grid_rows as $hab_row ) : ?>
				<tr class="<?php echo $hab_row['child'] ? 'is-child' : 'is-parent'; ?>">
					<th scope="row"><?php echo esc_html( $hab_row['label'] ); ?></th>
					<?php foreach ( $hab_role_labels as $hab_role_key => $hab_role_label ) : ?>
						<?php
						/* translators: 1: item name, 2: role name. */
						$hab_cell_label = sprintf( __( 'Hide %1$s for %2$s', 'hide-admin-bar-based-on-user-roles' ), $hab_row['label'], $hab_role_label );
						?>
						<td>
							<input type="checkbox" name="<?php echo esc_attr( 'hab_modules[' . $hab_panel_section . '][hidden][' . $hab_role_key . '][]' ); ?>" value="<?php echo esc_attr( $hab_row['id'] ); ?>" aria-label="<?php echo esc_attr( $hab_cell_label ); ?>" title="<?php echo esc_attr( $hab_cell_label ); ?>" <?php checked( ! empty( $hab_panel_hidden[ $hab_role_key ] ) && in_array( $hab_row['id'], $hab_panel_hidden[ $hab_role_key ], true ) ); ?>>
						</td>
					<?php endforeach; ?>
				</tr>
			<?php endforeach; ?>
		</tbody>
		<?php if ( count( $hab_grid_rows ) > 12 ) : ?>
			<tfoot><?php echo $hab_grid_header; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped above. ?></tfoot>
		<?php endif; ?>
	</table>
</div>
