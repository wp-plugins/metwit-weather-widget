<?php
/**
 * A cool weather widget
 * 
 * The widget name is Metwit Weather Widget
 * 
 * @author bedspax
 *
 */
class DX_Sample_Widget extends WP_Widget {

    /**
     * Register the widget
     */
    public function __construct() {
        $this->WP_Widget(
            'dx_sample_widget',
            __('Metwit Weather Widget', 'dxbase'),
            array( 'classname' => 'dx_widget_sample_single', 'description' => __( 'Display a sample DX Widget', 'dxbase' ) ),
            array( ) // you can pass width/height as parameters with values here
        );
    }

    /**
     * Output of widget
     * 
     * The $args array holds a number of arguments passed to the widget 
     */
    public function widget ( $args, $instance ) {

        extract( $args );

        // Get widget field values
        $title = apply_filters( 'widget_title', $instance[ 'title' ] );

        // Start sample widget body creation with output code (get arguments from options and output something)
        
        $option = get_option( 'dx_setting', '' );
        $widget = get_option( 'widget_dx_sample_widget', '');

        $lat = $option['dx_lat'];
        $lng = $option['dx_lng'];
        $units = $widget[2]['sample_dropdown'];
		if($units == 'f'){
			$f=1;
			$t="F";
		}else{
			$t="°C";
		}
        $out .= "
            <script>
                function __capitalize(string){
                    return string.charAt(0).toUpperCase() + string.slice(1);
                }
            </script>
        ";
        if($lat && $lng){
        
	        $out.= '<div class="mtw-widget">
	            <div class="mtw-widget-text">
	                <div class="mtw-widget-status"></div>
	                <div class="mtw-widget-location"></div>
	                <div class="mtw-widget-data"></div>
	            </div>
	            <div class="mtw-widget-icon-box">
	            </div>
	            <div style="clear:both;"></div>
	        </div>
	        <div class="mtw-widget-footer">
				Powered by Metwit <a href="http://metwit.com/weather-api/" title="weather API">weather API</a>
	        </div>';
        
    			$out .="
                        <script>
                        jQuery.getJSON('https://api.metwit.com/v2/weather/?location_lat=$lat&location_lng=$lng', function(data) {
                            
                            var locality, status, temp, humidity, text;
                            
                            try {
                           ";
                           if(!$f){
	                           	$out.="temp = data.objects[0]['weather']['measured']['temperature'] - 272";
                           }else{
                            	$out.="temp = data.objects[0]['weather']['measured']['temperature']";
                           }
                           $out.="
                            }catch(err){
                            	temp = 0;
                            }
                            
                            try {
                            	humidity = data.objects[0]['weather']['measured']['humidity'];
                            }catch(err){
                            	humidity = 0;
                            }
                            
                            try {
                                status = __capitalize(data.objects[0]['weather']['status']);
                            }catch(err){
                                status = 0;
                            }

                            try{
                                locality = data.objects[0]['location']['locality'];
                            }catch(err){
                                locality = 'Unknown';
                            }

                            if (status != 0){
                                
                                
                                text = temp+'$t';
                                
				                jQuery('div.mtw-widget-icon-box').html('<img id=\"mtw-widget-icon\" src=\"'+data.objects[0].icon+'?format=250x250&type=svg\">');				              
				                
                                
                                jQuery('.mtw-widget-status').html(status);
                                jQuery('.mtw-widget-location').html('in '+ locality);
                                jQuery('.mtw-widget-data').html(text);                                
                            }else{


                            }
                        });
                        </script>
                    ";
        }else{
            $out = "You must insert latitude and longitude from the admin panel.";
        }
        // End sample widget body creation
        
        if ( !empty( $out ) ) {
        	echo $before_widget;
        	if ( $title ) {
        		echo $before_title . $title . $after_title;
        	}
        	?>
        		<div>
        			<?php echo $out; ?>
        		</div>
        	<?php
        		echo $after_widget;
        }
    }

    /**
     * Updates the new instance when widget is updated in admin
     *
     * @return array $instance new instance after update
     */
    public function update ( $new_instance, $old_instance ) {
        $instance = $old_instance;

        $instance['title'] = strip_tags($new_instance['title']);
        $instance['sample_text'] = strip_tags($new_instance['sample_text']);
        $instance['sample_dropdown'] = strip_tags($new_instance['sample_dropdown']);
        
        return $instance;
    }

    /**
     * Widget Form
     */
    public function form ( $instance ) {

        $title = esc_attr( $instance[ 'title' ] );
        $sample_text = esc_attr( $instance[ 'sample_text' ] );
        $sample_dropdown = esc_attr( $instance[ 'sample_dropdown' ] );
        
        ?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:', 'dxbase'); ?></label> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>
		<p>
			<label for="<?php echo $this->get_field_id('sample_dropdown'); ?>"><?php _e( '  Units:', 'dxbase' ); ?></label>
			<select name="<?php echo $this->get_field_name('sample_dropdown'); ?>" id="<?php echo $this->get_field_id('sample_dropdown'); ?>" class="widefat">
				<option value="c"<?php selected( $instance['sample_dropdown'], 'c' ); ?>><?php _e( 'Celsius', 'dxbase' ); ?></option>
                <option value="f"<?php selected( $instance['sample_dropdown'], 'f' ); ?>><?php _e( 'Fahrenheit', 'dxbase' ); ?></option>
			</select>
		</p>
	<?php
    }
}

// Register the widget for use
register_widget('DX_Sample_Widget');