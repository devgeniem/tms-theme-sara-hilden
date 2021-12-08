<?php
/**
 * Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Sara_Hilden;

/**
 * Class TaxonomyController
 *
 * @package TMS\Theme\Sara_Hilden
 */
class TaxonomyController extends \TMS\Theme\Base\TaxonomyController implements \TMS\Theme\Base\Interfaces\Controller {

    /**
     * Get namespace for taxonomy instances
     *
     * @return string
     */
    protected function get_namespace() : string {
        return __NAMESPACE__;
    }

    /**
     * Get custom post type files
     *
     * @return array
     */
    protected function get_files() : array {
        return array_diff( scandir( __DIR__ . '/Taxonomy' ), [ '.', '..' ] );
    }
}
