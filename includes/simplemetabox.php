<?php
defined('ABSPATH') or die("Cannot access pages directly."); 

function add_simplealternate_metaboxes()
{
add_meta_box('add_alternate_language_site', 'Alternate Language Pages', 'add_alternate_language_metabox', 'post', 'normal', 'low');
add_meta_box('add_alternate_language_site', 'Alternate Language Pages', 'add_alternate_language_metabox', 'page', 'normal', 'low');
}
function add_alternate_language_metabox( $post )
{
	$selectedAlternateLanguagesJSON = get_post_meta( $post->ID, 'selectedAlternateLanguages', true );
	$selectedAlternateLanguagesJSON = stripslashes($selectedAlternateLanguagesJSON);
	
	wp_nonce_field( 'add_alternate_meta_box', 'add_alternate_meta_box_nonce' );	
	?>
	
	<select name="alternateurls[]" id="alternateurls" size="3" style="width: 70%; height: 13em;">
	<?php
	$languagesJSON = "";
	if($selectedAlternateLanguagesJSON != "")
	{
		$languagesJSON = json_decode($selectedAlternateLanguagesJSON, true);
	
		foreach ($languagesJSON['pages'] as $pages) {
			?>
			<option value="<?php echo $pages['code'];?>"><?php echo $pages['url']." (".$pages['code'].")";?></option>
			<?php
		}
	}
	?>	
	</select>
	<input type="button" id="deleteLang" value="Delete alternate language(s)">
	<hr style="width: 100%" />
	<label for="url">URL: </label>
	<input type="text" name="urllanguage" id="urllanguage" style="width:45%" value="" placeholder="i.e http://siteinanotherlanguage.com" />
	<input type="hidden" name="selectedAlternateLanguages" id="selectedAlternateLanguages" value='<?php echo $selectedAlternateLanguagesJSON;?>'/>
	<select name="languagecode" id="languagecode">
		<?php
		global $jsonLanguages;
		$options = json_decode($jsonLanguages, true);
		foreach ($options as $option) {
			?>			
			<option value="<?php echo $option['code'];?>"><?php echo $option['language'];?></option>
			<?php
		}
		?>
	</select>
	<input type="button" id="addLang" value="Add alternate language" >
	<script language="JavaScript">
	
	 (function($) {
			
			$('#alternateurls').change(function(){
			  $("#deleteLang").prop("disabled",false);
			});
			
			$("#deleteLang").prop("disabled",true);
			
			$("#deleteLang").click(function() {
				var url = $("#alternateurls option:selected").text().replace(/\s.*$/g, '');
				var code = $("#alternateurls").val();
				var jsondata = $("#selectedAlternateLanguages").val();
				
				$("#alternateurls option:selected" ).remove();
				
				 
				if( $('#alternateurls').has('option').length > 0 ) {
					console.log(jsondata);
					if(jsondata != "")
					{
						jsondata = jQuery.parseJSON(jsondata);
						$.each(jsondata.pages, function( index, value ) {
							  var arr = $.map(value, function(val, key) { return val; });							
							  var optionUrl = arr[0];
							  var optionCode = arr[1];
							  if(optionUrl == url && optionCode == code)
							  {
							  	console.log( index + ": deleting " + optionCode + "  - " + optionUrl );
							  	var deleted = jsondata.pages.splice(index, 1);
							  	console.log(deleted + '    DELETED');
							  	$("#selectedAlternateLanguages").val(JSON.stringify(jsondata));
							  	return;
							  }
							  
							});
							
					}							
				}
				else
				{
					$("#selectedAlternateLanguages").val("");
				}
			});
			$( "#addLang" ).click(function() {
				var url = $("#urllanguage").val();
				var code = $("#languagecode").val();
				var isInSelectAlready = false;
				if(/^(http|https|ftp):\/\/[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/i.test(url)){
				
					$('#alternateurls option').filter(function() { 
						console.log(url); 
						if($(this).text() === url)
							 isInSelectAlready = true;
						return $(this).text() === url; });
					
					console.log(isInSelectAlready);
					
					if(!isInSelectAlready)
					{
						$("#alternateurls").append($('<option>', {
					    value: code,
					    text: url + ' (' + code + ')' 
						}));
						
						//Add json
						
						var jsondata = $("#selectedAlternateLanguages").val();
						if(jsondata === "")
						{
							var jsonSelectedLang = new Object();
							jsonSelectedLang.url = url;
							jsonSelectedLang.code = code;
							
							var pageObjects = new Object();
							pageObjects.pages = [ jsonSelectedLang ];
							
							$("#selectedAlternateLanguages").val(JSON.stringify(pageObjects));
						}
						else
						{	
							jsondata = jQuery.parseJSON(jsondata);
							jsondata.pages.push(
							    {url: url, code: code}
							);
							$("#selectedAlternateLanguages").val(JSON.stringify(jsondata));
						}
						
						
						
					}
					


				} else {
				    alert("URL looks invalid, it should be in the right format. i.e. http://mysite.com/page");
				}
			 	
			});
			
		})(jQuery);
	</script>
	<?php
}

function save_meta_options_for_alternate_languages( $post_id ) {
	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	
	// Check if our nonce is set.
	if ( ! isset( $_POST['add_alternate_meta_box_nonce'] ) ) {
		return;
	}

	// Verify that the nonce is valid.
	if ( ! wp_verify_nonce( $_POST['add_alternate_meta_box_nonce'], 'add_alternate_meta_box' ) ) {
		return;
	}
	
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	
	// Check the user's permissions.
	if ( isset( $_POST['post_type'] ) && 'dogs_type' == $_POST['post_type'] ) {

		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return;
		}

	} else {

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
	}
	
	$selectedAlternateLanguages = $_POST['selectedAlternateLanguages'];
	
	update_post_meta( $post_id, 'selectedAlternateLanguages', wp_slash($selectedAlternateLanguages));
	
}
add_action( 'save_post', 'save_meta_options_for_alternate_languages' );

?>