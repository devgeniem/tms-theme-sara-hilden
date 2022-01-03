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

        add_filter( 'tms/theme/error404/search_link', [ $this, 'error404_search_link' ] );
        add_filter( 'tms/theme/error404/home_link', [ $this, 'error404_home_link' ] );
        add_filter( 'tms/acf/tab/error404/fields', [ $this, 'remove_404_alignment_setting' ] );
    }

    /**
     * Footer
     *
     * @param array $classes Footer classes.
     *
     * @return array
     */
    public function footer( array $classes ) : array {
        $classes['container']   = 'has-colors-light';
        $classes['back_to_top'] = 'is-primary';
        $classes['link']        = 'has-text-paragraph';
        $classes['link_icon']   = 'is-secondary';

        return $classes;
    }

    /**
     * 404 home link
     *
     * @param array $link Home link.
     *
     * @return array
     */
    public function error404_home_link( array $link ) : array {
        $link['classes'] = 'is-primary';
        $link['icon']    = 'arrow-right';
        $link['class']   = 'icon--medium';

        return $link;
    }

    /**
     * 404 search link
     *
     * @param array $link Search link.
     *
     * @return array
     */
    public function error404_search_link( array $link ) : array {
        $link['classes'] = 'is-primary';
        $link['class']   = 'icon--medium';

        return $link;
    }

    /**
     * Remove 404 alignment field
     *
     * @param array $fields Tab fields.
     *
     * @return array
     */
    public function remove_404_alignment_setting( array $fields ) : array {
        return array_filter( $fields, fn( $f ) => $f->get_name() !== '404_alignment' );
    }
}
