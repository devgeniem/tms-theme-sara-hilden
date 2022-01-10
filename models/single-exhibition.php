<?php
/**
 * Define the generic Page class.
 */

use DustPress\Query;

/**
 * The Page class.
 */
class SingleExhibition extends BaseModel {

    /**
     * Hooks
     */
    public function hooks() : void {
        add_filter( 'tms/theme/breadcrumbs/show_breadcrumbs_in_header', fn() => false );
    }

    /**
     * Content
     *
     * @return array|object|WP_Post|null
     * @throws Exception If global $post is not available or $id param is not defined.
     */
    public function content() {
        $single = Query::get_acf_post( get_queried_object_id() );

        $start_date = $single->fields['start_date'] ?? false;

        if ( ! empty( $start_date ) ) {
            $single->date = $start_date;

            if ( ! empty( $single->fields['end_date'] ) ) {
                $single->date .= ' - ' . $single->fields['end_date'];
            }
        }

        return $single;
    }

    /**
     * Have details?
     *
     * @return bool|void
     */
    public function has_details() {
        $meta_fields = [
            'date'          => [ 'icon' => 'icon-date' ],
            'opening_times' => [ 'icon' => 'icon-time' ],
            'location'      => [ 'icon' => 'icon-location' ],
        ];

        foreach ( $meta_fields as $meta ) {
            if ( ! empty( get_field( $meta ) ) ) {
                return true;
            }
        }
    }
}
