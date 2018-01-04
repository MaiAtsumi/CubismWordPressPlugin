<?php
/*

 Plugin Name: Live2D WordPress Plugin
 Plugin URI: http://www.live2d.com/
 Description: Live2D WordPress Plugin
 Version: 0.0.1
 Author: Live2D.inc
 Author URI: http://www.live2d.com/

 Copyright 2017 Live2D.inc

 This program is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

 Please see LICENSE.txt for the full license.

*/

function live2d_activate_activation(){ add_option('Live2DActivateConfirm', 'confirm'); }
if(function_exists('register_activation_hook')){ register_activation_hook(__FILE__, 'live2d_activate_activation'); }

function live2d_activate_deactivation(){ delete_option('Live2DActivateConfirm'); }
if(function_exists('register_deactivation_hook')){ register_deactivation_hook(__FILE__, 'live2d_activate_deactivation'); }
if(function_exists('register_uninstall_hook')){ register_uninstall_hook(__FILE__, 'live2d_activate_deactivation'); }

function live2d_activate_confirm(){
	if(is_admin()){

		$state = get_option('Live2DActivateConfirm');
		if($state == 'confirm'){

			$confirm = $_POST["live2d_activate_confirm"];
			if($confirm == 'accept'){
				update_option('Live2DActivateConfirm', 'accepted');
				if(isset($_GET['activate'])){ unset($_GET['activate']); }
				header("Location: " . $_SERVER['PHP_SELF']);

			}else if($confirm == 'reject'){
				delete_option('Live2DActivateConfirm');
				deactivate_plugins(plugin_basename(__FILE__));
				if(isset($_GET['activate'])){ unset($_GET['activate']); }
				header("Location: " . $_SERVER['PHP_SELF']);

			}else{
?>
	<div id="layer" style="z-index: 1000; position: fixed; top: 0; left: 0; display:block; width: 100%; height: 100%; opacity: 0.6; background-color: #000000;"></div>
	<div id="box" style="z-index: 1001; position: fixed; top: 10%; left: 15%; display: block; width: 70%; background-color: #ffffff; textAlign: center;">
		<div style="margin:10px;">
			<div style="font-size: 24px; line-height: 1.8em; font-weight: bold; text-align: center;">Cubism Core License</div>
		</div>
		<iframe class="license-frame" src="http://www.live2d.com/eula/live2d-proprietary-software-license-agreement_en.html" style="width: 100%; height: 500px; border-style: none; background-color: #f8f8f8;"></iframe><br />
		<div style="margin:20px 10px; overflow: hidden;">
			<div style="float:left; width: 50%; font-size: 30px; line-height: 1.8em; text-align: center; font-weight: bold;">
				<form name="live2d_activate_accept" method="POST" action="#" >
					<a href="javascript:document.live2d_activate_accept.submit()">承諾(Accept)</a>
					<input type="hidden" name="live2d_activate_confirm" value="accept">
				</form>
			</div>
			<div style="float:left; width: 50%; font-size: 30px; line-height: 1.8em; text-align: center;">
				<form name="live2d_activate_reject" method="POST" action="#" >
					<a href="javascript:document.live2d_activate_reject.submit()">拒否(Reject)</a>
					<input type="hidden" name="live2d_activate_confirm" value="reject">
				</form>
			</div>
		</div>
	</div>
<?php
			}

		}else if($state == 'accepted'){
			//nothing
		}
	}
}
add_action('admin_init', 'live2d_activate_confirm');

class Live2D{
	function __construct(){
		$state = get_option('Live2DActivateConfirm');
		//SDKリリースライセンスに同意していないなら終了
		if($state != 'accepted'){ return; }

		//Init path
		$opt = get_option('live2d_options');
		if(!$opt){
			$opt['moc_path'] = "assets/Koharu/Koharu.moc3";
			$opt['tex1_path'] = "assets/Koharu/Koharu_01.png";
			$opt['tex2_path'] = "assets/Koharu/Koharu_02.png";
			$opt['tex3_path'] = "assets/Koharu/Koharu_03.png";
			$opt['mot1_path'] = "assets/Koharu/Koharu_01.motion3.json";
			$opt['mot2_path'] = "assets/Koharu/Koharu_02.motion3.json";
			$opt['mot3_path'] = "assets/Koharu/Koharu_03.motion3.json";
			$opt['phy_path'] = "assets/Koharu/Koharu.physics3.json";
			$opt['attach_tag'] = ".entry-header";
			$opt['pos_x'] = "200";
			$opt['pos_y'] = "250";
			$opt['scale'] = "400";
			update_option('live2d_options', $opt);
			$opt = array();
		}

		//Load javascript
		if(!is_admin()){
			wp_enqueue_script('pixi', 'https://cdnjs.cloudflare.com/ajax/libs/pixi.js/4.6.1/pixi.min.js', '', '1.0', false);

			//By including below library in your project you agree to http://live2d.com/eula/live2d-proprietary-software-license-agreement_en.html
			//下記のライブラリを使用する際は次のライセンスに同意する必要があります http://live2d.com/eula/live2d-proprietary-software-license-agreement_jp.html
			wp_enqueue_script('live2dcubismcore', 'https://s3-ap-northeast-1.amazonaws.com/cubism3.live2d.com/sdk/js_eap/live2dcubismcore.min.js', '', '1.0', false);

			$plugin_url = plugin_dir_url( __FILE__ );
			$plugin_path = plugin_dir_path(__FILE__);
			wp_enqueue_script('live2dcubismframework', $plugin_url . 'js/live2dcubismframework.js', '', '1.0', false);
			wp_enqueue_script('live2dcubismpixi', $plugin_url . 'js/live2dcubismpixi.js', '', '1.0', false);

			//Set options
			$opt = get_option('live2d_options');
			$data = "var theme_path = '" . $plugin_url . "';\n";
			if(isset($opt['moc_path']) && !empty($opt['moc_path']) && file_exists($plugin_path . $opt['moc_path'])){ $data .= "var moc_path = '" . $opt['moc_path'] . "';\n"; }
			if(isset($opt['tex1_path']) && !empty($opt['tex1_path']) && file_exists($plugin_path . $opt['tex1_path'])){ $data .= "var tex1_path = '" . $opt['tex1_path'] . "';\n"; }
			if(isset($opt['tex2_path']) && !empty($opt['tex2_path']) && file_exists($plugin_path . $opt['tex2_path'])){ $data .= "var tex2_path = '" . $opt['tex2_path'] . "';\n"; }
			if(isset($opt['tex3_path']) && !empty($opt['tex3_path']) && file_exists($plugin_path . $opt['tex3_path'])){ $data .= "var tex3_path = '" . $opt['tex3_path'] . "';\n"; }
			if(isset($opt['mot1_path']) && !empty($opt['mot1_path']) && file_exists($plugin_path . $opt['mot1_path'])){ $data .= "var mot1_path = '" . $opt['mot1_path'] . "';\n"; }
			if(isset($opt['mot2_path']) && !empty($opt['mot2_path']) && file_exists($plugin_path . $opt['mot2_path'])){ $data .= "var mot2_path = '" . $opt['mot2_path'] . "';\n"; }
			if(isset($opt['mot3_path']) && !empty($opt['mot3_path']) && file_exists($plugin_path . $opt['mot3_path'])){ $data .= "var mot3_path = '" . $opt['mot3_path'] . "';\n"; }
			if(isset($opt['phy_path']) && !empty($opt['phy_path']) && file_exists($plugin_path . $opt['phy_path'])){ $data .= "var phy_path = '" . $opt['phy_path'] . "';\n"; }
			$data .= "var attach_tag = '" . ((isset($opt['attach_tag']) && !empty($opt['attach_tag'])) ? $opt['attach_tag'] : ".entry-header") . "';\n"; 
			$data .= "var pos_x = " . ((isset($opt['pos_x']) && !empty($opt['pos_x'])) ? $opt['pos_x'] : "0") . ";\n"; 
			$data .= "var pos_y = " . ((isset($opt['pos_y']) && !empty($opt['pos_y'])) ? $opt['pos_y'] : "0") . ";\n"; 
			$data .= "var scale = " . ((isset($opt['scale']) && !empty($opt['scale'])) ? $opt['scale'] : "100") . ";\n"; 

			wp_enqueue_script('pixiWordPressPlugin', $plugin_url . 'js/pixiWordPressPlugin.js', '', '1.0', false);
			wp_script_add_data('pixiWordPressPlugin', 'data', $data);

		}else{
			add_action('admin_menu', array($this, 'add_pages'));
		}
	}

	function add_pages(){
		add_menu_page('Live2D Settings', 'Live2D Settings', 'level_8', __FILE__, array($this, 'option_page'), '', 26);
	}
	function option_page(){
		if(isset($_POST['live2d_options'])){
			check_admin_referer('live2d_action', 'live2d_nonce_filed');
			$opt = $_POST['live2d_options'];
			update_option('live2d_options', $opt);
?>
			<div class="updated fade"><p><strong><?php _e('Options saved.'); ?></strong></p></div>
<?php
		}
?>
		<div class="wrap">
			<div id="icon-options-general" class="icon32"><br /></div>
			<h2>Live2D Settings</h2>
			<form action="" method="post">
<?php
			wp_nonce_field('live2d_action', 'live2d_nonce_filed');
			$opt = get_option('live2d_options');
			$moc_path = isset($opt['moc_path']) ? $opt['moc_path']: null;
			$tex1_path = isset($opt['tex1_path']) ? $opt['tex1_path']: null;
			$tex2_path = isset($opt['tex2_path']) ? $opt['tex2_path']: null;
			$tex3_path = isset($opt['tex3_path']) ? $opt['tex3_path']: null;
			$mot1_path = isset($opt['mot1_path']) ? $opt['mot1_path']: null;
			$mot2_path = isset($opt['mot2_path']) ? $opt['mot2_path']: null;
			$mot3_path = isset($opt['mot3_path']) ? $opt['mot3_path']: null;
			$phy_path = isset($opt['phy_path']) ? $opt['phy_path']: null;
			$attach_tag = isset($opt['attach_tag']) ? $opt['attach_tag']: null;
			$pos_x = isset($opt['pos_x']) ? $opt['pos_x']: null;
			$pos_y = isset($opt['pos_y']) ? $opt['pos_y']: null;
			$scale = isset($opt['scale']) ? $opt['scale']: null;
?> 
				<table class="form-table">
					<tr valign="top">
						<th scope="row"><label for="moc_path">Model path (Required)</label></th>
						<td>
							<input name="live2d_options[moc_path]" type="text" id="moc_path" value="<?php  echo $moc_path ?>" class="regular-text" /><br />
							e.g. assets/Koharu/Koharu.moc3
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="tex1_path">Texture path 1 (Required)</label></th>
						<td>
							<input name="live2d_options[tex1_path]" type="text" id="tex1_path" value="<?php  echo $tex1_path ?>" class="regular-text" /><br />
							e.g. assets/Koharu/Koharu_01.png
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="tex2_path">Texture path 2</label></th>
						<td>
							<input name="live2d_options[tex2_path]" type="text" id="tex2_path" value="<?php  echo $tex2_path ?>" class="regular-text" /><br />
							e.g. assets/Koharu/Koharu_02.png
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="tex3_path">Texture path 3</label></th>
						<td>
							<input name="live2d_options[tex3_path]" type="text" id="tex3_path" value="<?php  echo $tex3_path ?>" class="regular-text" /><br />
							e.g. assets/Koharu/Koharu_03.png
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="mot1_path">Motion path 1</label></th>
						<td>
							<input name="live2d_options[mot1_path]" type="text" id="mot1_path" value="<?php  echo $mot1_path ?>" class="regular-text" /><br />
							e.g. assets/Koharu/Koharu_01.motion3.json
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="mot2_path">Motion path 2</label></th>
						<td>
							<input name="live2d_options[mot2_path]" type="text" id="mot2_path" value="<?php  echo $mot2_path ?>" class="regular-text" /><br />
							e.g. assets/Koharu/Koharu_02.motion3.json
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="mot3_path">Motion path 3</label></th>
						<td>
							<input name="live2d_options[mot3_path]" type="text" id="mot3_path" value="<?php  echo $mot3_path ?>" class="regular-text" /><br />
							e.g. assets/Koharu/Koharu_03.motion3.json
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="phy_path">Physics path</label></th>
						<td>
							<input name="live2d_options[phy_path]" type="text" id="phy_path" value="<?php  echo $phy_path ?>" class="regular-text" /><br />
							e.g. assets/Koharu/Koharu.physics3.json
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="attach_tag">Attach tag (Required)</label></th>
						<td>
							<input name="live2d_options[attach_tag]" type="text" id="attach_tag" value="<?php  echo $attach_tag ?>" class="regular-text" /><br />
							e.g. .entry-header (CSS Selector)
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="pos_x">Possition x (Required)</label></th>
						<td>
							<input name="live2d_options[pos_x]" type="text" id="pos_x" value="<?php  echo $pos_x ?>" class="regular-text" /><br />
							e.g. 200
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="pos_y">Possition y (Required)</label></th>
						<td>
							<input name="live2d_options[pos_y]" type="text" id="pos_y" value="<?php  echo $pos_y ?>" class="regular-text" /><br />
							e.g. 250
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="scale">Scale (Required)</label></th>
						<td>
							<input name="live2d_options[scale]" type="text" id="scale" value="<?php  echo $scale ?>" class="regular-text" /><br />
							e.g. 400
						</td>
					</tr>
				</table>
				<p class="submit"><input type="submit" name="Submit" class="button-primary" value="変更を保存" /></p>
			</form>
		</div>
<?php
	}
}
$live2d = new Live2D;

