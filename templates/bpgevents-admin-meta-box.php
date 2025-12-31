<?php
if ( ! defined( 'ABSPATH' ) ) exit;

wp_nonce_field( 'bpge_event_save', 'bpge_event_nonce' );
?>

<table class="form-table bpge-event-meta">
    <tr>
        <th><label for="bpge_virtual"><?php _e( 'Virtual Event', 'bpgevents' ); ?></label></th>
        <td>
            <label>
                <input type="checkbox" id="bpge_virtual" name="bpge_virtual" <?php checked( $is_virtual ); ?>>
                <?php _e( 'This is a virtual event', 'bpgevents' ); ?>
            </label>
        </td>
    </tr>

    <tr>
        <th><label for="bpge_city"><?php _e( 'City', 'bpgevents' ); ?></label></th>
        <td>
            <input type="text" id="bpge_city" name="bpge_city" class="regular-text"
                   value="<?php echo esc_attr( $city ); ?>">
        </td>
    </tr>

    <tr>
        <th><label for="bpge_address"><?php _e( 'Address', 'bpgevents' ); ?></label></th>
        <td>
            <input type="text" id="bpge_address" name="bpge_address" class="regular-text"
                   value="<?php echo esc_attr( $address ); ?>">
        </td>
    </tr>

    <tr>
        <th><label for="bpge_province"><?php _e( 'Province/Region', 'bpgevents' ); ?></label></th>
        <td>
            <input type="text" id="bpge_province" name="bpge_province" class="regular-text"
                   value="<?php echo esc_attr( $province ); ?>">
        </td>
    </tr>

    <tr>
        <th><label for="bpge_country"><?php _e( 'Country', 'bpgevents' ); ?></label></th>
        <td>
            <input type="text" id="bpge_country" name="bpge_country" class="regular-text"
                   value="<?php echo esc_attr( $country ); ?>">
        </td>
    </tr>

    <tr>
        <th><label for="bpge_virtual_url"><?php _e( 'Virtual Event URL', 'bpgevents' ); ?></label></th>
        <td>
            <input type="url" id="bpge_virtual_url" name="bpge_virtual_url" class="regular-text"
                   value="<?php echo esc_attr( $url ); ?>">
        </td>
    </tr>

    <tr>
        <th><?php _e( 'Coordinates', 'bpgevents' ); ?></th>
        <td>
            <label>
                <?php _e( 'Latitude', 'bpgevents' ); ?>:
                <input type="text" name="bpge_lat" value="<?php echo esc_attr( $lat ); ?>" size="10">
            </label>
            <br>
            <label>
                <?php _e( 'Longitude', 'bpgevents' ); ?>:
                <input type="text" name="bpge_lng" value="<?php echo esc_attr( $lng ); ?>" size="10">
            </label>
            <p class="description">
                <?php _e( 'You can fill coordinates manually or via future auto-geocoding.', 'bpgevents' ); ?>
            </p>
        </td>
    </tr>
</table>
