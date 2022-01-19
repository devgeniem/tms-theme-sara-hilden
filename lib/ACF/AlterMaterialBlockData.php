<?php
/**
 *  Copyright (c) 2021. Geniem Oy
 */

use TMS\Theme\Base\Logger;

/**
 * Alter Material block
 */
class AlterMaterialBlockData {

    /**
     * Constructor
     */
    public function __construct() {
        add_filter(
            'tms/acf/block/material/data',
            [ $this, 'alter_data' ],
            10,
            2
        );
        add_filter(
            'tms/plugin-materials/page_materials/material_page_item_button_classes',
            [ $this, 'alter_button_classes' ],
            10,
            2
        );
    }

    /**
     * Alter data
     *
     * @param array $data Array of ACF data.
     *
     * @return array
     */
    public function alter_data( array $data ) : array {
        try {
            $data['button_classes'] = 'is-primary is-outlined is-inverted has-text-weight-semibold';
        }
        catch ( Exception $e ) {
            ( new Logger() )->error( $e->getMessage(), $e->getTrace() );
        }
        return $data;
    }

    /**
     * Alter data
     *
     * @return string
     */
    public function alter_button_classes() {
        return 'is-primary is-outlined is-inverted has-text-weight-semibold';
    }

}

( new AlterMaterialBlockData() );
