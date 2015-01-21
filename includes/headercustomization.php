<?php
defined('ABSPATH') or die("Cannot access pages directly.");


// Display meta header
	function simplealternate_wp_head() {
		switch (get_post_type()) {
			case 'post':
			case 'page':
				//<link rel="alternate" hreflang="es" href="http://es.example.com/" />
				global $post; 
				$id = $post->ID; 
				$simpleAltLangJSON = get_post_meta( $id, 'selectedAlternateLanguages', true );
				$simpleAltLangJSON = stripslashes($simpleAltLangJSON);
				
				if($simpleAltLangJSON != "")
				{
					$languagesJSON = json_decode($simpleAltLangJSON, true);
				
					foreach ($languagesJSON['pages'] as $pages) {
						?>
<link rel="alternate" hreflang="<?php echo $pages['code'];?>" href="<?php echo $pages['url'];?>" />						
						<?php
					}
				}
	
				break;
			
			default:
				
				break;
		};
	}



  
?>