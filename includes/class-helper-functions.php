<?php

namespace WadiAddons\Includes;

if (!defined('ABSPATH')) {
    exit;
}

class WadiHelpers
{

    /**
     * A list of safe tage for `validate_tags` method.
     */
    const ALLOWED_HTML_TAGS = array(
        'article',
        'aside',
        'div',
        'footer',
        'h1',
        'h2',
        'h3',
        'h4',
        'h5',
        'h6',
        'header',
        'main',
        'nav',
        'p',
        'section',
        'span',
    );

    
    const WADI_CURRENCIES  = array(
        'AUD' => 'Australian Dollar',
        'CAD' => 'Canadian Dollar',
        'EUR' => 'Euro',
        'GBP' => 'Pound Sterling',
        'JPY' => 'Japanese Yen',
        'USD' => 'U.S. Dollar',
        'NZD' => 'N.Z. Dollar',
        'CHF' => 'Swiss Franc',
        'HKD' => 'Hong Kong Dollar',
        'SGD' => 'Singapore Dollar',
        'SEK' => 'Swedish Krona',
        'DKK' => 'Danish Krone',
        'PLN' => 'Polish Zloty',
        'NOK' => 'Norwegian Krone',
        'HUF' => 'Hungarian Forint',
        'CZK' => 'Czech Koruna',
        'ILS' => 'Israeli New Sheqel',
        'MXN' => 'Mexican Peso',
        'BRL' => 'Brazilian Real',
        'MYR' => 'Malaysian Ringgit',
        'PHP' => 'Philippine Peso',
        'TWD' => 'New Taiwan Dollar',
        'THB' => 'Thai Baht',
        'TRY' => 'Turkish Lira',
    );
    
    /**
     * Valide HTML Tag
     *
     * Validates an HTML tag against a safe allowed list.
     *
     * @param string $tag HTML tag.
     *
     * @return string
     */
    public static function validate_tags($tag)
    {
        return in_array(strtolower($tag), self::ALLOWED_HTML_TAGS, true) ? $tag : 'div';
    }


    	/**
	 * Get Video Thumbnail
	 *
	 * Get thumbnail URL for embed or self hosted
	 *
	 * @since 3.7.0
	 * @access public
	 *
	 * @param string $video_id video ID.
	 * @param string $type embed type.
	 * @param string $size youtube thumbnail size.
	 */
	public static function get_video_thumbnail( $video_url, $size ) {

		$thumbnail_src = 'transparent';

		if ( strpos($video_url, 'youtu') ) {
			if ( '' === $size ) {
				$size = 'maxresdefault';
			}

            $link = $video_url;
            $video_id = explode("?v=", $link); // For videos like http://www.youtube.com/watch?v=...
            if (empty($video_id[1]))
                $video_id = explode("/v/", $link); // For videos like http://www.youtube.com/watch/v/..
            $video_id = explode("&", $video_id[1]); // Deleting any other params
            $video_id = $video_id[0];

            if(strpos($link, 'youtu.be')) {
                $video_id =  substr($link, -11);
            }
			$thumbnail_src = sprintf( 'https://i.ytimg.com/vi/%s/%s.jpg', $video_id, $size );

		} 


		return $thumbnail_src;

	}

    public static function convertYoutube($video_url) {
        $theVideo = preg_replace("/\s*[a-zA-Z\/\/:\.]*youtube.com\/watch\?v=([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i","<iframe width=\"420\" height=\"315\" src=\"//www.youtube.com/embed/$1\" frameborder=\"0\" allowfullscreen></iframe>", wp_kses_post($video_url));
        echo $theVideo;
    }

    public static function getYoutubeIdFromUrl($url) {
        $parts = parse_url($url);
        if(isset($parts['query'])){
            parse_str($parts['query'], $qs);
            if(isset($qs['v'])){
                return $qs['v'];
            }else if(isset($qs['vi'])){
                return $qs['vi'];
            }
        }
        if(isset($parts['path'])){
            $path = explode('/', trim($parts['path'], '/'));
            return $path[count($path)-1];
        }
        return false;
    }


    
    public static function wadi_elementor_is_edit() {
        return \Elementor\Plugin::$instance->editor->is_edit_mode();
    }

    public static function wadi_elementor_is_preview() {
        return \Elementor\Plugin::$instance->preview->is_preview_mode();
    }

    public static function wadi_elementor_is_json($elem) {
       return substr( $elem, -5 ) === '.json';
    }


}
