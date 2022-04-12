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

        add_filter( 'tms/theme/header/colors', [ $this, 'header' ] );
        add_filter( 'tms/theme/footer/colors', [ $this, 'footer' ] );

        add_filter( 'tms/theme/search/search_item', [ $this, 'event_search_classes' ] );
        add_filter( 'tms/theme/event/group_title', [ $this, 'event_info_group_title_classes' ] );
        add_filter( 'tms/theme/event/hero_icon_classes', fn() => '' );
        add_filter( 'tms/theme/event/info_group_classes', fn() => '' );

        add_filter( 'tms/theme/single_blog/classes', [ $this, 'single_blog_classes' ] );
        add_filter( 'comment_form_submit_button', [ $this, 'comments_submit' ], 15, 0 );

        add_filter( 'tms/theme/error404/search_link', [ $this, 'error404_search_link' ] );
        add_filter( 'tms/theme/error404/home_link', [ $this, 'error404_home_link' ] );
        add_filter( 'tms/acf/tab/error404/fields', [ $this, 'remove_404_alignment_setting' ] );

        add_filter( 'tms/theme/remove_custom_links', [ $this, 'remove_custom_links' ] );
    }

    /**
     * Header
     *
     * @param array $colors Color classes.
     *
     * @return array Array of customized colors.
     */
    public function header( $colors ) : array {
        $colors['nav']['container']            = 'has-background-primary-invert has-border-secondary has-border-top-1 has-border-bottom-1'; // phpcs:ignore
        $colors['search_popup_container']      = 'has-background-primary-invert has-text-primary';
        $colors['lang_nav']['link__default']   = 'has-text-primary';
        $colors['lang_nav']['link__active']    = 'has-background-primary has-text-primary-invert';
        $colors['lang_nav']['dropdown_toggle'] = 'is-primary';
        $colors['fly_out_nav']['inner']        = 'has-background-light has-text-primary';

        return $colors;
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

    /**
     * Override blog view classes.
     *
     * @param array $classes Classes.
     *
     * @return array
     */
    public function event_info_group_title_classes( $classes ) : array {
        $classes['title'] = '';
        $classes['icon']  = '';

        return $classes;
    }

    /**
     * Override event item classes.
     *
     * @param array $classes Classes.
     *
     * @return array
     */
    public function event_search_classes( $classes ) : array {
        $classes['search_form'] = 'has-background-light';

        return $classes;
    }

    /**
     * Override event item classes.
     *
     * @param array $classes Classes.
     *
     * @return array
     */
    public function single_blog_classes( $classes ) : array {
        $classes['info_section']         = '';
        $classes['info_section_authors'] = '';
        $classes['info_section_button']  = 'is-primary';

        return $classes;
    }

    /**
     * Override comment form submit button.
     *
     * @return string
     */
    public function comments_submit() : string {
        return sprintf(
            '<button name="submit" type="submit" id="submit" class="button button--icon is-primary-invert" >%s %s</button>', // phpcs:ignore
            __( 'Send Comment', 'tms-theme-base' ),
            '<svg class="icon icon--arrow-right icon--large">
                <use xlink:href="#icon-arrow-right"></use>
            </svg>'
        );
    }

    /**
     * Override custom links removal.
     *
     * @return boolean
     */
    public function remove_custom_links() : bool {
        return false;
    }
}
