<?php
/**
 * HTML Elements
 *
 * A helper class for outputting common HTML elements.
 *
 * @package     MDJM
 * @subpackage  Classes/HTML
 * @copyright   Copyright (c) 2016, Mike Howard
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * MDJM_HTML_Elements Class
 *
 * @since	1.3.7
 */
class MDJM_HTML_Elements {

	/**
	 * Renders an HTML Dropdown of all the event Post Statuses
	 *
	 * @access	public
	 * @since	1.3.7
	 * @param	str		$name		Name attribute of the dropdown
	 * @param	str		$selected	Status to select automatically
	 * @return	str		$output		Status dropdown
	 */
	public function event_status_dropdown( $name = 'post_status', $selected = 0 ) {
		$event_statuses = mdjm_get_post_statuses( 'labels' );
		$options    = array();
		
		foreach ( $event_statuses as $event_statuses ) {
			$options[ $event_statuses->name ] = esc_html( $event_statuses->label );
		}

		$output = $this->select( array(
			'name'             => $name,
			'selected'         => $selected,
			'options'          => $options,
			'show_option_all'  => false,
			'show_option_none' => false
		) );

		return $output;
	} // event_status_dropdown

	/**
	 * Renders an HTML Dropdown of all the Enquiry Sources
	 *
	 * @access	public
	 * @since	1.3.7
	 * @param	str		$name		Name attribute of the dropdown
	 * @param	int		$selected	Category to select automatically
	 * @return	str		$output		Category dropdown
	 */
	public function enquiry_source_dropdown( $name = 'mdjm_enquiry_source', $selected = 0 ) {

		$args = array(
			'hide_empty' => false
		);

		$categories = get_terms( 'enquiry-source', apply_filters( 'mdjm_enquiry_source_dropdown', $args ) );
		$options    = array();

		if ( empty( $selected ) )	{
			$selected = mdjm_get_option( 'enquiry_source_default' );
		}

		foreach ( $categories as $category ) {
			$options[ absint( $category->term_id ) ] = esc_html( $category->name );
		}

		$category_labels = mdjm_get_taxonomy_labels( 'enquiry-source' );
		$output = $this->select( array(
			'name'             => $name,
			'selected'         => $selected,
			'options'          => $options,
			'show_option_all'  => false,
			'show_option_none' => false
		) );

		return $output;
	} // enquiry_source_dropdown

	/**
	 * Renders an HTML Dropdown of all Transaction Types
	 *
	 * @access	public
	 * @since	1.3.7
	 * @param	str		$name		Name attribute of the dropdown
	 * @param	int		$selected	Category to select automatically
	 * @return	str		$output		Category dropdown
	 */
	public function txn_type_dropdown( $name = 'mdjm_txn_for', $selected = 0 ) {

		$args = array(
			'hide_empty' => false
		);

		$categories = get_terms( 'transaction-types', apply_filters( 'mdjm_txn_types_dropdown', $args ) );
		$options    = array();

		foreach ( $categories as $category ) {
			$options[ absint( $category->term_id ) ] = esc_html( $category->name );
		}

		$category_labels = mdjm_get_taxonomy_labels( 'transaction-types' );
		$output = $this->select( array(
			'name'             => $name,
			'selected'         => $selected,
			'options'          => $options,
			'show_option_all'  => __( ' - Select Txn Type - ' ),
			'show_option_none' => false
		) );

		return $output;
	} // txn_type_dropdown
	
	/**
	 * Renders an HTML Dropdown of all Transaction Types
	 *
	 * @access	public
	 * @since	1.3.7
	 * @param	arr		$args		See @defaults
	 * @return	str		$output		Venue dropdown
	 */
	public function venue_dropdown( $args = array() ) {

		$defaults = array(
			'name'             => 'venue_id',
			'class'            => 'mdjm-venue-select',
			'id'               => '',
			'selected'         => 0,
			'chosen'           => false,
			'placeholder'      => null,
			'multiple'         => false,
			'allow_add'        => true,
			'show_option_all'  => __( ' - Select Venue - ' ),
			'show_option_none' => false,
			'data'             => array()
		);
		
		$args = wp_parse_args( $args, $defaults );

		if ( $args['allow_add'] )	{
			$args['options']  = array(
				'manual' => __( '  - Enter Manually - ', 'mobile-dj-manager' ),
				'client' => __( '  - Use Client Address - ', 'mobile-dj-manager' )
			);
		}

		$venues = mdjm_get_venues();

		if ( $venues )	{
			foreach ( $venues as $venue ) {
				$args['options'][ $venue->ID ] = $venue->post_title;
			}
		}

		$output = $this->select( $args );

		return $output;
	} // venue_dropdown

	/**
	 * Renders an HTML Dropdown of years
	 *
	 * @access	public
	 * @since	1.3.7
	 * @param	str		$name			Name attribute of the dropdown
	 * @param	int		$selected		Year to select automatically
	 * @param	int		$years_before	Number of years before the current year the dropdown should start with
	 * @param	int		$years_after	Number of years after the current year the dropdown should finish at
	 * @return	str		$output			Year dropdown
	 */
	public function year_dropdown( $name = 'year', $selected = 0, $years_before = 5, $years_after = 0 ) {
		$current     = date( 'Y' );
		$start_year  = $current - absint( $years_before );
		$end_year    = $current + absint( $years_after );
		$selected    = empty( $selected ) ? date( 'Y' ) : $selected;
		$options     = array();

		while ( $start_year <= $end_year ) {
			$options[ absint( $start_year ) ] = $start_year;
			$start_year++;
		}

		$output = $this->select( array(
			'name'             => $name,
			'selected'         => $selected,
			'options'          => $options,
			'show_option_all'  => false,
			'show_option_none' => false
		) );

		return $output;
	} // year_dropdown

	/**
	 * Renders an HTML Dropdown of months
	 *
	 * @access	public
	 * @since	1.3.7
	 * @param	str		$name		Name attribute of the dropdown
	 * @param	int		$selected	Month to select automatically
	 * @return	str		$output		Month dropdown
	 */
	public function month_dropdown( $name = 'month', $selected = 0 ) {
		$month   = 1;
		$options = array();
		$selected = empty( $selected ) ? date( 'n' ) : $selected;

		while ( $month <= 12 ) {
			$options[ absint( $month ) ] = mdjm_month_num_to_name( $month );
			$month++;
		}

		$output = $this->select( array(
			'name'             => $name,
			'selected'         => $selected,
			'options'          => $options,
			'show_option_all'  => false,
			'show_option_none' => false
		) );

		return $output;
	} // month_dropdown

	/**
	 * Renders an HTML Dropdown of clients
	 *
	 * @access	public
	 * @since	1.3.7
	 * @param	arr		$args		Select list arguments. See @defaults.
	 * @return	str		$output		Client dropdown
	 */
	public function client_dropdown( $args = array() ) {
		$options  = array();

		$defaults = array(
			'name'             => 'client_name',
			'class'            => '',
			'id'               => '',
			'selected'         => 0,
			'roles'            => array( 'client', 'inactive_client' ),
			'chosen'           => false,
			'placeholder'      => null,
			'multiple'         => false,
			'null_value'       => false,
			'add_new'          => false,
			'show_option_all'  => _x( 'All Clients', 'all dropdown items', 'mobile-dj-manager' ),
			'show_option_none' => _x( 'Select a Client', 'no dropdown items', 'mobile-dj-manager' ),
			'data'             => array()
		);

		$args = wp_parse_args( $args, $defaults );

		$selected = empty( $args['selected'] ) ? 0 : $args['selected'];

		$clients = mdjm_get_clients( $args['roles'] );
		
		if ( ! empty( $args['null_value'] ) )	{
			foreach( $args['null_value'] as $key => $value )	{
				$options[ $key ] = $value;
			}
		}
		
		if ( ! empty( $args['add_new'] ) )	{
			$options['mdjm_add_client'] = __( 'Add New Client', 'mobile-dj-manager' );
		}
		
		if ( $clients )	{
			foreach( $clients as $client )	{
				$options[ $client->ID ] = $client->display_name;
			}
		}

		$output = $this->select( array(
			'name'             => $args['name'],
			'class'            => $args['class'],
			'id'               => $args['id'],
			'selected'         => $selected,
			'options'          => $options,
			'chosen'           => $args['chosen'],
			'placeholder'      => $args['placeholder'],
			'multiple'         => $args['multiple'],
			'show_option_all'  => $args['show_option_all'],
			'show_option_none' => $args['show_option_none'],
			'data'             => $args['data']
		) );

		return $output;
	} // client_dropdown

	/**
	 * Renders a dropdown list of packages.
	 *
	 * @since	1.3.7
	 * @param	arr		$args	@see $default
	 * @return	str
	 */
	public function packages_dropdown( $args = array() )	{
		$defaults = array(
			'name'             => '_mdjm_event_package',
			'id'               => '',
			'class'            => '',
			'selected'         => '',
			'show_option_none' => false,
			'show_option_all'  => __( 'No Package', 'mobile-dj-manager' ),
			'chosen'           => false,
			'employee'         => false,
			'placeholder'      => null,
			'multiple'         => false,
			'cost'             => true,
			'data'             => array()
		);
		
		$args    = wp_parse_args( $args, $defaults );
		$options = array();
		
		$args['id']       = ! empty( $args['id'] )       ? $args['id'] : $args['name'];
		
		$packages = mdjm_get_packages();

		if ( $packages )	{

			foreach( $packages as $package )	{
				if ( empty( $package['enabled'] ) || $package['enabled'] != 'Y' )	{
					continue;
				}

				if( $args['employee'] )	{	
					$employees_with = explode( ',', $package['djs'] );
					
					if( ! in_array( $args['employee'], $employees_with ) )	{
						continue;
					}
				}

				$price = '';
				if( $args['cost'] == true )	{
					$price .= ' - ' . mdjm_currency_filter( mdjm_format_amount( $package['cost'] ) ) ;
				}

				$options[ $package['slug'] ] = stripslashes( esc_attr( $package['name'] ) ) . $price;

			}

		}
		
		$output = $this->select( array(
			'name'             => $args['name'],
			'class'            => $args['class'],
			'id'               => $args['id'],
			'selected'         => $args['selected'],
			'options'          => $options,
			'chosen'           => $args['chosen'],
			'placeholder'      => $args['placeholder'],
			'multiple'         => $args['multiple'],
			'show_option_none' => false,
			'show_option_all'  => $packages ? $args['show_option_all'] : __( 'No packages available', 'mobile-dj-manager' ),
			'data'             => $args['data']
		) );

		return $output;

	} // packages_dropdown

	/**
	 * Renders a dropdown list of equipment add-ons.
	 *
	 * @since	1.3.7
	 * @param	arr		$args	@see $default
	 * @return	str
	 */
	public function addons_dropdown( $args = array() )	{
		$defaults = array(
			'name'             => 'event_addons',
			'id'               => '',
			'class'            => '',
			'selected'         => '',
			'show_option_none' => false,
			'show_option_all'  => __( 'No Package', 'mobile-dj-manager' ),
			'chosen'           => false,
			'employee'         => false,
			'placeholder'      => null,
			'multiple'         => true,
			'package'          => '',
			'cost'             => true,
			'data'             => array()
		);
		
		$args    = wp_parse_args( $args, $defaults );
		$options = array();

		$addons     = mdjm_get_addons();
		$categories = get_option( 'mdjm_cats' );
		if( $categories )	{
			asort( $categories );
		}

		if ( $addons )	{
			foreach( $categories as $category_key => $category_value )	{
				
				foreach( $addons as $addon )	{
					if ( empty( $addon[6] ) || $addon[6] != 'Y' )	{
						continue;
					}

					if ( ! empty( $args['package'] ) )	{
						$packages = mdjm_get_packages();
						$package_items = explode( ',', $packages[ $args['package'] ]['equipment'] );
						
						if ( ! empty( $package_items ) && in_array( $addon[1], $package_items ) )	{
							continue;
						}
					}

					if( $args['employee'] )	{	
						$employees_with = explode( ',', $addon[8] );
						
						if( ! in_array( $args['employee'], $employees_with ) )	{
							continue;
						}
					}

					if( $addon[5] == $category_key )	{
						$price = '';
						if( $args['cost'] == true )	{
							$price .= ' - ' . mdjm_currency_filter( mdjm_format_amount( $addon[7] ) ) ;
						}

						$options['groups'][ $category_value ][] = array( $addon[1] => stripslashes( esc_attr( $addon[0] ) ) . $price );

					}

				}
				
			}

		}
		
		$output = $this->select( array(
			'name'             => $args['name'],
			'class'            => $args['class'],
			'id'               => $args['id'],
			'selected'         => $args['selected'],
			'options'          => $options,
			'chosen'           => $args['chosen'],
			'placeholder'      => $args['placeholder'],
			'multiple'         => $args['multiple'],
			'show_option_none' => false,
			'show_option_all'  => $addons ? $args['show_option_all'] : __( 'No add-ons available', 'mobile-dj-manager' ),
			'data'             => $args['data']
		) );

		return $output;

	} // addons_dropdown

	/**
	 * Renders an Time Dropdown of Hours
	 *
	 * @since	1.3.7
	 *
	 * @param	arr		$args
	 *
	 * @return	str
	 */
	public function time_hour_select( $args = array() )	{
		$options  = array();
		$defaults = array(
			'name'             => 'event_start_hr',
			'class'            => 'mdjm-time',
			'id'               => '',
			'selected'         => 0
		);

		$args = wp_parse_args( $args, $defaults );
		
		$args['id'] = ! empty( $args['id'] ) ? $args['id'] : $args['name'];
		
		if( 'H:i' == mdjm_get_option( 'time_format', 'H:i' ) )	{
			$i      = '00';
			$x      = '23';
			$format = 'H';
		} else	{
			$i      = '1';
			$x      = '12';
			$format = 'g';	
		}

		while( $i <= $x )	{
			if( $i != 0 && $i < 10 && $format == 'H' )	{
				$i = '0' . $i;
			}
			$options[ $i ] = $i;
			$i++;
		}

		$output = $this->select( array(
			'name'     => $args['name'],
			'selected' => $args['selected'],
			'class'    => $args['class'],
			'options'  => $options,
			'show_option_all'  => false,
			'show_option_none' => false
		) );

		return $output;

	} // time_hour_select
	
	/**
	 * Renders an Time Dropdown of Hours
	 *
	 * @since	1.3.7
	 *
	 * @param	arr		$args
	 *
	 * @return	str
	 */
	public function time_minute_select( $args = array() )	{
		$options  = array();
		$minutes  = apply_filters( 'mdjm_time_minutes', array( '00', '15', '30', '45' ) );
		$defaults = array(
			'name'      => 'event_start_min',
			'class'     => 'mdjm-time',
			'id'        => '',
			'selected'  => 0
		);

		$args = wp_parse_args( $args, $defaults );
		
		$args['id'] = ! empty( $args['id'] ) ? $args['id'] : $args['name'];

		foreach( $minutes as $minute )	{
			$options[ $minute ] = $minute;
		}

		$output = $this->select( array(
			'name'     => $args['name'],
			'selected' => $args['selected'],
			'class'    => $args['class'],
			'options'  => $options,
			'show_option_all'  => false,
			'show_option_none' => false
		) );

		return $output;

	} // time_minute_select

	/**
	 * Renders an Time Period Dropdown
	 *
	 * @since	1.3.7
	 *
	 * @param	arr		$args
	 *
	 * @return	str
	 */
	public function time_period_select( $args = array() )	{
		$options  = array();
		$minutes  = apply_filters( 'mdjm_time_minutes', array( '00', '15', '30', '45' ) );
		$defaults = array(
			'name'             => 'event_start_period',
			'class'            => 'mdjm-time',
			'id'               => '',
			'selected'         => 0
		);

		$args = wp_parse_args( $args, $defaults );
		
		$args['id'] = ! empty( $args['id'] ) ? $args['id'] : $args['name'];

		$options = array(
			'AM' => __( 'AM', 'mobile-dj-manager' ),
			'PM' => __( 'PM', 'mobile-dj-manager' )
		);

		$output = $this->select( array(
			'name'             => $args['name'],
			'selected'         => $args['selected'],
			'class'    => $args['class'],
			'options'          => $options,
			'show_option_all'  => false,
			'show_option_none' => false
		) );

		return $output;

	} // time_period_select

	/**
	 * Renders an HTML Dropdown
	 *
	 * @since	1.3.7
	 *
	 * @param	arr		$args
	 *
	 * @return	str
	 */
	public function select( $args = array() ) {
		$defaults = array(
			'options'          => array(),
			'name'             => null,
			'class'            => '',
			'id'               => '',
			'selected'         => 0,
			'chosen'           => false,
			'placeholder'      => null,
			'multiple'         => false,
			'show_option_all'  => _x( 'All', 'all dropdown items', 'mobile-dj-manager' ),
			'show_option_none' => _x( 'None', 'no dropdown items', 'mobile-dj-manager' ),
			'data'             => array(),
		);

		$args = wp_parse_args( $args, $defaults );
		
		$args['id'] = ! empty( $args['id'] ) ? $args['id'] : $args['name'];

		$data_elements = '';
		foreach ( $args['data'] as $key => $value ) {
			$data_elements .= ' data-' . esc_attr( $key ) . '="' . esc_attr( $value ) . '"';
		}

		if( $args['multiple'] ) {
			$multiple = ' MULTIPLE';
		} else {
			$multiple = '';
		}

		if( $args['chosen'] ) {
			$args['class'] .= ' mdjm-select-chosen';
		}

		if( $args['placeholder'] ) {
			$placeholder = $args['placeholder'];
		} else {
			$placeholder = '';
		}

		$class  = implode( ' ', array_map( 'sanitize_html_class', explode( ' ', $args['class'] ) ) );
		$output = '<select name="' . esc_attr( $args['name'] ) . '" id="' . esc_attr( mdjm_sanitize_key( str_replace( '-', '_', $args['id'] ) ) ) . '" class="mdjm-select ' . $class . '"' . $multiple . ' data-placeholder="' . $placeholder . '"'. $data_elements . '>' . "\r\n";

		if ( $args['show_option_all'] ) {
			if( $args['multiple'] ) {
				$selected = selected( true, in_array( 0, $args['selected'] ), false );
			} else {
				$selected = selected( $args['selected'], 0, false );
			}
			$output .= '<option value="all"' . $selected . '>' . esc_html( $args['show_option_all'] ) . '</option>' . "\r\n";
		}

		if ( ! empty( $args['options'] ) ) {

			if ( $args['show_option_none'] ) {
				if( $args['multiple'] ) {
					$selected = selected( true, in_array( -1, $args['selected'] ), false );
				} else {
					$selected = selected( $args['selected'], -1, false );
				}
				$output .= '<option value="-1"' . $selected . '>' . esc_html( $args['show_option_none'] ) . '</option>' . "\r\n";
			}

			if ( ! isset( $args['options']['groups'] ) )	{

				foreach( $args['options'] as $key => $option ) {
	
					if( $args['multiple'] && is_array( $args['selected'] ) ) {
						$selected = selected( true, in_array( $key, $args['selected'], true ), false );
					} else {
						$selected = selected( $args['selected'], $key, false );
					}
	
					$output .= '<option value="' . esc_attr( $key ) . '"' . $selected . '>' . esc_html( $option ) . '</option>' . "\r\n";
				}
				
			} else	{

				$i = 0;
				foreach( $args['options']['groups'] as $group => $items )	{

					if ( $i == 0 )	{
						$output .= '<optgroup label="' . esc_html( $group ) . '">' . "\r\n";
					}

					foreach( $items as $options ) {
						foreach ( $options as $key => $option )	{
	
							if( $args['multiple'] && is_array( $args['selected'] ) ) {
								$selected = selected( true, in_array( $key, $args['selected'], true ), false );
							} else {
								$selected = selected( $args['selected'], $key, false );
							}
			
							$output .= '<option value="' . esc_attr( $key ) . '"' . $selected . '>' . esc_html( $option ) . '</option>' . "\r\n";
						}
					}

					$i++;

					if ( $i == count( $options ) )	{
						$output .= '</optgroup>' . "\r\n";
						$i = 0;
					}

				}
	
			}
		}

		$output .= '</select>' . "\r\n";

		return $output;
	} // select

	/**
	 * Renders an HTML Checkbox
	 *
	 * @since	1.3.7
	 *
	 * @param	arr		$args
	 *
	 * @return	string
	 */
	public function checkbox( $args = array() ) {
		$defaults = array(
			'name'     => null,
			'current'  => null,
			'class'    => 'mdjm-checkbox',
			'options'  => array(
				'disabled' => false,
				'readonly' => false
			)
		);

		$args = wp_parse_args( $args, $defaults );

		$class = implode( ' ', array_map( 'sanitize_html_class', explode( ' ', $args['class'] ) ) );
		$options = '';
		if ( ! empty( $args['options']['disabled'] ) ) {
			$options .= ' disabled="disabled"';
		} elseif ( ! empty( $args['options']['readonly'] ) ) {
			$options .= ' readonly';
		}

		$output = '<input type="checkbox"' . $options . ' name="' . esc_attr( $args['name'] ) . '" id="' . esc_attr( $args['name'] ) . '" class="' . $class . ' ' . esc_attr( $args['name'] ) . '" ' . checked( 1, $args['current'], false ) . ' />';

		return $output;
	} // checkbox
	
	/**
	 * Renders an HTML Checkbox List
	 *
	 * @since	1.3.7
	 *
	 * @param	arr		$args
	 *
	 * @return	string
	 */
	public function checkbox_list( $args = array() ) {
		$defaults = array(
			'name'      => null,
			'class'     => 'mdjm-checkbox',
			'label_pos' => 'before',
			'options'   => array()
		);

		$args = wp_parse_args( $args, $defaults );

		$class = implode( ' ', array_map( 'sanitize_html_class', explode( ' ', $args['class'] ) ) );

		$label_pos = isset( $args['label_pos'] ) ? $args['label_pos'] : 'before';

		$output = '';
		
		if ( ! empty( $args['options'] ) )	{

			$i = 0;

			foreach( $args['options'] as $key => $value )	{

				if ( $label_pos == 'before' )	{
					$output .= $value . '&nbsp';
				}

				$output .= '<input type="checkbox" name="' . esc_attr( $args['name'] ) . '[]" id="' . esc_attr( $args['name'] ) . '-' . $key . '" class="' . $class . ' ' . esc_attr( $args['name'] ) . '" value="' . $key . '" />';

				if ( $label_pos == 'after' )	{
					$output .= '&nbsp' . $value;
				}

				if ( $i < count( $args['options'] ) )	{
					$output .= '<br />';
				}

				$i++;

			}
			
		}

		return $output;
	} // checkbox_list
	
	/**
	 * Renders HTML Radio Buttons
	 *
	 * @since	1.3.7
	 *
	 * @param	arr		$args
	 *
	 * @return	string
	 */
	public function radio( $args = array() ) {
		$defaults = array(
			'name'     => null,
			'current'  => null,
			'class'    => 'mdjm-radio',
			'label_pos' => 'before',
			'options'  => array()
		);

		$args = wp_parse_args( $args, $defaults );

		$class = implode( ' ', array_map( 'sanitize_html_class', explode( ' ', $args['class'] ) ) );

		$output = '';
		
		if ( ! empty( $args['options'] ) )	{

			$i = 0;

			foreach( $args['options'] as $key => $value )	{

				if ( $label_pos == 'before' )	{
					$output .= $value . '&nbsp';
				}

				$output = '<input type="radio" name="' . esc_attr( $args['name'] ) . '" id="' . esc_attr( $args['name'] ) . '-' . $key . '" class="' . $class . ' ' . esc_attr( $args['name'] ) . '" />';

				if ( $label_pos == 'after' )	{
					$output .= '&nbsp' . $value;
				}

				if ( $i < count( $args['options'] ) )	{
					$output .= '<br />';
				}

				$i++;

			}
			
		}

		return $output;
	} // radio

	/**
	 * Renders an HTML Text field
	 *
	 * @since	1.3.7
	 *
	 * @param	arr		$args	Arguments for the text field
	 * @return	str		Text field
	 */
	public function text( $args = array() ) {

		$defaults = array(
			'id'           => '',
			'name'         => isset( $name )  ? $name  : 'text',
			'type'         => 'text',
			'value'        => isset( $value ) ? $value : null,
			'label'        => isset( $label ) ? $label : null,
			'desc'         => isset( $desc )  ? $desc  : null,
			'placeholder'  => '',
			'class'        => 'regular-text',
			'disabled'     => false,
			'autocomplete' => '',
			'required'     => false,
			'data'         => false
		);

		$args = wp_parse_args( $args, $defaults );
		
		$args['id'] = ! empty( $args['id'] ) ? $args['id'] : $args['name'];

		$class = implode( ' ', array_map( 'sanitize_html_class', explode( ' ', $args['class'] ) ) );
		$disabled = '';
		$required = '';
		if( $args['disabled'] ) {
			$disabled = ' disabled="disabled"';
		}
		if( $args['required'] ) {
			$required = ' required';
		}

		$data = '';
		if ( ! empty( $args['data'] ) ) {
			foreach ( $args['data'] as $key => $value ) {
				$data .= 'data-' . mdjm_sanitize_key( $key ) . '="' . esc_attr( $value ) . '" ';
			}
		}

		$output = '<span id="mdjm-' . mdjm_sanitize_key( $args['name'] ) . '-wrap">';

		$output .= '<label for="' . mdjm_sanitize_key( $args['id'] ) . '">' . esc_html( $args['label'] ) . '</label>';

		$output .= '<input type="' . $args['type'] . '" name="' . esc_attr( $args['name'] ) . '" id="' . esc_attr( $args['id'] )  . '" autocomplete="' . esc_attr( $args['autocomplete'] )  . '" value="' . esc_attr( $args['value'] ) . '" placeholder="' . esc_attr( $args['placeholder'] ) . '" class="' . $class . '" ' . $data . '' . $disabled . '' . $required . '/>';
		
		$output .= '</span>';
		
		if ( ! empty( $args['desc'] ) ) {
			$output .= '<br />';
			$output .= '<span class="description">' . esc_html( $args['desc'] ) . '</span>';
		}

		return $output;
	} // text

	/**
	 * Renders a date picker
	 *
	 * @since	1.3.7
	 *
	 * @param	arr		$args	Arguments for the text field
	 * @return	str		Datepicker field
	 */
	public function date_field( $args = array() ) {

		if( empty( $args['class'] ) ) {
			$args['class'] = 'mdjm_datepicker';
		} elseif( ! strpos( $args['class'], 'mdjm_datepicker' ) ) {
			$args['class'] .= ' mdjm_datepicker';
		}

		return $this->text( $args );
	} // date_field

	/**
	 * Renders an HTML textarea
	 *
	 * @since	1.3.7
	 *
	 * @param	arr		$args	Arguments for the textarea
	 * @return	srt		textarea
	 */
	public function textarea( $args = array() ) {
		$defaults = array(
			'name'        => 'textarea',
			'value'       => null,
			'label'       => null,
			'placeholder' => null,
			'desc'        => null,
			'class'       => 'large-text',
			'disabled'    => false
		);

		$args = wp_parse_args( $args, $defaults );

		$class = implode( ' ', array_map( 'sanitize_html_class', explode( ' ', $args['class'] ) ) );
		$disabled = '';

		if( $args['disabled'] ) {
			$disabled = ' disabled="disabled"';
		}
		
		$placeholder = '';
		if( $args['placeholder'] ) {
			$placeholder = ' placeholder="' . esc_attr( $args['placeholder'] ) . '"';
		}

		$output = '<span id="mdjm-' . mdjm_sanitize_key( $args['name'] ) . '-wrap">';

			$output .= '<label for="' . mdjm_sanitize_key( $args['name'] ) . '">' . esc_html( $args['label'] ) . '</label>';

			$output .= '<textarea name="' . esc_attr( $args['name'] ) . '" id="' . mdjm_sanitize_key( $args['name'] ) . '" class="' . $class . '"' . $disabled . $placeholder . '>' . esc_attr( $args['value'] ) . '</textarea>';

			if ( ! empty( $args['desc'] ) ) {
				$output .= '<span class="mdjm-description">' . esc_html( $args['desc'] ) . '</span>';
			}

		$output .= '</span>';

		return $output;
	} // textarea
	
	/**
	 * Renders an HTML Number field
	 *
	 * @since	1.3.7
	 *
	 * @param	arr		$args	Arguments for the text field
	 * @return	str		Text field
	 */
	public function number( $args = array() ) {

		$defaults = array(
			'id'           => '',
			'name'         => isset( $name )  ? $name  : 'text',
			'value'        => isset( $value ) ? $value : null,
			'label'        => isset( $label ) ? $label : null,
			'desc'         => isset( $desc )  ? $desc  : null,
			'placeholder'  => '',
			'class'        => 'small-text',
			'min'          => '',
			'max'          => '',
			'disabled'     => false,
			'autocomplete' => '',
			'data'         => false
		);

		$args = wp_parse_args( $args, $defaults );
		
		$args['id'] = ! empty( $args['id'] ) ? $args['id'] : $args['name'];

		$class = implode( ' ', array_map( 'sanitize_html_class', explode( ' ', $args['class'] ) ) );
		$disabled = '';
		if( $args['disabled'] ) {
			$disabled = ' disabled="disabled"';
		}

		$data = '';
		if ( ! empty( $args['data'] ) ) {
			foreach ( $args['data'] as $key => $value ) {
				$data .= 'data-' . mdjm_sanitize_key( $key ) . '="' . esc_attr( $value ) . '" ';
			}
		}
		
		$min = ! empty( $args['min'] ) ? ' min="' . $args['min'] . '"' : '';
		$max = ! empty( $args['max'] ) ? ' max="' . $args['max'] . '"' : '';
		
		if ( $max > 5 )	{
			$max = 5;
		}

		$output = '<span id="mdjm-' . mdjm_sanitize_key( $args['name'] ) . '-wrap">';

			$output .= '<label for="' . mdjm_sanitize_key( $args['id'] ) . '">' . esc_html( $args['label'] ) . '</label>';

			if ( ! empty( $args['desc'] ) ) {
				$output .= '<span class="mdjm-description">' . esc_html( $args['desc'] ) . '</span>';
			}

			$output .= '<input type="number" name="' . esc_attr( $args['name'] ) . '" id="' . esc_attr( $args['id'] )  . '" autocomplete="' . esc_attr( $args['autocomplete'] )  . '" value="' . esc_attr( $args['value'] ) . '" placeholder="' . esc_attr( $args['placeholder'] ) . '" class="' . $class . '" ' . $data . '' . $min . '' . $max . '' . $disabled . '/>';

		$output .= '</span>';

		return $output;
	} // number
	
	/**
	 * Renders an HTML Hidden field
	 *
	 * @since	1.3.7
	 *
	 * @param	arr		$args	Arguments for the text field
	 * @return	str		Hidden field
	 */
	public function hidden( $args = array() ) {

		$defaults = array(
			'id'           => '',
			'name'         => isset( $name )  ? $name  : 'hidden',
			'value'        => isset( $value ) ? $value : null
		);

		$args = wp_parse_args( $args, $defaults );
		
		$args['id'] = ! empty( $args['id'] ) ? $args['id'] : $args['name'];

		$output = '<input type="hidden" name="' . esc_attr( $args['name'] ) . '" id="' . esc_attr( $args['id'] )  . '" value="' . esc_attr( $args['value'] ) . '" />';

		return $output;
	} // hidden

	/**
	 * Renders an ajax user search field
	 *
	 * @since	1.3.7
	 *
	 * @param	arr		$args
	 * @return	str		Text field with ajax search
	 */
	public function ajax_user_search( $args = array() ) {

		$defaults = array(
			'name'        => 'user_id',
			'value'       => null,
			'placeholder' => __( 'Enter username', 'mobile-dj-manager' ),
			'label'       => null,
			'desc'        => null,
			'class'       => '',
			'disabled'    => false,
			'autocomplete'=> 'off',
			'data'        => false
		);

		$args = wp_parse_args( $args, $defaults );

		$args['class'] = 'mdjm-ajax-user-search ' . $args['class'];

		$output  = '<span class="mdjm_user_search_wrap">';
			$output .= $this->text( $args );
			$output .= '<span class="mdjm_user_search_results hidden"><a class="mdjm-ajax-user-cancel" title="' . __( 'Cancel', 'mobile-dj-manager' ) . '" aria-label="' . __( 'Cancel', 'mobile-dj-manager' ) . '" href="#">x</a><span></span></span>';
		$output .= '</span>';

		return $output;
	} // ajax_user_search

} // MDJM_HTML_Elements
