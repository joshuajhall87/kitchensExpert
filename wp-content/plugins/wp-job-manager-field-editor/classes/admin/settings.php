<?php

if ( ! defined( 'ABSPATH' ) ) exit;

require_once( WPJM_FIELD_EDITOR_PLUGIN_DIR . '/classes/admin/settings-fields.php' );
require_once( WPJM_FIELD_EDITOR_PLUGIN_DIR . '/classes/admin/settings-handlers.php' );

/**
 * Class WP_Job_Manager_Field_Editor_Settings
 *
 * @since 1.1.9
 *
 */
class WP_Job_Manager_Field_Editor_Settings extends WP_Job_Manager_Field_Editor_Settings_Handlers {

	// Start Workspace
	protected $settings;
	protected $settings_group;
	protected $process_count;
	protected $button_count;
	protected $field_data;

	function __construct() {

		$this->settings_group = 'job_manager_field_editor';
		$this->process_count = 0;
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'wp_ajax_jmfe_get_field_data', array( $this, 'get_field_data' ) );

	}

	/**
	 * Get Field Data through AJAX
	 *
	 *
	 * @since @@since
	 *
	 */
	public function get_field_data() {

		$response = array();
		check_ajax_referer( 'jmfe_get_field_data', 'nonce' );

		if ( ob_get_length() ) ob_end_clean();
		ob_start();

		$response[ 'status' ] = 'success';
		$response[ 'body' ]   = $this->fields()->dump_array( $this->field_data(), TRUE );

		if ( ob_get_length() ) ob_end_clean();

		echo json_encode( $response );

		die();

	}

	/**
	 * Output Settings HTML
	 *
	 *
	 * @since 1.1.9
	 *
	 */
	function output() {

		$this->init_settings();
		?>
		<div class="wrap">

			<div id="icon-themes" class="icon32"></div>
			<h2><?php _e( 'Field Editor Settings', 'wp-job-manager-field-editor' ); ?></h2>

			<form method="post" action="options.php">

				<?php
				settings_errors();
				settings_fields( $this->settings_group );
				?>

				<h2 class="nav-tab-wrapper">
					<?php
					foreach ( $this->settings as $key => $section ) {
						echo '<a href="#settings-' . sanitize_title( $key ) . '" class="nav-tab">' . esc_html( $section[ 0 ] ) . '</a>';
					}
					?>
				</h2>
				<div id="jmfe-all-settings">
					<?php
						foreach ( $this->settings as $key => $section ) {
							echo "<div id=\"settings-{$key}\" class=\"settings_panel\">";
							do_settings_sections( "jmfe_{$key}_section" );
							echo "</div>";
						}
						submit_button();
					?>
				</div>
			</form>

		</div>

		<script type="text/javascript">
			jQuery( '.nav-tab-wrapper a' ).click(
				function () {
					jQuery( '.settings_panel' ).hide();
					jQuery( '.nav-tab-active' ).removeClass( 'nav-tab-active' );
					jQuery( jQuery( this ).attr( 'href' ) ).show();
					jQuery( this ).addClass( 'nav-tab-active' );
					return false;
				}
			);

			jQuery( '.nav-tab-wrapper a:first' ).click();
		</script>
		<style>
			.jmfe-settings-separator {
				border-top: 2px solid #ffffff;
			}
		</style>
	<?php
	}

	/**
	 * Initialize Settings Array
	 *
	 *
	 * @since 1.1.9
	 *
	 */
	function init_settings() {

		$job_singular = WP_Job_Manager_Field_Editor::get_job_post_label();

		$this->settings = apply_filters(
			'job_manager_field_editor_settings',
			array(
				'job'     => array(
					$job_singular,
					array(
						array(
							'name'       => 'jmfe_enable_required_label',
							'std'        => '0',
							'label'      => __( 'Custom Required Label', 'wp-job-manager-field-editor' ),
							'cb_label'   => __( 'Enable', 'wp-job-manager-field-editor' ),
							'desc'       => __( 'Use a custom label for required fields instead of optional fields. HTML is supported.', 'wp-job-manager-field-editor' ),
							'type'       => 'checkbox',
							'attributes' => array()
						),
						array(
							'name'        => 'jmfe_required_label',
							'label'       => __( 'Required Field Label', 'wp-job-manager-field-editor' ),
							'type'        => 'textbox',
							'handler'     => 'esc_html',
							'placeholder' => __( '<small>(required)</small>', 'wp-job-manager-field-editor' )
						),
						array(
							'name'       => 'jmfe_enable_optional_label',
							'std'        => '0',
							'label'      => __( 'Custom Optional Label', 'wp-job-manager-field-editor' ),
							'cb_label'   => __( 'Enable', 'wp-job-manager-field-editor' ),
							'desc'       => __( 'Use a custom label for optional fields instead of required fields. HTML is supported. (default is optional)', 'wp-job-manager-field-editor' ),
							'type'       => 'checkbox',
							'class'      => 'jmfe-settings-separator',
							'attributes' => array()
						),
						array(
							'name'        => 'jmfe_optional_label',
							'label'       => __( 'Optional Field Label', 'wp-job-manager-field-editor' ),
							'type'        => 'textbox',
							'handler'     => 'esc_html',
							'placeholder' => __( '<small>(optional)</small>', 'wp-job-manager-field-editor' )
						),
						array(
							'name'       => 'jmfe_enable_job_submit_button',
							'std'        => '0',
							'label'      => __( 'Custom Submit Button', 'wp-job-manager-field-editor' ),
							'cb_label'   => __( 'Enable', 'wp-job-manager-field-editor' ),
							'desc'       => __( 'Use a custom value for the submit button (on initial submit page)', 'wp-job-manager-field-editor' ),
							'type'       => 'checkbox',
							'class'      => 'jmfe-settings-separator',
							'attributes' => array()
						),
						array(
							'name'        => 'jmfe_job_submit_button',
							'label'       => __( 'Submit Button Caption', 'wp-job-manager-field-editor' ),
							'type'        => 'textbox',
							'placeholder' => __( 'Preview &rarr;', 'wp-job-manager-field-editor' ),
							'desc'        => __( 'The default button is <code>Preview &rarr;</code>, use <code>&amp;rarr;</code> for the arrow', 'wp-job-manager-field-editor' )
						)
					)
				),
				'resume'  => array(
					__( 'Resume', 'wp-job-manager-field-editor' ),
					array(
						array(
							'name'       => 'jmfe_enable_resume_required_label',
							'std'        => '0',
							'label'      => __( 'Custom Required Label', 'wp-job-manager-field-editor' ),
							'cb_label'   => __( 'Enable', 'wp-job-manager-field-editor' ),
							'desc'       => __( 'Use a custom label for required fields instead of optional fields. HTML is supported.', 'wp-job-manager-field-editor' ),
							'type'       => 'checkbox',
							'attributes' => array()
						),
						array(
							'name'        => 'jmfe_resume_required_label',
							'label'       => __( 'Required Field Label', 'wp-job-manager-field-editor' ),
							'type'        => 'textbox',
							'handler'     => 'esc_html',
							'placeholder' => __( '<small>(required)</small>', 'wp-job-manager-field-editor' )
						),
						array(
							'name'       => 'jmfe_enable_resume_optional_label',
							'std'        => '0',
							'label'      => __( 'Custom Optional Label', 'wp-job-manager-field-editor' ),
							'cb_label'   => __( 'Enable', 'wp-job-manager-field-editor' ),
							'desc'       => __( 'Use a custom label for optional fields instead of required fields. HTML is supported. (default is optional)', 'wp-job-manager-field-editor' ),
							'type'       => 'checkbox',
							'class'      => 'jmfe-settings-separator',
							'attributes' => array()
						),
						array(
							'name'        => 'jmfe_resume_optional_label',
							'label'       => __( 'Optional Field Label', 'wp-job-manager-field-editor' ),
							'type'        => 'textbox',
							'handler'     => 'esc_html',
							'placeholder' => __( '<small>(optional)</small>', 'wp-job-manager-field-editor' )
						),
						array(
							'name'       => 'jmfe_enable_resume_submit_button',
							'std'        => '0',
							'label'      => __( 'Custom Submit Button', 'wp-job-manager-field-editor' ),
							'cb_label'   => __( 'Enable', 'wp-job-manager-field-editor' ),
							'desc'       => __( 'Use a custom value for the submit button (on initial submit page)', 'wp-job-manager-field-editor' ),
							'type'       => 'checkbox',
							'class'      => 'jmfe-settings-separator',
							'attributes' => array()
						),
						array(
							'name'        => 'jmfe_resume_submit_button',
							'label'       => __( 'Submit Button Caption', 'wp-job-manager-field-editor' ),
							'type'        => 'textbox',
							'placeholder' => __( 'Preview &rarr;', 'wp-job-manager-field-editor' ),
							'desc'        => __( 'The default button is <code>Preview &rarr;</code>, use <code>&amp;rarr;</code> for the arrow', 'wp-job-manager-field-editor' )
						),
						array(
								'name'       => 'jmfe_enable_resume_candidate_photo_multiple',
								'std'        => '0',
								'label'      => __( 'Multiple Candidate Photos', 'wp-job-manager-field-editor' ),
								'cb_label'   => __( 'Yes, allow', 'wp-job-manager-field-editor' ) . ' <code>candidate_photo</code> ' . __( 'to be set as multiple file uploads', 'wp-job-manager-field-editor' ),
								'desc'       => __( 'By default the', 'wp-job-manager-field-editor' ) . ' <code>candidate_photo</code> ' . __( 'field is a single upload.  Enable this option to allow changing this to multiple files, but be aware that there may be issues with the list in admin, etc.  Use at your own risk.', 'wp-job-manager-field-editor' ),
								'type'       => 'checkbox',
								'class'      => 'jmfe-settings-separator',
								'attributes' => array()
						),
					)
				),
				'recaptcha'  => array(
					__( 'reCAPTCHA', 'wp-job-manager-field-editor' ),
					array(
						array(
							'name'        => 'jmfe_recaptcha_site_key',
							'label'       => __( 'Site Key', 'wp-job-manager-field-editor' ),
							'type'        => 'textbox',
							'placeholder' => '',
							'desc'	=> sprintf( __( 'Required to use reCAPTCHA, you can get one from <a href="%s" target="_blank">Google</a>.', 'wp-job-manager-field-editor' ), 'https://www.google.com/recaptcha/admin#list' )
						),
						array(
							'name'        => 'jmfe_recaptcha_secret_key',
							'label'       => __( 'Secret Key', 'wp-job-manager-field-editor' ),
							'type'        => 'textbox',
							'placeholder' => '',
							'desc'	=> sprintf( __( 'Required to use reCAPTCHA, you can get one from <a href="%s" target="_blank">Google</a>.', 'wp-job-manager-field-editor' ), 'https://www.google.com/recaptcha/admin#list' )
						),
						array(
							'name'        => 'jmfe_recaptcha_label',
							'label'       => __( 'Label', 'wp-job-manager-field-editor' ),
							'type'        => 'textbox',
							'std'         => __( "Are you human?", 'wp-job-manager-field-editor' ),
							'placeholder' => '',
							'desc'        => __( 'This value will be used as the label that shows next to the actual reCAPTCHA', 'wp-job-manager-field-editor' )
						),
						array(
							'name'        => 'jmfe_recaptcha_theme',
							'label'       => __( 'Theme', 'wp-job-manager-field-editor' ),
							'type'        => 'select',
							'options'     => array(
								'light' => __( 'Light', 'wp-job-manager-field-editor' ),
								'dark'  => __( 'Dark', 'wp-job-manager-field-editor' )
							),
							'placeholder' => '',
							'desc'        => __( 'By default the light theme is used, changed this to dark if you wish to use the dark theme.', 'wp-job-manager-field-editor' ),
							'class' => 'jmfe-settings-separator',
						),
						array(
							'name'        => 'jmfe_recaptcha_size',
							'label'       => __( 'Size', 'wp-job-manager-field-editor' ),
							'type'        => 'select',
							'options'     => array(
								'normal'  => __( 'Normal', 'wp-job-manager-field-editor' ),
								'compact' => __( 'Compact', 'wp-job-manager-field-editor' )
							),
							'placeholder' => '',
							'desc'        => __( 'By default the normal size is used.  Change this to compact to show a smaller recaptcha.', 'wp-job-manager-field-editor' )
						),
						array(
							'name'        => 'jmfe_recaptcha_type',
							'label'       => __( 'Type', 'wp-job-manager-field-editor' ),
							'type'        => 'select',
							'options'     => array(
								'image' => __( 'Image', 'wp-job-manager-field-editor' ),
								'audio' => __( 'Audio', 'wp-job-manager-field-editor' )
							),
							'placeholder' => '',
							'desc'        => __( 'Images are used as the default reCAPTCHA verification, select Audio to change the default to audio. Visitors will still be able to use either audio or images, this setting sets the default type shown initially.', 'wp-job-manager-field-editor' )
						),
						array(
							'name'         => 'jmfe_recaptcha_force_language',
							'std'          => '0',
							'label'        => __( 'Force Language', 'wp-job-manager-field-editor' ),
							'cb_label'     => __( 'Yes, force custom language (disables Google reCAPTCHA auto detection)', 'wp-job-manager-field-editor' ),
							'desc'         => __( 'Google reCAPTCHA auto-detects the user\'s language by default, enable this setting to use language returned by WordPress, or a custom language set below.', 'wp-job-manager-field-editor' ),
							'type'         => 'checkbox',
							'attributes'   => array(),
							'child_fields' => array( 'jmfe_recaptcha_language' ),
							'class' => 'jmfe-settings-separator',
						),
						array(
							'name'        => 'jmfe_recaptcha_language',
							'label'       => "   &#8627; " . __( 'Custom', 'wp-job-manager-field-editor' ),
							'type'        => 'select',
							'options'     => WP_Job_Manager_Field_Editor_reCAPTCHA::language_codes( TRUE ),
							'placeholder' => '',
							'desc'        => sprintf( __( 'Select <strong>%1$s</strong> to force the widget to render in the language returned by <a href="%2$s" target="_blank"><code>get_locale()</code></a>, which will be converted to a <a href="%4$s" target="_blank">Google reCAPTCHA supported language</a>, which is <strong>(%5$s)</strong> for the current locale of <strong>(%3$s)</strong>.  Otherwise, select a specific language to force the reCAPTCHA to use.', 'wp-job-manager-field-editor' ), __( 'Auto (based on WordPress get_locale)', 'wp-job-manager-field-editor' ), 'https://developer.wordpress.org/reference/functions/get_locale/', get_locale(), 'https://developers.google.com/recaptcha/docs/language', WP_Job_Manager_Field_Editor_reCAPTCHA::get_locale_code( TRUE ) ),
							'class'       => ''
						),
						array(
								'name'       => 'jmfe_recaptcha_enable_job',
								'std'        => '0',
								'label'      => sprintf( __( '%s Submit Page', 'wp-job-manager-field-editor' ), $job_singular ),
								'cb_label'   => __( 'Enable', 'wp-job-manager-field-editor' ),
								'desc'       => sprintf( __( 'If enabled, a reCAPTCHA form will be added to the bottom of the submit %1$s page. See the <a target="_blank" href="%2$s">WP Job Manager Documentation</a> page.', 'wp-job-manager-field-editor' ), $job_singular, 'https://wpjobmanager.com/document/tutorial-adding-recaptcha-job-submission-form/' ),
								'type'       => 'checkbox',
								'attributes' => array(),
								'child_fields' => array( 'jmfe_recaptcha_output_after_job' ),
								'class' => 'jmfe-settings-separator',
						),
						array(
								'name'       => 'jmfe_recaptcha_output_after_job',
								'std'        => '0',
								'label'      => "   &#8627; " . __( 'Output Location', 'wp-job-manager-field-editor' ),
								'cb_label'   => sprintf( __( 'After %s fields', 'wp-job-manager-field-editor' ), $job_singular ),
								'desc'       => sprintf( __( 'Select this checkbox to output the reCAPTCHA after the %s fields.  You should only need to do this if you have disabled all company fields, or prefer to have the reCAPTCHA above company fields.', 'wp-job-manager-field-editor' ), $job_singular ),
								'type'       => 'checkbox',
								'class'      => '',
								'attributes' => array()
						),
						array(
								'name'       => 'jmfe_recaptcha_enable_resume',
								'std'        => '0',
								'label'      => __( 'Resume Submit Page', 'wp-job-manager-field-editor' ),
								'cb_label'   => __( 'Enable', 'wp-job-manager-field-editor' ),
								'desc'       => sprintf( __( 'If enabled, a reCAPTCHA form will be added to the bottom of the submit resume page. See the <a target="_blank" href="%s">WP Job Manager Documentation</a> page.', 'wp-job-manager-field-editor' ), 'https://wpjobmanager.com/document/tutorial-adding-recaptcha-job-submission-form/' ),
								'type'       => 'checkbox',
								'attributes' => array(),
								'class' => 'jmfe-settings-separator',
						),
					)
				),
				'fields' => array(
					__( 'Fields', 'wp-job-manager-field-editor' ),
					array(
						array(
							'name'  => 'jmfe_fields_dp_saveas',
							'type'  => 'select',
							'label'     => __( 'Date Save Format', 'wp-job-manager-field-editor' ),
							'desc'        => __( 'Select the type of format you want the date picker field to save as.  Default is WordPress Date Format (defined in WordPress settings).', 'wp-job-manager-field-editor' ),
							'options' => array(
								'default' => __( 'WordPress Date Format', 'wp-job-manager-field-editor' ),
								'epoch'		=> __( 'Epoch Timestamp ', 'wp-job-manager-field-editor' ) . '( Ex: ' . time() . ' )',
								'iso' 		=> __( 'ISO 8601 ', 'wp-job-manager-field-editor' ) . '( Ex: ' . date( 'c' ) . ' )',
								'rfc' 		=> __( 'RFC 2822 ', 'wp-job-manager-field-editor' ) . '( Ex: ' . date( 'r' ) . ' )',
								'rfc' 		=> __( 'MySQL DATETIME ', 'wp-job-manager-field-editor' ) . '( Ex: ' . date( 'Y-m-d H:i:s' ) . ' )',
								'Ymd'	=> __( 'PHP Ymd | JS: yymmdd ', 'wp-job-manager-field-editor' ) . '( Ex: ' . date( 'Ymd' ) . ' )',
								'ymd'	=> __( 'PHP ymd | JS: ymmdd ', 'wp-job-manager-field-editor' ) . '( Ex: ' . date( 'ymd' ) . ' )',
								'custom'	=> __( 'Custom Format (set below)', 'wp-job-manager-field-editor' ),
							)
						),
						array(
								'name'        => 'jmfe_fields_dp_custom',
								'label'       => __( 'Date Save Custom Format', 'wp-job-manager-field-editor' ),
								'type'        => 'textbox',
								'std'         => '',
								'placeholder' => get_option( 'date_format' ),
								'desc'        => __( 'If you chose Custom Format above, enter the PHP custom format here', 'wp-job-manager-field-editor' )
						),
						array(
								'name'       => 'jmfe_fields_dp_i18n',
								'std'        => '0',
								'label'      => __( 'Date Display', 'wp-job-manager-field-editor' ),
								'cb_label'   => __( 'Yes, use <code>date_i18n()</code>', 'wp-job-manager-field-editor' ),
								'desc'       => sprintf( __( 'Enable this to use the core WordPress <a href="%s" target="_blank"><code>date_i18n()</code></a> function to attempt translation/format of date based on locale. Default value is unchecked.', 'wp-job-manager-field-editor' ), 'https://codex.wordpress.org/Function_Reference/date_i18n' ),
								'type'       => 'checkbox',
								'attributes' => array()
						),
						array(
								'name'       => 'jmfe_fields_options_output_label',
								'std'        => '0',
								'label'      => __( 'Option Labels', 'wp-job-manager-field-editor' ),
								'cb_label'   => __( 'Yes, output/return the option Label/Caption instead of the value', 'wp-job-manager-field-editor' ),
								'desc'       => __( 'By default, when using any field type that has options (Dropdown, Radio Buttons, Multi-Select), the <strong>value</strong> is saved to the listing, and that is what is output when using any of the built-in methods.  Enable this setting to output/return the Label/Caption instead of the value.', 'wp-job-manager-field-editor' ),
								'type'       => 'checkbox',
								'attributes' => array(),
								'class' => 'jmfe-settings-separator'
						),
						array(
								'name'        => 'jmfe_force_update_string_i18n',
								'caption'     => __( 'Force Register String Translations', 'wp-job-manager-field-editor' ),
								'field_class' => 'button-primary',
								'action'      => 'force_register_i18n',
								'label'       => __( 'String Translation', 'wp-job-manager-field-editor' ),
								'desc'        => __( 'This is specifically for WPML or Polylang.  Only use this if you know you need to.  This will force loop through all custom fields and register all supported translatable values in string translation.', 'wp-job-manager-field-editor' ),
								'type'        => 'button',
								'class'       => 'jmfe-settings-separator'
						),
					),
				),
				'output' => array(
					__( 'Output', 'wp-job-manager-field-editor' ),
					array(
						array(
							'name'  => 'jmfe_output_wpautop',
							'type'  => 'checkboxes',
							'label'     => __( 'Auto Paragraph', 'wp-job-manager-field-editor' ),
							'desc'        => __( 'Select which field types you want to automatically add paragraphs to, when using one of the built-in output methods.', 'wp-job-manager-field-editor' ),
							'options' => array(
								'wp-editor' => array(
										'label' => __( 'WP-Editor', 'wp-job-manager-field-editor' ),
										'std'	=> 1,
								),
								'textarea' => array(
										'label' => __( 'Text Area', 'wp-job-manager-field-editor' ),
										'std'	=> 1,
								)
							)
						),
						array(
							'name'  => 'jmfe_output_as_link_url_scheme',
							'type'  => 'checkbox',
							'label' => __( 'As Link URL Scheme', 'wp-job-manager-field-editor' ),
							'desc'  => __( 'Enable this option to automatically prepend <code>http://</code> if it does not exist, when using the output as Link feature.', 'wp-job-manager-field-editor' ),
							'std'      => '0',
							'cb_label' => __( 'Enable', 'wp-job-manager-field-editor' ),
						)
					)
				),
				'admin' => array(
					__( 'Admin', 'wp-job-manager-field-editor' ),
					array(
						array(
							'name'  => 'jmfe_admin_enable_auto_populate',
							'type'     => 'checkbox',
							'label'    => __( 'Auto Populate', 'wp-job-manager-field-editor' ),
							'desc'     => __( 'Auto populate fields when adding listings via admin backend with value from the current admin user meta, or default value if admin user meta does not exist.', 'wp-job-manager-field-editor' ),
							'std'      => '0',
							'cb_label' => __( 'Enable', 'wp-job-manager-field-editor' ),
						),
						array(
							'name'  => 'jmfe_admin_enable_wp_editor',
							'type'     => 'checkbox',
							'label'    => __( 'WP Editor', 'wp-job-manager-field-editor' ),
							'desc'     => __( 'For any fields that are set as WP-Editor field type, show in admin section as full WP Editor instead of TextArea.', 'wp-job-manager-field-editor' ),
							'std'      => '1',
							'class'    => 'jmfe-settings-separator',
							'cb_label' => __( 'Show WP-Editor instead of TextArea', 'wp-job-manager-field-editor' ),
						),
						array(
							'name'  => 'jmfe_admin_wp_editor_at_bottom',
							'type'     => 'checkbox',
							'label'    => __( 'WP Editor at Bottom', 'wp-job-manager-field-editor' ),
							'desc'     => __( 'Enable this option to output WP Editor fields by their priority, after all other fields have been output (recommended)', 'wp-job-manager-field-editor' ),
							'std'      => '1',
							'cb_label' => __( 'Enable', 'wp-job-manager-field-editor' ),
						)
					)
				),
				'backup'  => array(
					__( 'Backup', 'wp-job-manager-field-editor' ),
					array(
						array(
							'name'        => 'jmfe_backup',
							'caption'     => __( 'Create Backup!', 'wp-job-manager-field-editor' ),
							'field_class' => 'button-primary',
							'action'      => 'create_backup',
							'label'       => __( 'Generate Backup', 'wp-job-manager-field-editor' ),
							'desc'        => __( 'Generate and download a backup of all fields.', 'wp-job-manager-field-editor' ),
							'type'        => 'backup'
						),
						array(
							'name'        => 'jmfe_import',
							'caption'     => __( 'Import Backup!', 'wp-job-manager-field-editor' ),
							'field_class' => 'button button-primary',
							'href'        => get_admin_url() . 'import.php?import=wordpress',
							'label'       => __( 'Import Backup', 'wp-job-manager-field-editor' ),
							'desc'        => __( 'Import a previously generated backup for custom fields.  This uses the default WordPress import feature, if you do not see a file upload after clicking this button, make sure to import using WordPress importer.', 'wp-job-manager-field-editor' ),
							'type'        => 'link'
						)
					),
				),
				'debug'   => array(
					__( 'Debug', 'wp-job-manager-field-editor' ),
					array(
						array(
							'name'       => 'jmfe_enable_bug_reporter',
							'std'        => '0',
							'label'      => __( 'Enable Bug Reporter', 'wp-job-manager-field-editor' ),
							'cb_label'   => __( 'Enable', 'wp-job-manager-field-editor' ),
							'desc'       => __( 'Enable the bug report icon in the top right corner to submit bug reports', 'wp-job-manager-field-editor' ),
							'type'       => 'checkbox',
							'attributes' => array()
						),
						array(
							'name'       => 'jmfe_enable_pmi',
							'std'        => '0',
							'label'      => __( 'Enable Post Meta Inspector', 'wp-job-manager-field-editor' ),
							'cb_label'   => __( 'Enable', 'wp-job-manager-field-editor' ),
							'desc'       => __( 'Will add a meta box at the bottom of each listing that shows all the meta and values associated with the listing.', 'wp-job-manager-field-editor' ),
							'type'       => 'checkbox',
							'attributes' => array()
						),
						array(
							'name'       => 'jmfe_disable_license_deactivate',
							'std'        => '0',
							'label'      => __( 'License Deactivate', 'wp-job-manager-field-editor' ),
							'cb_label'   => __( 'Disable', 'wp-job-manager-field-editor' ),
							'desc'       => __( 'By default when you deactivate this plugin it will also deactivate/unregister your API/License Key.  With this setting checked your license will not be deactivated when you deactivate the plugin.', 'wp-job-manager-field-editor' ),
							'type'       => 'checkbox',
							'attributes' => array()
						),
						array(
							'name'        => 'jmfe_remove_all',
							'caption'     => __( 'I understand, remove all data!', 'wp-job-manager-field-editor' ),
							'field_class' => 'button-primary',
							'action'      => 'remove_all',
							'label'       => __( 'Remove All', 'wp-job-manager-field-editor' ),
							'desc'        => __( 'This will remove all custom and customized field data!', 'wp-job-manager-field-editor' ),
							'type'        => 'button'
						),
						array(
							'name'        => 'jmfe_purge_options',
							'caption'     => __( 'Purge Options!', 'wp-job-manager-field-editor' ),
							'field_class' => 'button-primary',
							'action'      => 'purge_options',
							'label'       => __( 'Purge Options', 'wp-job-manager-field-editor' ),
							'desc'        => __( 'Older versions of this plugin saved option values for fields that do not require them. You can purge those values by clicking this button.', 'wp-job-manager-field-editor' ),
							'type'        => 'button'
						),
						array(
							'name'        => 'jmfe_field_dump',
							'caption'     => __( 'Show Field Data', 'wp-job-manager-field-editor' ),
							'field_class' => 'button-primary',
							'action'      => 'jmfe_get_field_data',
							'label'       => __( 'Field Data', 'wp-job-manager-field-editor' ),
							'desc'        => __( 'Click this button to pull all the field data and configuration via AJAX and display it below here.', 'wp-job-manager-field-editor' ),
							'type'        => 'ajax',
						),
					),
				),
				'license'   => array(
					__( 'License', 'wp-job-manager-field-editor' ),
					array(
						array(
								'name'        => 'jmfe_license_page_notice',
								'label'       => '',
								'type'        => 'license_page_notice',
								'std'         => '',
								'placeholder' => '',
								'noregister'  => TRUE
						),
						array(
								'name'        => 'wp-job-manager-field-editor_email',
								'label'       => __( 'Email', 'wp-job-manager-field-editor' ),
								'type'        => 'license_email',
								'std'         => '',
								'placeholder' => '',
								'desc'        => sprintf(__( 'The email associated with your license key, this <strong>MUST</strong> match the email found on your <a href="%s" target="_blank">My Account</a> page.', 'wp-job-manager-field-editor' ), 'http://plugins.smyl.es/my-account/' ),
								'noregister'  => TRUE
						),
						array(
								'name'        => 'wp-job-manager-field-editor_licence_key',
								'label'       => __( 'License Key', 'wp-job-manager-field-editor' ),
								'type'        => 'license_key',
								'std'         => '',
								'placeholder' => '',
								'desc'        => __( 'The license key associated with the email above.', 'wp-job-manager-field-editor' ),
								'noregister'  => TRUE
						),
					)
				),
				'support' => array(
						__( 'Support', 'wp-job-manager-field-editor' ),
						array(
								array(
										'name'  => 'jmfe_support',
										'label' => '',
										'type'  => 'support'
								)
						)
				),
				'about' => array(
						__( 'About', 'wp-job-manager-field-editor' ),
						array(
								array(
										'name'  => 'jmfe_about',
										'label' => '',
										'type'  => 'about'
								)
						)
				),
			)
		);

		if( ! $this->fields()->wprm_active() ) {
			unset( $this->settings['resume'] );
			unset( $this->settings['recaptcha'][1][4] );
		}

	}

	/**
	 * register_settings function.
	 *
	 * @access public
	 * @return void
	 */
	public function register_settings() {

		$this->init_settings();

		foreach ( $this->settings as $key => $section ) {

			$section_header = "default_header";

			if ( method_exists( $this, "{$key}_header" ) ) $section_header = "{$key}_header";

			add_settings_section( "jmfe_{$key}_section", $section[ 0 ], array( $this, $section_header ), "jmfe_{$key}_section" );

			foreach ( $section[ 1 ] as $option ) {

				$submit_handler = 'submit_handler';

				if( method_exists( $this, "{$option['type']}_handler" ) ) $submit_handler = "{$option['type']}_handler";
				// Custom handler set in config
				if( isset( $option['handler'] ) ) $submit_handler = "{$option['handler']}_handler";

				if ( isset( $option[ 'std' ] ) ) add_option( $option[ 'name' ], $option[ 'std' ] );

				if( ! isset( $option['noregister'] ) ) register_setting( $this->settings_group, $option[ 'name' ], array( $this, $submit_handler ) );

				$placeholder = ( ! empty( $option[ 'placeholder' ] ) ) ? 'placeholder="' . $option[ 'placeholder' ] . '"' : '';
				$class       = ! empty( $option[ 'class' ] ) ? $option[ 'class' ] : '';
				$field_class       = ! empty( $option[ 'field_class' ] ) ? $option[ 'field_class' ] : '';
				$value       = get_option( $option[ 'name' ] );
				$value	     = maybe_unserialize( $value );
				//$value = esc_attr( $value );
				$attributes  = "";

				if ( ! empty( $option[ 'attributes' ] ) && is_array( $option[ 'attributes' ] ) ) {

					foreach ( $option[ 'attributes' ] as $attribute_name => $attribute_value ) {
						$attribute_name  = esc_attr( $attribute_name );
						$attribute_value = esc_attr( $attribute_value );
						$attributes .= "{$attribute_name}=\"{$attribute_value}\" ";
					}

				}

				$field_args = array(
					'option'      => $option,
					'placeholder' => $placeholder,
					'value'       => $value,
					'attributes'  => $attributes,
					'class'       => $class . " {$option[ 'name' ]}-row",
					'field_class' => $field_class
				);

				add_settings_field(
					$option[ 'name' ],
					$option[ 'label' ],
					array( $this, "{$option['type']}_field" ),
					"jmfe_{$key}_section",
					"jmfe_{$key}_section",
					$field_args
				);

			}
		}
	}

	/**
	 * Get Admin Class Object
	 *
	 *
	 * @since 1.1.9
	 *
	 * @return \wp_job_manager_field_editor|\WP_Job_Manager_Field_Editor_Admin
	 */
	public function admin(){

		return WP_Job_Manager_Field_Editor_Admin::get_instance();

	}

	/**
	 * Get Fields Class Object
	 *
	 *
	 * @since 1.1.9
	 *
	 * @return \wp_job_manager_field_editor
	 */
	public function fields(){

		return WP_Job_Manager_Field_Editor_Fields::get_instance();

	}

	/**
	 * Get ALL Custom Field Data
	 *
	 *
	 * @since 1.1.9
	 *
	 * @return bool
	 */
	public function field_data(){

		if( ! isset( $this->field_data ) ) $this->field_data = $this->fields()->get_custom_fields( TRUE );

		if ( empty( $this->field_data ) ) return false;

		return $this->field_data;

	}

}