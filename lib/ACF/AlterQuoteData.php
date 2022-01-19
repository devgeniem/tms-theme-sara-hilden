<?php
/**
 *  Copyright (c) 2021. Geniem Oy
 */

use TMS\Theme\Base\Logger;
use TMS\Theme\Base\PostType\Page;
use TMS\Theme\Base\PostType\Post;
use TMS\Theme\Base\PostType\BlogArticle;

/**
 * Alter Material block
 */
class AlterQuoteData {

    /**
     * Constructor
     */
    public function __construct() {
        add_filter(
            'tms/acf/block/quote/data',
            [ $this, 'alter_data' ],
            20,
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
            $data['classes']['quote'] = [
                'is-family-secondary',
                'has-text-weight-medium',
                'is-size-4',
                is_singular( [ Page::SLUG, Post::SLUG, BlogArticle::SLUG ] ) ? 'is-size-1' : 'is-size-4',
            ];
        }
        catch ( Exception $e ) {
            ( new Logger() )->error( $e->getMessage(), $e->getTrace() );
        }
        return $data;
    }

}

( new AlterQuoteData() );
