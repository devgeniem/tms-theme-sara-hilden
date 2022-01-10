<?php
/**
 *  Copyright (c) 2021. Geniem Oy
 *
 */

use TMS\Theme\Base\Traits\Pagination;
use TMS\Theme\Sara_Hilden\PostType\Exhibition;

/**
 * Archive for Artist CPT
 */
class ArchiveExhibition extends BaseModel {

    use Pagination;

    /**
     * Search input name.
     */
    const SEARCH_QUERY_VAR = 'exhibition-search';

    /**
     * Artist category filter name.
     */
    const YEAR_QUERY_VAR = 'exhibition-year';

    /**
     * Artist category filter name.
     */
    const PAST_QUERY_VAR = 'archive';

    /**
     * Number of past items to show per page.
     */
    const PAST_ITEMS_PER_PAGE = '9';

    /**
     * Number of upcoming items to show per page.
     */
    const UPCOMING_ITEMS_PER_PAGE = '100';

    /**
     * Pagination data.
     *
     * @var object
     */
    protected object $pagination;

    /**
     * Hooks
     */
    public static function hooks() : void {
        add_action(
            'pre_get_posts',
            [ __CLASS__, 'modify_query' ]
        );
    }

    /**
     * Get search query var value
     *
     * @return mixed
     */
    protected static function get_search_query_var() {
        return get_query_var( self::SEARCH_QUERY_VAR, false );
    }

    /**
     * Get filter query var value
     *
     * @return string|null
     */
    protected static function get_year_query_var() : ?string {
        $value = get_query_var( self::YEAR_QUERY_VAR, false );

        return ! $value
            ? null
            : sanitize_text_field( $value );
    }

    /**
     * Get current tab query var value
     *
     * @return bool
     */
    public function is_past_archive() : bool {
        return ! is_null( get_query_var( self::PAST_QUERY_VAR, null ) );
    }

    /**
     * Page title
     *
     * @return string
     */
    public function page_title() : string {
        return post_type_archive_title( '', false );
    }

    /**
     * Return translated strings.
     *
     * @return array[]
     */
    public function strings() : array {
        return [
            'search'     => [
                'label'             => __( 'Search from archive', 'tms-theme-sara_hilden' ),
                'submit_value'      => __( 'Search', 'tms-theme-sara_hilden' ),
                'input_placeholder' => __( 'Search from archive', 'tms-theme-sara_hilden' ),
            ],
            'no_results' => __( 'No results', 'tms-theme-sara_hilden' ),
            'year_label' => __( 'Year', 'tms-theme-sara_hilden' ),
        ];
    }

    /**
     * Modify query
     *
     * @param WP_Query $wp_query Instance of WP_Query.
     *
     * @return void
     */
    public static function modify_query( WP_Query $wp_query ) : void {
        if ( is_admin() || ( ! $wp_query->is_main_query() || ! $wp_query->is_post_type_archive( Exhibition::SLUG ) ) ) {
            return;
        }

        $past_archive = ( new ArchiveExhibition )->is_past_archive();

        $meta_query[] = [
            'key'     => 'is_upcoming',
            'compare' => $past_archive ? '!=' : '=',
            'value'   => true,
        ];

        $posts_per_page = self::UPCOMING_ITEMS_PER_PAGE;

        if ( $past_archive ) {
            $posts_per_page = self::PAST_ITEMS_PER_PAGE;
            $year           = self::get_year_query_var();

            if ( ! empty( $year ) ) {
                $meta_query[] = [
                    'key'     => 'exhibition_year',
                    'compare' => '=',
                    'value'   => $year,
                ];
            }

            $s = self::get_search_query_var();

            if ( ! empty( $s ) ) {
                $wp_query->set( 's', $s );
            }
        }

        $wp_query->set( 'posts_per_page', $posts_per_page );
        $wp_query->set( 'meta_query', $meta_query );
    }

    /**
     * Return current search data.
     *
     * @return string[]
     */
    public function search() : array {
        $this->search_data        = new stdClass();
        $this->search_data->query = get_query_var( self::SEARCH_QUERY_VAR );

        return [
            'input_search_name' => self::SEARCH_QUERY_VAR,
            'current_search'    => $this->search_data->query,
            'action'            => add_query_arg(
                self::PAST_QUERY_VAR,
                '',
                get_post_type_archive_link( Exhibition::SLUG )
            ),
        ];
    }

    /**
     * Return current search data.
     *
     * @return string[]
     */
    public function tabs() : array {
        $base_url        = get_post_type_archive_link( Exhibition::SLUG );
        $past_tab_active = self::is_past_archive();

        return [
            'upcoming' => [
                'text'      => __( 'Current exhibitions', 'tms-theme-sara_hilden' ),
                'link'      => $base_url,
                'is_active' => ! $past_tab_active,
            ],
            'past'     => [
                'text'      => __( 'Archives', 'tms-theme-sara_hilden' ),
                'link'      => add_query_arg(
                    self::PAST_QUERY_VAR,
                    '',
                    $base_url
                ),
                'is_active' => $past_tab_active,
            ],
        ];
    }

    /**
     * Years
     *
     * @return array
     */
    public function years() {
        if ( ! $this->is_past_archive() ) {
            return;
        }

        $choices = [];
        $items   = $this->get_past_exhibitions();

        if ( ! empty( $items ) ) {
            $selected_year = self::get_year_query_var();

            $choices[] = [
                'value'       => '',
                'is_selected' => empty( $selected_year ) ? 'selected' : '',
                'label'       => __( 'Filter by year', 'tms-theme-sara_hilden' ),
            ];

            foreach ( $items as $exhibition ) {
                $year = get_post_meta( $exhibition->ID, 'exhibition_year', true );

                if ( in_array( $year, array_column( $choices, 'value' ), true ) ) {
                    continue;
                }

                if ( ! empty( $year ) ) {
                    $choices[] = [
                        'value'       => $year,
                        'label'       => $year,
                        'is_selected' => $year === $selected_year ? 'selected' : '',
                    ];
                }
            }
        }

        return $choices;
    }

    /**
     * View results
     *
     * @return array
     */
    public function results() {
        global $wp_query;

        $is_past_archive = $this->is_past_archive();
        $per_page        = $is_past_archive ? self::PAST_ITEMS_PER_PAGE : self::UPCOMING_ITEMS_PER_PAGE;

        $this->set_pagination_data( $wp_query, $per_page );

        $search_clause = self::get_search_query_var();
        $is_filtered   = $search_clause || self::get_year_query_var();

        return [
            'result_count'       => count( $this->get_upcoming_exhibitions() ),
            'past_results_count' => count( $this->get_past_exhibitions() ),
            'show_past'          => $is_past_archive,
            'posts'              => $this->format_posts( $wp_query->posts ),
            'summary'            => $is_filtered ? $this->results_summary( $wp_query->found_posts, $search_clause ) : false,
            'partial'            => $is_past_archive ? 'shared/exhibition-item-simple' : 'shared/exhibition-item',
        ];
    }

    /**
     * Format posts for view
     *
     * @param array $posts Array of WP_Post instances.
     *
     * @return array
     */
    protected function format_posts( array $posts ) : array {
        return array_map( function ( $item ) {
            $item->permalink   = get_the_permalink( $item->ID );
            $additional_fields = get_fields( $item->ID );
            $item->fields      = $additional_fields;
            $start_date        = $item->fields['start_date'] ?? false;

            if ( ! empty( $start_date ) ) {
                $item->date = $start_date;

                if ( ! empty( $item->fields['end_date'] ) ) {
                    $item->date .= ' - ' . $item->fields['end_date'];
                }
            }

            return $item;
        }, $posts );
    }

    /**
     * Set pagination data
     *
     * @param WP_Query $wp_query       Instance of WP_Query.
     * @param string   $posts_per_page Number of items per page.
     */
    protected function set_pagination_data( $wp_query, $per_page ) : void {
        $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

        $this->pagination           = new stdClass();
        $this->pagination->page     = $paged;
        $this->pagination->per_page = $per_page;
        $this->pagination->items    = $wp_query->found_posts;
        $this->pagination->max_page = (int) ceil( $wp_query->found_posts / $per_page );
    }

    /**
     * Get results summary text.
     *
     * @param int $result_count Result count.
     *
     * @return string|bool
     */
    protected function results_summary( $result_count, $search_clause ) {

        if ( ! empty( $search_clause ) ) {
            $results_text = sprintf(
            // translators: 1. placeholder is number of search results, 2. placeholder contains the search term(s).
                _nx(
                    '%1$1s result found for "%2$2s"',
                    '%1$1s results found for "%2$2s"',
                    $result_count,
                    'filter with search clause results summary',
                    'tms-theme-sara_hilden'
                ),
                $result_count,
                $search_clause
            );
        }
        else {
            $results_text = sprintf(
            // translators: 1. placeholder is number of search results
                _nx(
                    '%1$1s result found',
                    '%1$1s results found',
                    $result_count,
                    'filter results summary',
                    'tms-theme-sara_hilden'
                ),
                $result_count,
                $search_clause
            );
        }

        return $results_text;
    }

    /**
     * @return array
     */
    private function get_exhibitions() : array {
        $args = [
            'post_type'      => Exhibition::SLUG,
            'post_status'    => 'publish',
            'posts_per_page' => 200,
        ];

        $query = new WP_Query( $args );

        return $query->have_posts() ? $query->posts : [];
    }

    /**
     * Get upcoming exhibitions
     *
     * @return array
     */
    private function get_upcoming_exhibitions() : array {
        $posts = $this->get_exhibitions();

        return array_filter( $posts, function ( $item ) {
            return get_post_meta( $item->ID, 'is_upcoming', true );
        } );
    }

    /**
     * Get past exhibitions
     *
     * @return array
     */
    private function get_past_exhibitions() : array {
        $posts = $this->get_exhibitions();

        return array_filter( $posts, function ( $item ) {
            return ! get_post_meta( $item->ID, 'is_upcoming', true );
        } );
    }
}
