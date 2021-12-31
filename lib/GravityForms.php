<?php
/**
 *  Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Sara_Hilden;

use TMS\Theme\Base\Interfaces\Controller;

/**
 * Class GravityForms
 *
 * @package TMS\Theme\Base
 */
class GravityForms implements Controller {

    /**
     * Hooks
     */
    public function hooks() : void {
        add_filter( 'gform_file_upload_markup', function( $file_upload_markup, $file_info, $form_id, $field_id ) {
            // do stuff

            return '$result';
        }, 10, 4 );
    }
}
