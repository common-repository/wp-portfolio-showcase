<?php

namespace Prince\Settings;

/**
 * Prince Meta Box API
 *
 * This class loads all the methods and helpers specific to build a meta box.
 *
 * @package   Prince
 * @author    Prince Ahmed <israilahmed5@gmail.com>
 * @copyright Copyright (c) 2019, Prince Ahmed
 */
if ( ! class_exists( 'MetaBox' ) ) {

	class MetaBox {

		/* variable to store the meta box array */
		private $meta_box;

		/**
		 * PHP5 constructor method.
		 *
		 * This method adds other methods of the class to specific hooks within WordPress.
		 *
		 * @uses      add_action()
		 *
		 * @return    void
		 *
		 * @access    public
		 * @since     1.0
		 */
		function __construct( $meta_box ) {
			if ( ! is_admin() ) {
				return;
			}

			global $prince_meta_boxes;

			if ( ! isset( $prince_meta_boxes ) ) {
				$prince_meta_boxes = array();
			}

			$prince_meta_boxes[] = $meta_box;

			$this->meta_box = $meta_box;

			add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );

			add_action( 'save_post', array( $this, 'save_meta_box' ), 1, 2 );

		}

		/**
		 * Adds meta box to any post type
		 *
		 * @uses      add_meta_box()
		 *
		 * @return    void
		 *
		 * @access    public
		 * @since     1.0
		 */
		function add_meta_boxes() {
			foreach ( (array) $this->meta_box['pages'] as $page ) {
				add_meta_box( $this->meta_box['id'], $this->meta_box['title'], array(
					$this,
					'build_meta_box'
				), $page, $this->meta_box['context'], $this->meta_box['priority'], $this->meta_box['fields'] );
			}
		}

		/**
		 * Meta box view
		 *
		 * @return    string
		 *
		 * @access    public
		 * @since     1.0
		 */
		function build_meta_box( $post, $metabox ) {

			echo '<div class="prince-metabox-wrapper">';

			/* Use nonce for verification */
			echo '<input type="hidden" name="' . $this->meta_box['id'] . '_nonce" value="' . wp_create_nonce( $this->meta_box['id'] ) . '" />';

			/* meta box description */
			echo isset( $this->meta_box['desc'] ) && ! empty( $this->meta_box['desc'] ) ? '<div class="description" style="padding-top:10px;">' . htmlspecialchars_decode( $this->meta_box['desc'] ) . '</div>' : '';

			/* loop through meta box fields */
			foreach ( $this->meta_box['fields'] as $field ) {

				/* get current post meta data */
				$field_value = get_post_meta( $post->ID, $field['id'], true );

				/* set standard value */
				if ( isset( $field['std'] ) ) {
					$field_value = prince_filter_std_value( $field_value, $field['std'] );
				}

				/* build the arguments array */
				$_args = array(
					'type'               => $field['type'],
					'field_id'           => $field['id'],
					'field_name'         => $field['id'],
					'field_value'        => $field_value,
					'field_desc'         => isset( $field['desc'] ) ? $field['desc'] : '',
					'field_std'          => isset( $field['std'] ) ? $field['std'] : '',
					'field_rows'         => isset( $field['rows'] ) && ! empty( $field['rows'] ) ? $field['rows'] : 10,
					'field_post_type'    => isset( $field['post_type'] ) && ! empty( $field['post_type'] ) ? $field['post_type'] : 'post',
					'field_taxonomy'     => isset( $field['taxonomy'] ) && ! empty( $field['taxonomy'] ) ? $field['taxonomy'] : 'category',
					'field_min_max_step' => isset( $field['min_max_step'] ) && ! empty( $field['min_max_step'] ) ? $field['min_max_step'] : '0,100,1',
					'field_class'        => isset( $field['class'] ) ? $field['class'] : '',
					'field_condition'    => isset( $field['condition'] ) ? $field['condition'] : '',
					'field_operator'     => isset( $field['operator'] ) ? $field['operator'] : 'and',
					'field_choices'      => isset( $field['choices'] ) ? $field['choices'] : array(),
					'field_settings'     => isset( $field['settings'] ) && ! empty( $field['settings'] ) ? $field['settings'] : array(),
					'field_attrs'        => '',
					'post_id'            => $post->ID,
					'meta'               => true
				);

				if ( isset($field['attrs']) && ! empty( array_filter( $field['attrs'] ) ) ) {
					$attrs = '';
					foreach ( array_filter( $field['attrs'] ) as $key => $value ) {
						$attrs .= ' ' . $key . '="' . $value . '" ';
					}

					$_args['field_attrs'] = $attrs;


				}

				$conditions = '';

				/* setup the conditions */
				if ( isset( $field['condition'] ) && ! empty( $field['condition'] ) ) {

					$conditions = ' data-condition="' . $field['condition'] . '"';
					$conditions .= isset( $field['operator'] ) && in_array( $field['operator'], array(
						'and',
						'AND',
						'or',
						'OR'
					) ) ? ' data-operator="' . $field['operator'] . '"' : '';

				}

				/* only allow simple textarea due to DOM issues with wp_editor() */
				if ( apply_filters( 'prince_override_forced_textarea_simple', false, $field['id'] ) == false && $_args['type'] == 'textarea' ) {
					//$_args['type'] = 'textarea-simple';
				}

				// Build the setting CSS class
				if ( ! empty( $_args['field_class'] ) ) {

					$classes = explode( ' ', $_args['field_class'] );

					foreach ( $classes as $key => $value ) {

						$classes[ $key ] = $value . '-wrap';

					}

					$class = 'format-settings ' . implode( ' ', $classes );

				} else {

					$class = 'format-settings';

				}

				/* option label */
				echo '<div id="setting_' . $field['id'] . '" class="' . $class . '"' . $conditions . '>';

				echo '<div class="format-setting-wrap">';

				/* don't show title with textblocks */
				if ( $_args['type'] != 'textblock' && ! empty( $field['label'] ) ) {
					echo '<div class="format-setting-label">';
					echo '<label for="' . $field['id'] . '" class="label">' . $field['label'] . '</label>';
					echo '</div>';
				}

				/* get the option HTML */
				echo prince_display_by_type( $_args );

				echo '</div>';

				echo '</div>';

			}

			echo '<div class="clear"></div>';

			echo '</div>';

		}

		/**
		 * Saves the meta box values
		 *
		 * @return    integer
		 *
		 * @access    public
		 * @since     1.0
		 */
		function save_meta_box( $post_id, $post_object ) {

			global $pagenow;

			/* don't save if $_POST is empty */
			if ( empty( $_POST ) || ( isset( $_POST['vc_inline'] ) && esc_attr($_POST['vc_inline']) == true ) ) {
				return $post_id;
			}

			/* don't save during quick edit */
			if ( $pagenow == 'admin-ajax.php' ) {
				return $post_id;
			}

			/* don't save during autosave */
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return $post_id;
			}

			/* don't save if viewing a revision */
			if ( $post_object->post_type == 'revision' || $pagenow == 'revision.php' ) {
				return $post_id;
			}

			/* verify nonce */
			if ( isset( $_POST[ $this->meta_box['id'] . '_nonce' ] ) && ! wp_verify_nonce( $_POST[ $this->meta_box['id'] . '_nonce' ], $this->meta_box['id'] ) ) {
				return $post_id;
			}

			/* check permissions */
			if ( isset( $_POST['post_type'] ) && 'page' == esc_attr($_POST['post_type']) ) {
				if ( ! current_user_can( 'edit_page', $post_id ) ) {
					return $post_id;
				}
			} else {
				if ( ! current_user_can( 'edit_post', $post_id ) ) {
					return $post_id;
				}
			}

			foreach ( $this->meta_box['fields'] as $field ) {

				$old = get_post_meta( $post_id, $field['id'], true );
				$new = '';

				/* there is data to validate */
				if ( isset( $_POST[ $field['id'] ] ) ) {

					/* slider and list item */
					if ( in_array( $field['type'], array( 'list-item', 'slider' ) ) ) {

						/* required title setting */
						$required_setting = array(
							array(
								'id'        => 'title',
								'label'     => __( 'Title', 'wp-portfolio-showcase' ),
								'desc'      => '',
								'std'       => '',
								'type'      => 'text',
								'rows'      => '',
								'class'     => 'prince-setting-title',
								'post_type' => '',
								'choices'   => array()
							)
						);

						/* get the settings array */
						$settings = isset( $_POST[ $field['id'] . '_settings_array' ] ) ? unserialize( prince_decode( esc_attr($_POST[ $field['id'] . '_settings_array' ]) ) ) : array();

						/* settings are empty for some odd ass reason get the defaults */
						if ( empty( $settings ) ) {
							$settings = 'slider' == $field['type'] ?
								prince_slider_settings( $field['id'] ) :
								prince_list_item_settings( $field['id'] );
						}

						/* merge the two settings array */
						$settings = array_merge( $required_setting, $settings );

						foreach ( $_POST[ $field['id'] ] as $k => $setting_array ) {

							foreach ( $settings as $sub_setting ) {

								/* verify sub setting has a type & value */
								if ( isset( $sub_setting['type'] ) && isset( $_POST[ $field['id'] ][ $k ][ $sub_setting['id'] ] ) ) {

									$_POST[ $field['id'] ][ $k ][ $sub_setting['id'] ] = prince_validate_setting( $_POST[ $field['id'] ][ $k ][ $sub_setting['id'] ], $sub_setting['type'], $sub_setting['id'] );

								}

							}

						}

						/* set up new data with validated data */
						$new = prince_validate_setting( $_POST[ $field['id'] ], $field['type'], $field['id'] );

					} else if ( $field['type'] == 'social-links' ) {

						/* get the settings array */
						$settings = isset( $_POST[ $field['id'] . '_settings_array' ] ) ? unserialize( prince_decode( $_POST[ $field['id'] . '_settings_array' ] ) ) : array();

						/* settings are empty get the defaults */
						if ( empty( $settings ) ) {
							$settings = prince_social_links_settings( $field['id'] );
						}

						foreach ( $_POST[ $field['id'] ] as $k => $setting_array ) {

							foreach ( $settings as $sub_setting ) {

								/* verify sub setting has a type & value */
								if ( isset( $sub_setting['type'] ) && isset( $_POST[ $field['id'] ][ $k ][ $sub_setting['id'] ] ) ) {

									$_POST[ $field['id'] ][ $k ][ $sub_setting['id'] ] = prince_validate_setting( $_POST[ $field['id'] ][ $k ][ $sub_setting['id'] ], $sub_setting['type'], $sub_setting['id'] );

								}

							}

						}

						/* set up new data with validated data */
						$new = prince_validate_setting( esc_attr($_POST[ $field['id'] ]), $field['type'], $field['id'] );

					} else {

						/* run through validattion */
						$new = prince_validate_setting( esc_attr($_POST[ $field['id'] ]), $field['type'], $field['id'] );

					}

					/* insert CSS */
					if ( $field['type'] == 'css' ) {

						/* insert CSS into dynamic.css */
						if ( '' !== $new ) {

							prince_insert_css_with_markers( $field['id'], $new, true );

							/* remove old CSS from dynamic.css */
						} else {

							prince_remove_old_css( $field['id'] );

						}

					}

				}

				if ( isset( $new ) && $new !== $old ) {
					update_post_meta( $post_id, $field['id'], $new );
				} else if ( '' == $new && $old ) {
					delete_post_meta( $post_id, $field['id'], $old );
				}
			}

		}

	}

}
