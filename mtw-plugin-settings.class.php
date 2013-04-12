<?php


class DX_Plugin_Settings {
	
	private $dx_setting;
	/**
	 * Construct me
	 */
	public function __construct() {
		$this->dx_setting = get_option( 'dx_setting', '' );
				
		// register the checkbox
		add_action('admin_init', array( $this, 'register_settings' ) );
	}
		
	/**
	 * Setup the settings
	 * 
	 * Add a single checkbox setting for Active/Inactive and a text field 
	 * just for the sake of our demo
	 * 
	 */
	public function register_settings() {
		register_setting( 'dx_setting', 'dx_setting', array( $this, 'dx_validate_settings' ) );
		
		add_settings_section(
			'dx_settings_section',         // ID used to identify this section and with which to register options
			'Enable Metwit Weather Widget',                  // Title to be displayed on the administration page
			array($this, 'dx_settings_callback'), // Callback used to render the description of the section
			'dx-plugin-base'                           // Page on which to add this section of options
		);
		
		add_settings_field(
			'dx_lat',                      // ID used to identify the field throughout the theme
			__( 'Latitude: ', 'dxbase' ),                           // The label to the left of the option interface element
			array( $this, 'dx_callback_lat' ),   // The name of the function responsible for rendering the option interface
			'dx-plugin-base',                          // The page on which this option will be displayed
			'dx_settings_section'         // The name of the section to which this field belongs
		);
		
		add_settings_field(
			'dx_lng',                      // ID used to identify the field throughout the theme
			__( 'Longitude: ', 'dxbase' ),                           // The label to the left of the option interface element
			array( $this, 'dx_callback_lng' ),   // The name of the function responsible for rendering the option interface
			'dx-plugin-base',                          // The page on which this option will be displayed
			'dx_settings_section'         // The name of the section to which this field belongs
		);
		
	}
	
	public function dx_settings_callback() {
		//echo _e( 'Enable me', 'dxbase' );
	}
		
	public function dx_callback_lat() {
		$out = '';
		$val = '';
				
		// check if checkbox is checked
		if(! empty( $this->dx_setting ) && isset ( $this->dx_setting['dx_lat'] ) ) {
			$val = $this->dx_setting['dx_lat'];
		}

		$out = '<input type="text" id="dx_sample_text" name="dx_setting[dx_lat]" value="' . $val . '"  />';
		
		echo $out;
	}
	
	public function dx_callback_lng() {
		$out = '';
		$val = '';
				
		// check if checkbox is checked
		if(! empty( $this->dx_setting ) && isset ( $this->dx_setting['dx_lng'] ) ) {
			$val = $this->dx_setting['dx_lng'];
		}

		$out = '<input type="text" id="dx_sample_text" name="dx_setting[dx_lng]" value="' . $val . '"  />';
		
		echo $out;
	}
	
	
	/**
	 * Helper Settings function if you need a setting from the outside.
	 * 
	 * Keep in mind that in our demo the Settings class is initialized in a specific environment and if you
	 * want to make use of this function, you should initialize it earlier (before the base class)
	 * 
	 * @return boolean is enabled
	 */
	public function is_enabled() {
		if(! empty( $this->dx_setting ) && isset ( $this->dx_setting['dx_opt_in'] ) ) {
			return true;
		}
		return false;
	}
	
	/**
	 * Validate Settings
	 * 
	 * Filter the submitted data as per your request and return the array
	 * 
	 * @param array $input
	 */
	public function dx_validate_settings( $input ) {
		
		return $input;
	}
}