<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://caredove.com
 * @since      0.1.0
 *
 * @package    Caredove
 * @subpackage Caredove/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Caredove
 * @subpackage Caredove/public
 * @author     Steedan Crowe <steedancrowe@gmail.com>
 */
class Caredove_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    0.1.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    0.1.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    0.1.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    0.1.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Caredove_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Caredove_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/caredove-public.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name . '-modaal', plugin_dir_url( __FILE__ ) . 'css/modaal.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    0.1.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Caredove_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Caredove_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/caredove-public.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name . '-modaal', plugin_dir_url( __FILE__ ) . 'js/modaal.js', array( 'jquery' ), $this->version, false );		

	}

  /**
	 * Regsiter the shortcodes
	 *
	 * @since 0.2.0
	 */
	public function register_shortcodes() {
		add_shortcode('caredove_listings', array($this, 'caredove_listings_shortcode'));
		add_shortcode('caredove_search', array($this, 'caredove_shortcode'));	
		add_shortcode('caredove_button', array($this, 'caredove_shortcode'));
	}


	public function caredove_modal() {
		?>
<!-- 					<div class="caredove-modal">
				    <div class="caredove-modal-content">
				        <span class="caredove-modal-close">×</span>
				        	<iframe id="caredove-iframe" scrolling="yes" src=""></iframe>
				    </div>
					</div> -->
		<?php	
	}

	//make the button function available, since it's used in more than one plance
	public function caredove_button($a) {
			$style_name = '';
			$style_inline = '';

			$button_style = explode('-', $a['button_style']);
			foreach ($button_style as $value){
				$style_name .= 'caredove-button-'.$value.' ';
				switch($value){
					case 'outline':
						$style_inline = 'border-color:'.$a['button_color'].';';
						$style_inline .= 'color:'.$a['button_color'].';';
						break;
					case 'solid':
						$style_inline = 'background-color:'.$a['button_color'].';';
						break;

				}
			}
		
		if($a['display_option'] == 'link'){
		 		ob_start();
				?> 
					<a href="<?php echo $a['page_url']; ?>" class="caredove-inline-link caredove-iframe-button <?php echo $style_name ?>"><?php echo $a['button_text']; ?></a>
				<?php
				return ob_get_clean();
		} else {
				ob_start();
								?> 
				<button type="button" class="caredove-iframe-button <?php echo $style_name ?>" data-modal-title="<?php echo $a["modal_title"]?>" href="<?php echo $a["page_url"]?>" style="<?php echo $style_inline?>"><?php echo $a['button_text']; ?></button>
				<?php
				return ob_get_clean();
		}
	}

	public function caredove_shortcode($atts) {
				$a = shortcode_atts( array(
						'page_url' => 'https://macrumors.com',
						'display_option' => 'false',
						'button_text' => 'Open Search',
						'button_color' => '',
						'button_style' => 'default',
						'modal_title' => 'Search for Services',
				), $atts );

			 $iframe = '<iframe id="caredove-iframe" scrolling="yes" src="'.$a['page_url'].'?embed=1"></iframe>';

			 if($a['display_option'] == 'modal' || $a['display_option'] == 'false' || $a['display_option'] == 'link'){
						
						return $this->caredove_button($a);
						
			 } else {
			 		ob_start();
					echo $iframe;
					return ob_get_clean();	
			 }

	}

	public function caredove_listings_shortcode($atts) {

		$a = shortcode_atts( array(
				'listing_order' => 'ASC',
				'columns' => '1',
				'list_style' => 'full_width',
				'button_text' => 'Book Now',
				'button_color' => '',
				'button_style' => 'default',
				'modal_title' => 'Book an Appointment',
				'listing_categories' => '',
				'per_page' => '5',
				'offest' => '0',
		), $atts );

   

  	if($a['listing_categories'] != ''){  		 
			 $caredove_api_data = Caredove_Admin::get_api_listings($listing_options['category_id'] = $a['listing_categories']); 		  
  	} else {
  		 $caredove_api_data = Caredove_Admin::get_api_listings($listing_options = ''); 		  
  	}

  	if(isset($caredove_api_data)){
	  		$api_object = json_decode($caredove_api_data);
	  }

  	if ( isset($api_object->results) ) :

			$max_num_pages = sizeof($api_object->results) / $a['per_page'];
			$current_page = (get_query_var( 'paged' ) ? get_query_var( 'paged' ) : "1" );	
			$current_offset = $current_page * $a['per_page'] - 5;
			$current_limit = $current_offset + $a['per_page'] - 1;

			// echo('offset: '. $current_offset.'<br/>');
			// echo('current limit'.$current_limit);
			?><div class="caredove-listings caredove-listings-<?php echo $a['list_style'] ?>"><?php
			foreach ($api_object->results as $k => $result){
				if($k >= $current_offset && $k <= $current_limit){
						?>
							<div class="caredove-listing-item">
								<h3><?php echo $result->name ?></h3>
								<p><?php echo $result->details->description ?></p>
								<br />
								<?php $a['page_url'] = $result->eReferral->formUrl; ?>
								<?php	echo $this->caredove_button($a); ?>										
							</div>
						<?php
				}			
			}
			?></div><?php

      // Your loop

    // This is responsible for 1, 2, 3 pagination links. You can easily change this to previous and next links.
    if ( $max_num_pages > 1 ) :
      $big = 999999999;
      echo '<div class="pagination">';
      echo paginate_links( array(
        'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
        'format' => '?paged=%#%',
        'current' => max( 1, get_query_var('paged') ),
        'total' => $max_num_pages
      ) );
      echo '</div>';
    endif;

  endif;

	}


}

