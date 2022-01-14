<?php
/**
 *  Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Sara_Hilden\Formatters;

use TMS\Theme\Base\Interfaces\Formatter;

/**
 * Class NoticeBannerFormatter
 *
 * @package TMS\Theme\Sara_Hilden\Formatters
 */
class NoticeBannerFormatter implements Formatter {

    /**
     * Define formatter name
     */
    const NAME = 'NoticeBanner';

    /**
     * Hooks
     */
    public function hooks() : void {
        add_filter(
            'tms/acf/layout/notice_banner/data',
            [ $this, 'format' ],
            20
        );

        add_filter(
            'tms/acf/block/notice_banner/data',
            [ $this, 'format' ],
            20
        );
    }

    /**
     * Format layout or block data
     *
     * @param array $data ACF data.
     *
     * @return array
     */
    public function format( array $data ) : array {
        if ( $data['background_color'] === 'primary' ) {
            $data['container_classes'] = 'has-colors-primary';
            $data['text_color']        = 'has-text-primary-invert';
            $data['icon_classes']      = 'is-primary-invert';
        }
        else {
            $data['container_classes'] = 'has-colors-secondary';
            $data['text_color']        = 'has-text-secondary-invert';
            $data['icon_classes']      = 'is-secondary-invert';
        }

        return $data;
    }
}
