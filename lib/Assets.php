<?php
/**
 *  Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Sara_Hilden;

use Closure;
use function add_action;
use function add_filter;
use function get_stylesheet_directory;

/**
 * Class Assets
 *
 * @package TMS\Theme\Sara_Hilden
 */
class Assets extends \TMS\Theme\Base\Assets implements \TMS\Theme\Base\Interfaces\Controller {

    /**
     * Add hooks and filters from this controller
     *
     * @return void
     */
    public function hooks() : void {
        add_filter( 'tms/theme/theme_default_color', [ $this, 'theme_name' ] );
        add_filter( 'tms/theme/theme_selected', [ $this, 'theme_name' ] );

        add_filter( 'tms/theme/theme_css_path', [ $this, 'theme_asset_path' ], 10, 2 );
        add_filter( 'tms/theme/theme_js_path', [ $this, 'theme_asset_path' ], 10, 2 );
        add_filter( 'tms/theme/admin_js_path', [ $this, 'base_theme_asset_path' ], 10, 2 );

        add_filter( 'tms/theme/asset_mod_time', function ( $mod_time, $filename ) {
            if ( false !== strpos( $filename, 'sara_hilden' ) ) {
                $dist_path = get_stylesheet_directory() . '/assets/dist/' . $filename;

                if ( file_exists( $dist_path ) ) {
                    return filemtime( $dist_path );
                }
            }

            return $mod_time;

        }, 10, 2 );

        add_filter(
            'tms/theme/icons',
            Closure::fromCallable( [ $this, 'get_theme_icons' ] ),
            15,
            0
        );
    }

    /**
     * Get theme name.
     *
     * @return string
     */
    public function theme_name() : string {
        return 'sara_hilden';
    }

    /**
     * Get theme asset path.
     *
     * @param string $full_path Asset path.
     * @param string $file      File name.
     *
     * @return string
     */
    public function theme_asset_path( $full_path, $file ) : string { // // phpcs:ignore
        return get_stylesheet_directory_uri() . '/assets/dist/' . $file;
    }

    /**
     * Get base theme asset path.
     *
     * @param string $full_path Asset path.
     * @param string $file      File name.
     *
     * @return string
     */
    public function base_theme_asset_path( $full_path, $file ) : string { // // phpcs:ignore
        return get_template_directory_uri() . '/assets/dist/' . $file;
    }

    /**
     * This enables cache busting for theme CSS and JS files by
     * returning a microtime timestamp for the given files.
     * If the file is not found for some reason, it uses the theme version.
     *
     * @param string $filename The file to check.
     *
     * @return int|string A microtime amount or the theme version.
     */
    protected static function get_theme_asset_mod_time( $filename = '' ) {
        return file_exists( DPT_ASSET_CACHE_URI . '/' . $filename )
            ? filemtime( DPT_ASSET_CACHE_URI . '/' . $filename )
            : DPT_THEME_VERSION;
    }

    /**
     * Get available icon choices.
     *
     * @return string[]
     */
    protected function get_theme_icons() {
        $icons = parent::get_theme_icons();

        $sara_hilden_icons = [
            'cafesara'      => 'Cafe Sara',
            'veistospuisto' => 'Veistospuisto',
        ];

        $icons = array_merge( $icons, $sara_hilden_icons );

        asort( $icons );

        return $icons;
    }
}
