<?php
/**
	Plugin Name: CallPhone'r
	Plugin URI: http://mojwp.ru/
	Description: Increase calls of the clients who visite your website from the mobile phone
	Author: mojWP
	Version: 1.0
	Author URI: http://mojwp.ru/
	Text Domain: callphoner
	Domain Path: /languages
	License: GPL
*/

defined('ABSPATH') or die('No script kiddies please!');

define('CALLPHONER_VERSION', '1.0');
define('CALLPHONER_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('CALLPHONER_PLUGIN_URL', plugin_dir_url(__FILE__));

class CallPhoner {

	private $settings = array();
	private $minSize = 26;
	private $defaultSize = 50;
	private $defaultBgColor = '#000';
	private $defaultIconColor = '#fff';

	public function __construct() {
		$this->saveSettings();
		$this->loadSettings();
		
		add_action('plugins_loaded', array($this, 'init_texdomain'));

		register_activation_hook(__FILE__, array($this, 'activation'));
		register_deactivation_hook(__FILE__, array($this, 'deactivation'));

		add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
		add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
		add_action('admin_menu', array($this, 'register_pages'));

		if (wp_is_mobile() === true) add_action('wp_footer', array($this, 'wp_footer'));
	}

	public function register_pages() {
		add_menu_page('CallPhone\'r', 'CallPhone\'r', 'manage_options', 'callphoner', array($this, 'settings_html'), 'dashicons-phone');
		add_submenu_page('callphoner', 'CallPhone\'r — '.__('Settings', 'callphoner'), __('Settings', 'callphoner'), 'manage_options', 'callphoner', array($this, 'settings_html'));
		add_submenu_page('callphoner', 'CallPhone\'r — '.__('Instruction', 'callphoner'), __('Instruction', 'callphoner'), 'manage_options', 'callphoner_support', array($this, 'support_html'));
	}

	public function admin_enqueue_scripts($page) {
		wp_enqueue_style('callphoner_font_awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css', array(), false);
		wp_enqueue_style('callphoner_backend_style', CALLPHONER_PLUGIN_URL.'assets/css/admin-style.css', array(), false);
		if ($page == 'toplevel_page_callphoner') {
			wp_enqueue_style('wp-color-picker');
			wp_enqueue_script('plugin-script', CALLPHONER_PLUGIN_URL.'assets/js/jquery.callphoner.js', array('wp-color-picker'), false, 1);
		}
	}

	public function enqueue_scripts() {
		wp_enqueue_style('dashicons');
	}

	public function settings_html() {
		$size = isset($this->settings['size']) ? $this->settings['size'] : $this->defaultSize;
		$color = isset($this->settings['color']) ? $this->settings['color'] : $this->defaultBgColor;
		$colori = isset($this->settings['colori']) ? $this->settings['colori'] : $this->defaultIconColor;
		$tel = isset($this->settings['tel']) ? $this->settings['tel'] : '';

		?>
		<div class="wrap">
			<h1>CallPhone'r</h1>
			<form method="POST" action="" class="callphoner_form">
				<?php wp_nonce_field('callpnoner_save_settings', 'callphoner_nonce'); ?>
				<input type="hidden" name="callphoner_action" value="saveSettings" />
				<table class="form-table">
					<tr>
						<th scope="row"><?php _e('Plugin status', 'callphoner'); ?></th>
						<td id="callphoner_status">
							<fieldset>
								<legend class="screen-reader-text"><span><?php _e('Plugin status', 'callphoner'); ?></span></legend>
								<p><label><input name="callphoner_status" type="radio" value="enabled" class="tog" <?php if ($this->settings['status'] == 'enabled') echo 'checked="checked"'; ?> /><?php _e('On', 'callphoner'); ?></label></p>
								<p><label><input name="callphoner_status" type="radio" value="disabled" class="tog" <?php if (!isset($this->settings['status']) || $this->settings['status'] != 'enabled') echo 'checked="checked"'; ?>><?php _e('Off', 'callphoner'); ?></label></p>
							</fieldset>
						</td>
					</tr>
					<tr>
						<td colspan="2" class="callphoner_heading_line"><h3><?php _e('Button settings', 'callphoner'); ?></h3></td>
					</tr>
					<tr>
						<th scope="row"><label for="callphoner_button_size"><?php _e('Size of the button in px', 'callphoner'); ?></label></th>
						<td><input name="callphoner_button_size" type="number" step="1" min="10" id="callphoner_button_size" value="<?=$size;?>" class="small-text"> px</td>
					</tr>
					<tr>
						<th scope="row"><label for="callphoner_background_color"><?php _e('Button background color', 'callphoner'); ?></label></th>
						<td><input name="callphoner_background_color" type="text" id="callphoner_background_color" value="<?=$color;?>" class="small-text"></td>
					</tr>
					<tr>
						<th scope="row"><label for="callphoner_icon_color"><?php _e('Button color', 'callphoner'); ?></label></th>
						<td><input name="callphoner_icon_color" type="text" id="callphoner_icon_color" value="<?=$colori;?>" class="small-text"></td>
					</tr>
					<tr>
						<th scope="row"><label for="callphoner_number"><?php _e('Phone number', 'callphoner'); ?></label></th>
						<td><input name="callphoner_number" type="tel" id="callphoner_number" value="<?=$tel;?>" placeholder="+7211234567"></td>
					</tr>
					<tr>
						<td colspan="2" class="callphoner_heading_line"><h3><?php _e('Plugin schedule', 'callphoner'); ?></h3></td>
					</tr>
					<tr>
						<td colspan="2">
							<div id="callphoner_current_timestamp"><?php _e('Current time your server', 'callphoner'); ?>: <code><?php echo date('d.m.Y H:i'); ?></code></div>
							<div id="callphoner_schedule">
								<div class="callphoner_schedule_row callphoner_schedule_head">
									<div class="callphoner_schedule_row_item callphoner_row_day">&nbsp;</div>
									<div class="callphoner_schedule_row_item callphoner_row_work"><?php _e('Working time', 'callphoner'); ?></div>
									<div class="callphoner_schedule_row_item callphoner_row_break"><?php _e('Breaking time', 'callphoner'); ?></div>
								</div>
								<?php echo $this->generateScheduleRow(__('Monday', 'callphoner'), 'monday'); ?>
								<?php echo $this->generateScheduleRow(__('Tuesday', 'callphoner'), 'tuesday'); ?>
								<?php echo $this->generateScheduleRow(__('Wednesday', 'callphoner'), 'wednesday'); ?>
								<?php echo $this->generateScheduleRow(__('Thursday', 'callphoner'), 'thursday'); ?>
								<?php echo $this->generateScheduleRow(__('Friday', 'callphoner'), 'friday'); ?>
								<?php echo $this->generateScheduleRow(__('Saturday', 'callphoner'), 'saturday'); ?>
								<?php echo $this->generateScheduleRow(__('Sunday', 'callphoner'), 'sunday'); ?>
							</div>
						</td>
					</tr>
				</table>


				<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Save settings', 'callphoner'); ?>"></p></form>
		</div>
	<?php }

	public function generateScheduleRow($rus, $eng) {
		ob_start();
		?>
		<div class="callphoner_schedule_row callphoner_schedule_<?=$eng;?>">
			 <div class="callphoner_schedule_row_item callphoner_row_day callphoner_row_day_checkbox">
				 <input type="checkbox" name="callphoner_schedule[<?=$eng;?>][status]" id="callphoner_<?=$eng;?>" <?php if (isset($this->settings['schedule'][$eng])) echo 'checked="checked"'; ?> />
				 <label class="callphoner_day_label" for="callphoner_<?=$eng;?>"><?=$rus;?></label>
			 </div>
			 <div class="callphoner_schedule_row_item callphoner_row_work">
				 <select id="callphoner_work_<?=$eng;?>_from_select" name="callphoner_schedule[<?=$eng;?>][work][from]" class="small-text">
					<?php echo $this->generateTimeForSelect('work', 'from', $eng); ?>
				 </select>
					-
				 <select id="callphoner_work_<?=$eng;?>_to_select"  name="callphoner_schedule[<?=$eng;?>][work][to]" class="small-text">
					 <?php echo $this->generateTimeForSelect('work', 'to', $eng); ?>
				 </select>
				 <div class="callphoner_work_checkbox">
					 <input type="checkbox" name="callphoner_schedule[<?=$eng;?>][work]" value="around" id="callphoner_work_<?=$eng;?>_checkbox" data-interval="work" data-day="<?=$eng;?>" <?php if (isset($this->settings['schedule'][$eng]['work']) && $this->settings['schedule'][$eng]['work'] == 'around') echo 'checked="checked"'; ?> />
					 <label for="callphoner_work_<?=$eng;?>_checkbox"><?php _e('around the clock', 'callphoner'); ?></label>
				 </div>
			 </div>
			 <div class="callphoner_schedule_row_item callphoner_row_break">
				 <select id="callphoner_break_<?=$eng;?>_from_select" name="callphoner_schedule[<?=$eng;?>][break][from]" class="small-text">
					 <?php echo $this->generateTimeForSelect('break', 'from', $eng); ?>
				 </select>
				 -
				 <select id="callphoner_break_<?=$eng;?>_to_select" name="callphoner_schedule[<?=$eng;?>][break][to]" class="small-text">
					 <?php echo $this->generateTimeForSelect('break', 'to', $eng); ?>
				 </select>
				 <div class="callphoner_break_checkbox">
					 <input type="checkbox" name="callphoner_schedule[<?=$eng;?>][break]" value="nobreak" id="callphoner_break_<?=$eng;?>_checkbox" data-interval="break" data-day="<?=$eng;?>" <?php if (isset($this->settings['schedule'][$eng]['break']) && $this->settings['schedule'][$eng]['break'] == 'nobreak') echo 'checked="checked"'; ?> />
					 <label for="callphoner_break_<?=$eng;?>_checkbox"><?php _e('no break', 'callphoner'); ?></label>
				 </div>
			 </div>
		</div>
		<?php
	}

	public function generateTimeForSelect($interval, $position, $day) {
		ob_start();
		for ($i = 0; $i < 24; $i++) {
			for ($j = 0; $j < 2; $j++) {
				$h = ($i < 10) ? '0'.$i : $h = $i;
				$m = ($j == 0) ? '00' : '30';

				$time = $h.':'.$m;

				if (isset($this->settings['schedule'][$day][$interval][$position]) && $this->settings['schedule'][$day][$interval][$position] == $time)
					$checked = ' selected';
				else
					$checked = '';

				?><option value="<?=$time;?>"<?=$checked;?>><?=$time;?></option><?php
			}
		}
		return ob_get_clean();
	}

	private function saveSettings() {
		if (isset($_POST['callphoner_action']) && $_POST['callphoner_action'] == 'saveSettings' /*&& wp_verify_nonce($_POST['callphoner_nonce'], 'callpnoner_save_settings')*/) {
			$status = trim($_POST['callphoner_status']) == 'enabled' ? 'enabled' : 'disabled';
			$size = (!isset($_POST['callphoner_button_size']) || !is_numeric($_POST['callphoner_button_size']) || $_POST['callphoner_button_size'] < $this->minSize) ? $this->minSize : $_POST['callphoner_button_size'];
			$color = trim($_POST['callphoner_background_color']) != '' ? $_POST['callphoner_background_color'] : $this->defaultBgColor;
			$colori = trim($_POST['callphoner_icon_color']) != '' ? $_POST['callphoner_icon_color'] : $this->defaultIconColor;
			$tel = trim($_POST['callphoner_number']) != '' ? $_POST['callphoner_number'] : '';
			$schedule = array();

			if (isset($_POST['callphoner_schedule'])) {
				foreach ($_POST['callphoner_schedule'] as $day => $value) {
					if ($value['status'] == 'on') {
						$schedule[$day] = array(
							'work' => $value['work'],
							'break' => $value['break']
						);
					}
				}
			}

			$settings = array(
				'status' => $status,
				'size' => $size,
				'color' => $color,
				'colori' => $colori,
				'tel' => $tel,
				'schedule' => $schedule
			);

			update_option('callphoner_settings', $settings, true);

			add_action('admin_notices', array($this, 'admin_notices'));
		}
	}
	
	public function admin_notices() {
		echo '<div class="updated"><p>'.__('Settings saved...', 'callphoner').'</p></div>';
	}

	private function loadSettings() {
		$this->settings = get_option('callphoner_settings');
	}

	public function init_texdomain() {
		load_plugin_textdomain('callphoner', false, dirname(plugin_basename(__FILE__)).'/languages/' );
	}

	public function wp_footer() {
		if (isset($this->settings['status']) && $this->settings['status'] == 'enabled' && $this->settings['tel'] != '') {
			$day_of_week = strtolower(date('l'));
			$currentTime = time();

			if (isset($this->settings['schedule'][$day_of_week])) {
				$show = false;

				if ($this->settings['schedule'][$day_of_week]['work'] == 'around') $show = true;
				elseif (is_array($this->settings['schedule'][$day_of_week]['work'])) {
					$f = $this->settings['schedule'][$day_of_week]['work']['from'];
					$t = $this->settings['schedule'][$day_of_week]['work']['to'];

					$from = strtotime(date('Y-m-d') . ' ' . $f);
					$to = strtotime(date('Y-m-d') . ' ' . $t);

					if ($currentTime > $from && $currentTime < $to) {
						$show = true;

						if (is_array($this->settings['schedule'][$day_of_week]['break'])) {
							$break_f = $this->settings['schedule'][$day_of_week]['break']['from'];
							$break_t = $this->settings['schedule'][$day_of_week]['break']['to'];

							$break_from = strtotime(date('Y-m-d') . ' ' . $break_f);
							$break_to = strtotime(date('Y-m-d') . ' ' . $break_t);

							if ($currentTime > $break_from && $currentTime < $break_to) $show = false;
						}
					}
				}

				if ($show !== false) {
					?>
					<style type="text/css">
						a.call {
							bottom: 10px;
							display: block;
							left: 45%;
							position: fixed;
							z-index: 10000;
							color: <?=$this->settings['colori'];?>;
							width: <?=$this->settings['size'];?>px;
							height: <?=$this->settings['size'];?>px;
							padding: <?php echo $this->settings['size']/4-3; ?>px;
							font-size: <?php echo $this->settings['size']/2; ?>px;
							-webkit-animation: heartbeat 1s infinite;
							-moz-animation: heartbeat 1s infinite;
							animation: heartbeat 1s infinite;
							background: <?=$this->settings['color'];?>;;
							border: 3px solid #fff;
							border-radius: <?php echo $this->settings['size']/2; ?>px;
							opacity: 0.9;
							box-shadow: 0 0 2px 2px #e4e4e4;
							box-sizing: border-box;
							-wibkit-sizing: border-box;
							-moz-sizing: border-box;
							-o-sizing: border-box;
						}

						@-webkit-keyframes heartbeat {
							92% {
								-webkit-transform: scale(1, 1);
							}
							94% {
								-webkit-transform: scale(1.2, 1.2);
							}
							96% {
								-webkit-transform: scale(1, 1);
							}
							98% {
								-webkit-transform: scale(1.1, 1.1);
							}
							100% {
								-webkit-transform: scale(1, 1);
							}
						}
						@-moz-keyframes heartbeat {
							92% {
								-moz-transform: scale(1, 1);
							}
							94% {
								-moz-transform: scale(1.2, 1.2);
							}
							96% {
								-moz-transform: scale(1, 1);
							}
							98% {
								-moz-transform: scale(1.1, 1.1);
							}
							100% {
								-moz-transform: scale(1, 1);
							}
						}
						@keyframes heartbeat {
							92% {
								transform: scale(1, 1);
							}
							94% {
								transform: scale(1.2, 1.2);
							}
							96% {
								transform: scale(1, 1);
							}
							98% {
								transform: scale(1.1, 1.1);
							}
							100% {
								transform: scale(1, 1);
							}
						}
					</style>
					<a href="tel:<?= $this->settings['tel']; ?>" class="call dashicons dashicons-phone"></a>
					<?php
				}
			}
		}
	}

	public function support_html() {
		?>
			<div class="wrap instruction">
			<h1><?php _e('Instruction', 'callphoner'); ?></h1>

			<?php if (get_locale() == 'ru_RU') { ?>
				<p>Настройки плагина очень простые. Можете наглядно посмотреть видео:</p>
				<p><iframe width="420" height="315" src="https://www.youtube.com/embed/zT8ezDKt_q4?rel=0" frameborder="0" allowfullscreen></iframe></p>
				<p>Вы можете выбрать цветовую гамму и размер отображаемой кнопки.</p>
				<p>Дальше вам остается настроить время работы вашего офиса/менеджера, чтобы кнопка не выводилась в нерабочее время. Для этого отметьте дни недели и напротив выберите время работы плагина.</p>
				<p>Обратите внимание на время вашего сервера. Оно может отличаться от вашего текущего времени и зависит от вашей хостинг компании. Поэтому скорректируйте время в полях настройки плагина CallPhone'r исходя из погрешности во времени в сравнении с сервером.</p>
				<div class="autor_plugin">
					<h3>Разработчики</h3>
					<div>
						<img src="https://www.gravatar.com/avatar/5480336fef49a6c9a0c15beea7771941?d=mm&s=50&r=G" alt="Vitalik" /><a href="http://mojwp.ru" target="_blank">Виталик mojWP</a><br/>Автор<br/>SEO, HTML/CSS
					</div>
					<div>
						<img src="https://www.gravatar.com/avatar/afcaa467847bce7547124f48e0b46115?d=mm&s=50&r=G" alt="Nicolay" /><a href="http://wpskills.ru/" target="_blank">Николай Бирулин</a><br/>Разработка<br/>PHP & WP Developer
					</div>
				</div>
			<?php } else { ?>
			
			
				<div class="autor_plugin">
					<p>The settings of the plugin are very simple.
					<p>You can choose the color and the size of the button.
					<p>And it's left just to set up the working hours of your office/manager, for the button was not to be visible in the hours off. 
					<p>For it to do, just mark the day of week and choose the time of work for the plug-in.
					<p>Please, pay attention to the time of your server. It can differ from your current time and depends on your hosting company.  So you should correct the time in the setting fields of the plug-in CallPhone'r, taking into consideration the error in time in comparison with the server.

					<h3>Development</h3>
					<div>
						<img src="https://www.gravatar.com/avatar/5480336fef49a6c9a0c15beea7771941?d=mm&s=50&r=G" alt="Vitalik" /><a href="http://mojwp.ru" target="_blank">Vitalik mojWP</a><br/>Author<br/>SEO, HTML/CSS
					</div>
					<div>
						<img src="https://www.gravatar.com/avatar/afcaa467847bce7547124f48e0b46115?d=mm&s=50&r=G" alt="Nicolay" /><a href="http://wpskills.ru/" target="_blank">Nicolay Birulin</a><br/>Developer<br/>PHP & WP Developer
					</div>
				</div>
			<?php } ?>
		</div>
		<?php
	}

	public function activation() {

	}

	public function deactivation() {

	}

}

new CallPhoner();

?>