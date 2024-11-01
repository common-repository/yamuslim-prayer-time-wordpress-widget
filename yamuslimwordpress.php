<?php
/*
Plugin Name: Ya'Muslim Prayer Time Wordpress Widget
Description: A widget for displaying daily Prayer Times
Version: 1.0
Author: Ya'Muslim
Author URI: http://www.ya-muslim.com
License: GPL2
*/

class yamuslimwordpress extends WP_Widget {
	function yamuslimwordpress() {

		$options = array( 'classname' => 'yamuslimwordpress', 
						  'description' => __( "A widget for displaying daily Prayer Times" ) );

		$this->WP_Widget('yamuslimwordpress', __("Ya'Muslim Widget"), $options);
	}

	function form($instance) {

		if(!isset($instance["title"])){
			$instance["title"] = "";
		}

		echo '<div id="yamuslimwordpress-widget-form">';
		echo '<p><label for="' . $this->get_field_id("title") .'">' . __("Title") . ' :</label>';
		echo '<input type="text" name="' . $this->get_field_name("title") . '" ';
		echo '</div>';
	}

	function widget($args, $instance) {

		if ( isset($instance['error']) && $instance['error'] )
			return;

		if(isset($args['before_title']))
			$before_title = $args['before_title'];
		else
			$before_title = '<h3 class="widget-title">';
		
		if(isset($args['after_title']))
			$after_title = $args['after_title'];
		else
			$after_title = '</h3>';
		
		if(isset($args['before_widget']))
			$before_widget = $args['before_widget'];
		else
			$before_widget = '';
		
		if(isset($args['after_widget']))
			$after_widget = $args['after_widget'];
		else
			$after_widget = '';

		echo $before_widget;
		echo $before_title;
		echo $instance['title'];
		echo $after_title; 
		?>
		<!-- logo picture -->
		<center>
			<img src="<?= plugins_url('/logo.png', __FILE__); ?>" width="96"/>
		</center>

		<script charset="UTF-8">
			var prayTimes = new PrayTimes();
			var gps = [0, 0];

			function appManageActivity() {

				var date = new Date();
				var times = prayTimes.getTimes( date, gps, 'auto', 'auto', 0);

				var list = ['Fajr', 'Sunrise', 'Dhuhr', 'Asr', 'Maghrib', 'Isha', 'Midnight'];
				var listViewLtn = ['Fajr', 'Shorouk', 'Dhohr', 'Asr', 'Maghrib', 'Isha', 'Kiaam'];
				var listViewArb = ['الفجر', 'الشروق', 'الظهر', 'العصر', 'المغرب', 'العشاء', 'القيام'];
				
				var html = '<table id="timetable">';
				
				for(var i in list)	{
					html += '<tr>';
					html += 	'<td class="text-left">' + listViewLtn[i] + '</td>';
					html += 	'<td class="text-center">' + times[list[i].toLowerCase()] + '</td>';
					html += 	'<td class="text-right">' + listViewArb[i] + '</td>';
					html += '</tr>';
				}
				html += '</table>';
				document.getElementById('table-prayer').innerHTML = html;
			}

			jQuery(document).ready(function($) {
				$.getJSON('https://ipinfo.io/geo', function(response) { 
			    		gps = response.loc.split(',');
				});
			});

			var intervalID = setInterval(function(){
				appManageActivity();
			}, 500);
		</script>
		<div align="center" id="table-prayer"></div>
		<?php			
		echo $after_widget;
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		return $instance;
	}
}

add_action('widgets_init', create_function('', 'return register_widget("yamuslimwordpress");'));

function themeslug_enqueue_style() {
	wp_enqueue_style( 'yamuslim_display', plugins_url("/css/yamuslim_wordpress_widget.css", __FILE__ ), false );
}

function themeslug_enqueue_script() {
	wp_enqueue_script( 'yamuslim_script', plugins_url("/js/yamuslim_wordpress_widget.js", __FILE__ ), false );
}

add_action( 'wp_enqueue_scripts', 'themeslug_enqueue_style' );
add_action( 'wp_enqueue_scripts', 'themeslug_enqueue_script' );