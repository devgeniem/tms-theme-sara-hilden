<?php
/**
 * Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Sara_Hilden;

use PageArtist;

/**
 * Class ThemeSupports
 *
 * @package TMS\Theme\Sara_Hilden
 */
class ThemeSupports implements \TMS\Theme\Base\Interfaces\Controller {

    /**
     * Initialize the class' variables and add methods
     * to the correct action hooks.
     *
     * @return void
     */
    public function hooks() : void {
        \add_filter(
            'query_vars',
            \Closure::fromCallable( [ $this, 'query_vars' ] )
        );
    }

    /**
     * Append custom query vars
     *
     * @param array $vars Registered query vars.
     *
     * @return array
     */
    protected function query_vars( $vars ) {
        $vars[] = \PageArtwork::SEARCH_QUERY_VAR;
        $vars[] = \PageArtwork::FILTER_QUERY_VAR;

        $vars[] = PageArtist::SEARCH_QUERY_VAR;
        $vars[] = PageArtist::FILTER_QUERY_VAR;
        $vars[] = PageArtist::ORDERBY_QUERY_VAR;

        return $vars;
    }
}
