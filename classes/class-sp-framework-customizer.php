<?php
class SP_Framework_Customizer extends SP_Framework_Main {

	function __construct() {
		$this->init();
	}

	private function init() {
		add_action(
			'customize_register',
			function( $wpCustomize ) {
				$panel = $this->args;

				$wpCustomize->add_panel(
					$panel['name'],
					array(
						'priority'       => $panel['priority'],
						'capability'     => 'edit_theme_options',
						'description'    => $panel['description'],
						'theme_supports' => '',
						'title'          => $panel['title'],
					)
				);

				if ( isset( $panel['section'] ) ) {
					$counter = 0;
					foreach ( $panel['section'] as $section ) {
						$counter++;

						if ( isset( $section['description'] ) ) {
							$description = $section['description'];
						} else {
							$description = '';
						}

						$wpCustomize->add_section(
							$section['name'],
							array(
								'title'       => $section['title'],
								'priority'    => $counter,
								'panel'       => $panel['name'],
								'description' => $description,
							)
						);

						if ( isset( $section['fields'] ) ) {
							foreach ( $section['fields'] as $fields ) {

								$sanitizeCallback = '';

								if ( $fields['type'] == 'input' ) {
									$sanitizeCallback = 'sanitize_text_field';
								}

								if ( $fields['type'] == 'textarea' ) {
									$sanitizeCallback = function( $input ) {

										$allowed_html = array(
											'h1'     => array(),
											'h2'     => array(),
											'h3'     => array(),
											'p'      => array(),
											'a'      => array(
												'href'  => true,
												'title' => true,
											),
											'br'     => array(),
											'em'     => array(),
											'strong' => array(),
											'dl'     => array(),
											'dt'     => array(),
											'dd'     => array(),
											'b'      => array(),
											'i'      => array(),
										);

										return wp_kses( $input, $allowed_html );

									};
								}

								if ( $fields['type'] == 'checkbox' ) {
									$sanitizeCallback = function( $input ) {
										if ( $input == 1 ) {
											return 1;
										} else {
											return '';
										}
									};
								}

								if ( $fields['type'] == 'image' ) {

									$wpCustomize->add_setting(
										$fields['name'],
										array(
											'capability' => 'edit_theme_options',
											'sanitize_callback' => 'esc_url_raw',
											'default'    => '',
										)
									);

									$wpCustomize->add_control(
										new WP_Customize_Image_Control(
											$wpCustomize,
											$fields['name'],
											array(
												'label'    => $fields['label'],
												'section'  => $section['name'],
												'settings' => $fields['name'],
											)
										)
									);

								} else {

									if ( isset( $fields['sanitize'] ) && $fields['sanitize'] == 'y' ) {
										$wpCustomize->add_setting(
											$fields['name'],
											array(
												'capability' => 'edit_theme_options',
												'default' => '',
												'sanitize_callback' => $sanitizeCallback,
											)
										);
									} else {
										$wpCustomize->add_setting(
											$fields['name'],
											array(
												'capability' => 'edit_theme_options',
												'default' => '',
												'sanitize_callback' => '',
											)
										);
									}

									$wpCustomize->add_control(
										$fields['name'],
										array(
											'type'    => $fields['type'],
											'section' => $section['name'],
											'label'   => $fields['label'],
										)
									);

								}
							}
						} else {
							echo esc_html__( '$args[fields] is empty!', 'spf86' );
							wp_die();
						}
					}
				} else {
					echo esc_html__( '$args[section] is empty!', 'spf86' );
					wp_die();
				}
			}
		);
	}
}
