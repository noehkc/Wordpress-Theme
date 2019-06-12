<?php 

/*

@package sun-restaurant-systems-t1

 ==============
 Admin Page
 ==============
*/


// Settings Page: Configure Website
// Retrieving values: get_option( 'your_field_id' )
class configurewebsite_Settings_Page {

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'wph_create_settings' ) );
		add_action( 'admin_init', array( $this, 'wph_setup_sections' ) );
		add_action( 'admin_init', array( $this, 'wph_setup_fields' ) );
		add_action( 'admin_footer', array( $this, 'media_fields' ) );
		add_action( 'admin_enqueue_scripts', 'wp_enqueue_media' );
	}

	public function wph_create_settings() {
		$page_title = 'Website Configuration Options';
		$menu_title = 'Configure Website';
		$capability = 'manage_options';
		$slug = 'configurewebsite';
		$callback = array($this, 'wph_settings_content');
		$icon = 'dashicons-admin-settings';
		$position = 99;
		add_menu_page($page_title, $menu_title, $capability, $slug, $callback, $icon, $position);
	}

	public function wph_settings_content() { ?>
		<div class="wrap">
			<h1>Website Configuration Options</h1>
			<?php settings_errors(); ?>
			<form method="POST" action="options.php">
				<?php
					settings_fields( 'configurewebsite' );
					do_settings_sections( 'configurewebsite' );
					submit_button();
				?>
			</form>
		</div> <?php
	}

	public function wph_setup_sections() {
		add_settings_section( 'configurewebsite_section', '', array(), 'configurewebsite' );
	}

	public function wph_setup_fields() {
		$fields = array(
			array(
				'label' => 'Upload Logo',
				'id' => 'upload_logo',
				'type' => 'media',
				'section' => 'configurewebsite_section',
				'returnvalue' => 'id',
				'desc' => 'Upload your site Logo',
			),
			array(
				'label' => 'Facebook',
				'id' => 'facebook',
				'type' => 'text',
				'section' => 'configurewebsite_section',
				'desc' => 'Enter your Facebook URL',
			),
			array(
				'label' => 'Twitter',
				'id' => 'twitter',
				'type' => 'text',
				'section' => 'configurewebsite_section',
				'desc' => 'Enter your Twitter Handle',
			),
			array(
				'label' => 'Instagram',
				'id' => 'instagram_text',
				'type' => 'text',
				'section' => 'configurewebsite_section',
				'desc' => 'Enter your Instagram Handle',
			),
			array(
				'label' => 'Slider',
				'id' => 'slider',
				'type' => 'media',
				'section' => 'configurewebsite_section',
				'returnvalue' => 'url',
				'desc' => 'Upload your slide show images',
			),
			array(
				'label' => 'font-color-primary',
				'id' => 'font-color-primary',
				'type' => 'color',
				'section' => 'configurewebsite_section',
				'desc' => 'Primary Font Color',
			),
			array(
				'label' => 'font-color-secondary',
				'id' => 'font-color-secondary',
				'type' => 'color',
				'section' => 'configurewebsite_section',
				'desc' => 'Secondary Font Color',
			),
		);
		foreach( $fields as $field ){
			add_settings_field( $field['id'], $field['label'], array( $this, 'wph_field_callback' ), 'configurewebsite', $field['section'], $field );
			register_setting( 'configurewebsite', $field['id'] );
		}
	}

	public function wph_field_callback( $field ) {
		$value = get_option( $field['id'] );
		$placeholder = '';
		if ( isset($field['placeholder']) ) {
			$placeholder = $field['placeholder'];
		}
		switch ( $field['type'] ) {
				case 'media':
					$field_url = '';
					if ($value) {
						if ($field['returnvalue'] == 'url') {
							$field_url = $value;
						} else {
							$field_url = wp_get_attachment_url($value);
						}
					}
					printf(
						'<input style="display:none;" id="%s" name="%s" type="text" value="%s"  data-return="%s"><div id="preview%s" style="margin-right:10px;border:1px solid #e2e4e7;background-color:#fafafa;display:inline-block;width: 100px;height:100px;background-image:url(%s);background-size:cover;background-repeat:no-repeat;background-position:center;"></div><input style="width: 19%%;margin-right:5px;" class="button configurewebsite-media" id="%s_button" name="%s_button" type="button" value="Select" /><input style="width: 19%%;" class="button remove-media" id="%s_buttonremove" name="%s_buttonremove" type="button" value="Clear" />',
						$field['id'],
						$field['id'],
						$value,
						$field['returnvalue'],
						$field['id'],
						$field_url,
						$field['id'],
						$field['id'],
						$field['id'],
						$field['id']
					);
					break;
			default:
				printf( '<input name="%1$s" id="%1$s" type="%2$s" placeholder="%3$s" value="%4$s" />',
					$field['id'],
					$field['type'],
					$placeholder,
					$value
				);
		}
		if( isset($field['desc']) ) {
			if( $desc = $field['desc'] ) {
				printf( '<p class="description">%s </p>', $desc );
			}
		}
	}
	public function media_fields() {
		?><script>
			jQuery(document).ready(function($){
				if ( typeof wp.media !== 'undefined' ) {
					var _custom_media = true,
					_orig_send_attachment = wp.media.editor.send.attachment;
					$('.configurewebsite-media').click(function(e) {
						var send_attachment_bkp = wp.media.editor.send.attachment;
						var button = $(this);
						var id = button.attr('id').replace('_button', '');
						_custom_media = true;
							wp.media.editor.send.attachment = function(props, attachment){
							if ( _custom_media ) {
								if ($('input#' + id).data('return') == 'url') {
									$('input#' + id).val(attachment.url);
								} else {
									$('input#' + id).val(attachment.id);
								}
								$('div#preview'+id).css('background-image', 'url('+attachment.url+')');
							} else {
								return _orig_send_attachment.apply( this, [props, attachment] );
							};
						}
						wp.media.editor.open(button);
						return false;
					});
					$('.add_media').on('click', function(){
						_custom_media = false;
					});
					$('.remove-media').on('click', function(){
						var parent = $(this).parents('td');
						parent.find('input[type="text"]').val('');
						parent.find('div').css('background-image', 'url()');
					});
				}
			});
		</script><?php
	}

}
new configurewebsite_Settings_Page();