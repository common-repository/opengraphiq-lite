<?php

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class Opengraphiq_Public {

	
	private $opengraphiq;

	private $version;

	private $option_name = 'opengraphiq_setting';
	private $plugin_name = 'opengraphiq';

	public function __construct( $opengraphiq, $version ) {

		$this->opengraphiq = $opengraphiq;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Opengraphiq_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Opengraphiq_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->opengraphiq, plugin_dir_url( __FILE__ ) . 'css/opengraphiq-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Opengraphiq_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Opengraphiq_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->opengraphiq, plugin_dir_url( __FILE__ ) . 'js/opengraphiq-public.js', array( 'jquery' ), $this->version, false );
	}

	public function start_head_buffer(){
		if(is_page() || is_single()){
			global $post;
			$ogtemplate = $this -> opengraphiq_get_template_by_id( $post );

			if($ogtemplate != ''){
				$ogimageurl = get_post_meta( $post -> ID, 'opengraphiqtemplates_image', true );
				if ($ogimageurl != ''){
					ob_start();
				}
			}
		}
		
	}

	public function opengraphiq_insert_tags($title){

		$str_to_add = '';
		if(is_page() || is_single()){
			global $post;
			$ogtemplate = $this -> opengraphiq_get_template_by_id( $post );
			
			if($ogtemplate != ''){
				$ogimageurl = get_post_meta( $post -> ID, 'opengraphiqtemplates_image', true );

				if ($ogimageurl != ''){
					
					if(is_ssl()){
						$str_to_add .= "\t<ogmeta property='og:image:secure_url' content='" . esc_attr(get_site_url(null, $ogimageurl)) . "' />\r\n";
					}
					$str_to_add .= "\t<ogmeta property='og:image' content='" . esc_attr(get_site_url(null, $ogimageurl)) . "' />\r\n";
					//$size = $this->getpngsize($this->attachment_url_to_path($ogimageurl));
					$size = getimagesize( $this->attachment_url_to_path($ogimageurl) );
					$str_to_add .= "\t<ogmeta property='og:image:height' content='" . esc_attr($size[1]) . "' />\r\n";
					$str_to_add .= "\t<ogmeta property='og:image:width' content='" . esc_attr($size[0]) . "' />\r\n";
					$str_to_add .= "\t<ogmeta name='twitter:image' content='" . esc_attr(get_site_url(null, $ogimageurl)) . "' />\r\n";
					$str_to_add .= "\t<ogmeta name='twitter:card' content='summary_large_image' />\r\n";

					$tagarr = array( 'meta' => array ( 'property' => array(), 'content' => array(), 'name' => array() ), 'ogmeta' => array ( 'property' => array(), 'content' => array(), 'name' => array() ) );
					echo wp_kses( $str_to_add, $tagarr );
				}
			}
		}
	}

	public function end_head_buffer(){
		if(is_page() || is_single()){
			global $post;
			$ogtemplate = $this -> opengraphiq_get_template_by_id( $post );

			if($ogtemplate != ''){
				$ogimageurl = get_post_meta( $post -> ID, 'opengraphiqtemplates_image', true );
				if ($ogimageurl != ''){
					$outputbuffer = ob_get_clean();
					$outputbuffer = $this -> removeTags($outputbuffer, $ogimageurl);
					echo str_replace( "<ogmeta", "<meta", $outputbuffer);
				}
			}
		}
	}

	private function removeTags( $markup, $img ){

    	// - do the og:image and twitter card magic
		$replaceRegexOgImage = [
			"/<meta[^>]*property=[\"']og:image[\"'][^>]*\>/",
			"/<meta[^>]*property=[\"']og:image:secure_url[\"'][^>]*\>/",
			"/<meta[^>]*property=[\"']og:image:height[\"'][^>]*\>/",
			"/<meta[^>]*property=[\"']og:image:width[\"'][^>]*\>/",
		];

		$replaceRegexTwitter = [
			"/<meta[^>]*name=[\"']twitter:image[\"'][^>]*\>/",
			"/<meta[^>]*name=[\"']twitter:card[\"'][^>]*\>/",
		];
        
		$replaceRegexes = [];
            
		$replaceRegexes = array_merge(
			$replaceRegexes,
			$replaceRegexOgImage
		);

		$replaceRegexes = array_merge(
			$replaceRegexes,
			$replaceRegexTwitter
		);

		foreach ($replaceRegexes as $regex) {
			$matches = [];
			preg_match_all($regex, $markup, $matches);
			
			$matches = array_unique( $this -> opengraphiq_array_get($matches, 0, []));
			
			foreach ($matches as $match) {
				$markup = str_replace(
					$match,
					$this->_wrap_match($match, $img),
					$markup
				);
			}

			foreach ($matches as $match) {
				$markup = $this->_delete_initial($match, $markup);
			}
		}
        
        return $markup;
    }

	private function attachment_url_to_path( $url )
	{
		$parsed_url = parse_url( $url );
		if(empty($parsed_url['path'])) return false;
		$file = ABSPATH . ltrim( $parsed_url['path'], '/');
		if (file_exists( $file)) return $file;
		return false;
	}

	private function getpngsize( $img_loc ) {
		$handle = fopen( $img_loc, "rb" ) or die( "Invalid file stream." );
	
		if ( ! feof( $handle ) ) {
			$new_block = fread( $handle, 24 );
			if ( $new_block[0] == "\x89" &&
				$new_block[1] == "\x50" &&
				$new_block[2] == "\x4E" &&
				$new_block[3] == "\x47" &&
				$new_block[4] == "\x0D" &&
				$new_block[5] == "\x0A" &&
				$new_block[6] == "\x1A" &&
				$new_block[7] == "\x0A" ) {
					if ( $new_block[12] . $new_block[13] . $new_block[14] . $new_block[15] === "\x49\x48\x44\x52" ) {
						$width  = unpack( 'H*', $new_block[16] . $new_block[17] . $new_block[18] . $new_block[19] );
						$width  = hexdec( $width[1] );
						$height = unpack( 'H*', $new_block[20] . $new_block[21] . $new_block[22] . $new_block[23] );
						$height  = hexdec( $height[1] );
	
						return array( $width, $height );
					}
				}
			}
	
		return false;
	}

	private function _wrap_match($match, $img)
    {
		$str_to_add = '';

		if (str_contains($match, 'og:image:secure_url')) {
			$str_to_add = '<meta property="og:image:secure_url" content="' . get_site_url(null, $img) . '" />';
		} elseif (str_contains($match, 'og:image:height')) {
			$size = getimagesize( $this->attachment_url_to_path($img) );
			$str_to_add = '<meta property="og:image:height" content="' . $size[1] . '" />';
		} elseif (str_contains($match, 'og:image:width')) {
			$size = getimagesize( $this->attachment_url_to_path($img) );
			$str_to_add = '<meta property="og:image:width" content="' . $size[0] . '" />';
		} elseif (str_contains($match, 'og:image')) {
			$str_to_add = '<meta property="og:image" content="' . get_site_url(null, $img) . '" />';
		} elseif (str_contains($match, 'twitter:image')) {
			$str_to_add = '<meta name="twitter:image" content="' . get_site_url(null, $img) . '" />';
		} elseif (str_contains($match, 'twitter:card')) {
			$str_to_add = '<meta name="twitter:card" content="summary_large_image" />';
		}

		$val = get_option( $this->option_name . '_debug_mode' );

		if($val == "on") {
			$match =  str_replace("<meta", "<-m-e-t-a-replaced", $match);
        	return $str_to_add . "\r\n\t" . "<!-- REPLACED BY OPENGRAPHIQ: " . $match . " -->";
		} else {
			return $str_to_add;
		}
    }

	private function _delete_initial($match, $markup)
    {
		$final_ret = '';

		if (str_contains($match, 'og:image:secure_url')) {
			$pattern = "/\\t<ogmeta[^>]*property=[\"']og:image:secure_url[\"'][^>]*\>\\r\\n/";
		} elseif (str_contains($match, 'og:image:height')) {
			$pattern = "/\\t<ogmeta[^>]*property=[\"']og:image:height[\"'][^>]*\>\\r\\n/";
		} elseif (str_contains($match, 'og:image:width')) {
			$pattern = "/\\t<ogmeta[^>]*property=[\"']og:image:width[\"'][^>]*\>\\r\\n/";
		} elseif (str_contains($match, 'og:image')) {
			$pattern = "/\\t<ogmeta[^>]*property=[\"']og:image[\"'][^>]*\>\\r\\n/";
		} elseif (str_contains($match, 'twitter:image')) {
			$pattern = "/\\t<ogmeta[^>]*name=[\"']twitter:image[\"'][^>]*\>\\r\\n/";
		} elseif (str_contains($match, 'twitter:card')) {
			$pattern = "/\\t<ogmeta[^>]*name=[\"']twitter:card[\"'][^>]*\>\\r\\n/";
		}
		$final_ret = preg_replace($pattern, '', $markup);
        return $final_ret;
    }

	private function opengraphiq_get_template_by_id( $post ){
		$meta = get_post_meta( $post -> ID, $this->option_name . '_post_meta', true );

		$post_type = $post -> post_type;

		if ( $meta == '0' ){
			return '';
		}

		if ( $meta != '-1' && $meta != ''){
			$ret_val[$meta] = get_post($meta) -> post_title;
			return $ret_val;	
		}	

		$optionval = get_option( $this->option_name . '_cp_template' );
		$needsfiltering = true;
		if ($optionval) {
			if( array_key_exists($post_type, $optionval) ) {
				if( $optionval[$post_type] !=  '-1'  && $meta != '' ){
					if( $optionval[$post_type] == '0' ){
						return '';
					}
					$ret_val[$optionval[$post_type]] = get_post($optionval[$post_type]) -> post_title;
					return $ret_val;
				} else {
					$val = get_option( $this->option_name . '_default_template' );
					if( $val  == '0' || $val == ''){
						return '';
					}
					$ret_val[$val] = get_post($val) -> post_title;
					return( $ret_val );
				}
			}
		}
		$val = get_option( $this->option_name . '_default_template' );
		$ret_val[$val] = get_post($val) -> post_title;
		return( $ret_val );
		
	}

	private function opengraphiq_array_get($array, $key, $default = null)
    {
        if (is_null($key)) {
            return $array;
        }
        if (isset($array[$key])) {
            return $array[$key];
        }
        foreach (explode(".", $key) as $segment) {
            if (!is_array($array) || !array_key_exists($segment, $array)) {
                return $this -> opengraphiq_value($default);
            }
            $array = $array[$segment];
        }
        return $array;
    }

	private function opengraphiq_value($value)
    {
        return $value instanceof Closure ? $value() : $value;
    }

}
