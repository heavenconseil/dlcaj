<?php

if( !defined('ABSPATH') ) die();

class Wami_Socialwall_View{

	public $plugin;
	public $message;

	public function __construct($plugin=null){
		$this->plugin = $plugin;
		$this->model  = $this->plugin->model;

		//add_action('init', array($this, 'init'));
		$this->message = '';
	}

	public function init(){
		if( function_exists('acf_form_head') ) acf_form_head();
	}

	private function header($title=''){
		//$screen = get_current_screen();
		
		echo '<div class="wrap">';
		if(!empty($title)){
			echo '<h2>'.$title.'</h2><br>';
		}
		if( !current_user_can($this->plugin->capability) ){
			echo '<p>Vous ne disposez pas des droits nécessaires</p>';
			$this->footer();
			die();
		}

		$this->message();
	}

	private function footer(){
		echo '</div><!-- /.wrap -->';
	}

	private function message($message=''){
		if( !empty($this->message) ): 
			?><div id="message" class="updated notice is-dismissible below-h2">
				<p><?php echo $this->message; ?></p>
				<button type="button" class="notice-dismiss"></button>
			</div><?php
		endif;
	}

	public function set_message($message){
		$this->message = $message;
	}

	public static function request($name, $default=''){
		if( isset($_REQUEST[$name]) && !empty($_REQUEST[$name]) ){
			return $_REQUEST[$name];
		}
		return $default;
	}

	public function form(){
		//debug( $this->plugin->settings );
		?><form action="" method="post" enctype="multipart/form-data" id="poststuff" style="padding-top:0;float:none;padding-right:320px;position:relative;min-width:0;">

			<div style="position:absolute;top:0;right:0;width:300px;">
				<div class="postbox">
					<h3 class="hndle"><span class="dashicons dashicons-clock"></span> <span>CRON</span></h3>
					<div class="inside" id="wami_total">
						<p>Prochaine mise à jour dans
						<!--<?php 
						foreach(_get_cron_array() as $timestamp=>$crons){
					        if(in_array('wami_socialwall_cron', array_keys($crons))){
					        	echo '<strong>'.human_time_diff(time(), $timestamp).'</strong>';
					        	$seconds = $timestamp-time();
					        }
					    }
						?><br>-->
						<strong><span id="wami_seconds"><?php echo $seconds; ?></span> secondes</strong></p>
					</div>
				</div>
			</div>

			<script type="text/javascript">
				var elem = document.getElementById('wami_seconds');
				var seconds = parseInt(elem.innerHTML);
				function decompte(time){
					if(time > 0){
						elem.innerHTML = time;
						setTimeout('decompte('+--time+')', 1000);
					}else{
						document.getElementById('wami_total').innerHTML = '<p>Prochaine mise à jour <strong>imminente</strong></p>';
					}
				}
				decompte(seconds);
			</script>

			<?php if(!empty($this->plugin->settings)):
			foreach($this->plugin->settings as $k=>$v):
			$v = (object) $v;
			$icon = (isset($v->icon) && !empty($v->icon)) ? '<span class="dashicons '.$v->icon.'"></span> ' : '';
			//debug($v); ?>
			<div class="postbox">

			

				<h3 class="hndle"><?php echo $icon; ?><span><?php echo $v->label; ?></span></h3>
				<div class="inside acf-fields">
					<table class="acf-table">
						
						<?php if(!empty($v->fields)):
						foreach($v->fields as $field_k=>$field_v):
							$field_v = (object) $field_v;
							
							//debug($field_v); ?>
							<tr class="acf-field acf-field-text">
								<td class="acf-label">
									<label><?php echo $field_v->label; ?></label>
								</td>
								<td class="acf-input">
									<?php echo (isset($field_v->prefix)) ? '<div class="acf-input-prepend">'.$field_v->prefix.'</div>' : ''; ?>
									<div class="acf-input-wrap">
										<input type="text" class="<?php echo (isset($field_v->prefix)) ? 'acf-is-prepended' : ''; ?>" name="socialwall[<?php echo $k; ?>][<?php echo $field_k; ?>]" value="<?php echo esc_attr($this->model->get_setting($k, $field_k)); ?>" placeholder="<?php echo (isset($field_v->default)) ? $field_v->default : ''; ?>">
									</div>
								</td>
								<td>
									<code><?php echo $field_k; ?></code>
								</td>
							</tr>
						<?php endforeach ?>
						<?php endif; ?>
					</table>
				</div>
			</div>
			<?php endforeach ?>
			<?php endif; ?>

			<p align="center" style="">
				<input type="hidden" name="wami_socialwall_action" value="save_accounts">
				<button type="submit" class="button button-primary">Enregistrer</button>
			</p>
			
		</form><?php
	}

	public function dashboard(){
		$this->header($this->plugin->title);
		$this->form();
		$this->footer();
	}

}