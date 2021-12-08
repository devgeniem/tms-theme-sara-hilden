<?php
/**
 *  Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Sara_Hilden;

use TMS\Theme\Sara_Hilden\Taxonomy\ArtistCategory;
use TMS\Theme\Sara_Hilden\Taxonomy\ArtworkLocation;
use TMS\Theme\Sara_Hilden\Taxonomy\ArtworkType;

/**
 * Class Localization
 *
 * @package TMS\Theme\Sara_Hilden
 */
class Localization extends \TMS\Theme\Base\Localization implements \TMS\Theme\Base\Interfaces\Controller {

    /**
     * Load theme translations.
     */
    public function load_theme_textdomains() {
        \load_theme_textdomain(
            'tms-theme-base',
            get_template_directory() . '/lang'
        );

        \load_child_theme_textdomain(
            'tms-theme-sara_hilden',
            get_stylesheet_directory() . '/lang'
        );
    }
}
