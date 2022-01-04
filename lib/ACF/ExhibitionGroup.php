<?php
/**
 * Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Sara_Hilden\ACF;

use Closure;
use Geniem\ACF\Exception;
use Geniem\ACF\Group;
use Geniem\ACF\RuleGroup;
use Geniem\ACF\Field;
use TMS\Theme\Base\Logger;
use TMS\Theme\Sara_Hilden\PostType\Exhibition;

/**
 * Class ExhibitionGroup
 *
 * @package TMS\Theme\Base\ACF
 */
class ExhibitionGroup {

    /**
     * ExhibitionGroup constructor.
     */
    public function __construct() {
        add_action(
            'init',
            Closure::fromCallable( [ $this, 'register_fields' ] )
        );
    }

    /**
     * Register fields
     */
    protected function register_fields() : void {
        try {
            $field_group = ( new Group( 'Näyttelyn lisätiedot' ) )
                ->set_key( 'fg_exhibition_fields' );

            $rule_group = ( new RuleGroup() )
                ->add_rule( 'post_type', '==', Exhibition::SLUG );

            $field_group
                ->add_rule_group( $rule_group )
                ->set_position( 'normal' );

            $field_group->add_fields(
                apply_filters(
                    'tms/acf/group/' . $field_group->get_key() . '/fields',
                    [
                        $this->get_details_tab( $field_group->get_key() ),
                    ]
                )
            );

            $field_group = apply_filters(
                'tms/acf/group/' . $field_group->get_key(),
                $field_group
            );

            $field_group->register();
        }
        catch ( Exception $e ) {
            ( new Logger() )->error( $e->getMessage(), $e->getTraceAsString() );
        }
    }

    /**
     * Get writer tab
     *
     * @param string $key Field group key.
     *
     * @return Field\Tab
     * @throws Exception In case of invalid option.
     */
    protected function get_details_tab( string $key ) : Field\Tab {
        $strings = [
            'tab'           => 'Lisätiedot',
            'date'          => [
                'title'        => 'Päiväys',
                'instructions' => '',
            ],
            'title'         => [
                'title'        => 'Otsikko',
                'instructions' => '',
            ],
            'opening_times' => [
                'title'        => 'Aukioloajat',
                'instructions' => '',
            ],
            'location'      => [
                'title'        => 'Sijainti',
                'instructions' => '',
            ],
        ];

        $tab = ( new Field\Tab( $strings['tab'] ) )
            ->set_placement( 'left' );

        $title_field = ( new Field\Text( $strings['title']['title'] ) )
            ->set_key( "${key}_title" )
            ->set_name( 'title' )
            ->set_instructions( $strings['title']['instructions'] );

        $date_field = ( new Field\DatePicker( $strings['date']['title'] ) )
            ->set_key( "${key}_date" )
            ->set_name( 'date' )
            ->set_display_format( 'j.n.Y' )
            ->set_return_format( 'j.n.Y' )
            ->set_instructions( $strings['date']['instructions'] );

        $opening_times_field = ( new Field\Text( $strings['opening_times']['title'] ) )
            ->set_key( "${key}_opening_times" )
            ->set_name( 'opening_times' )
            ->set_instructions( $strings['opening_times']['instructions'] );

        $location_field = ( new Field\Text( $strings['location']['title'] ) )
            ->set_key( "${key}_location" )
            ->set_name( 'location' )
            ->set_instructions( $strings['location']['instructions'] );

        $tab->add_fields( [
            $title_field,
            $date_field,
            $opening_times_field,
            $location_field,
        ] );

        return $tab;
    }
}

( new ExhibitionGroup() );
