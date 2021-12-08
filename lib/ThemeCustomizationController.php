<?php
/**
 * Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Sara_Hilden;

use WP_post;

/**
 * Class ThemeCustomizationController
 *
 * @package TMS\Theme\Base
 */
class ThemeCustomizationController implements \TMS\Theme\Base\Interfaces\Controller {

    /**
     * Add hooks and filters from this controller
     *
     * @return void
     */
    public function hooks() : void {
        add_filter(
            'tms/single/related_display_categories',
            '__return_false',
        );

        add_filter( 'tms/theme/footer/colors', [ $this, 'footer' ] );
    }

    public function footer( array $classes ) : array {
        $classes['container']   = 'has-colors-light';
        $classes['back_to_top'] = 'is-primary';
        $classes['link']        = 'has-text-paragraph';
        $classes['link_icon']   = 'is-secondary';

        return $classes;
    }

}
