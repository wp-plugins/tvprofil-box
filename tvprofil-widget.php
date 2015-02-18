<?php
/*

  Plugin name:  TvProfile Widget
  Plugin URI:   http://tvprofil.net/phbox/
  Description:  Widget for TV schedule display
  Version:      1.1
  Author:       Vladimir Kovacevic
  Author URI:   http://tvprofil.net/

  Copyright 2011  Vladimir Kovacevic  (email : vladimir.kovacevic@tvprofil.net)

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
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

 */

class TvProfilWidget extends WP_Widget {

	/** constructor */
	function __construct() {
		$widget_ops = array(
			'classname' => 'TvProfilWidget',
			'description' => 'Prikazuje TV raspored za domaće kanale.');
		parent::WP_Widget('TvProfilWidget', 'TvProfile Widget', $widget_ops);
	}

	/** @see WP_Widget::widget */
	function widget($args, $instance) {
		extract($args);

		echo $before_widget;
		
		$customCSS="(Custom CSS file)";

		$title = apply_filters('widget_title', $instance['title']);
		if ($title)		echo '<b>' . $title . '</b>';
		$css = $instance['style']==$customCSS&&!empty($instance['custom'])?$instance['custom']:'http://tv.phazer.info/css/v3/'.$instance['style'];
		echo '<div><iframe src="http://tv.phazer.info/phazerbox/'.$instance['group'].'/?css='.$css.'" allowtransparency="true" width="'.$instance['width'].'" height="'.$instance['height'].'" frameborder="0" marginwidth="0" marginheight="0" scrolling="no"></iframe></div>';

		echo $after_widget;
	}

	/** @see WP_Widget::update */
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['width'] = (int)$new_instance['width'];
		$instance['height'] = (int)$new_instance['height'];
		$instance['group'] = (int)$new_instance['group'];
		$instance['style'] = strip_tags($new_instance['style']);
		$instance['custom'] = strip_tags($new_instance['custom']);
		return $instance;
	}

	/** Widget settings */
	function form($instance) {
		$defaults = array('title' => 'TV Program', 'width' => 200, 'height' => 300, 'group'=>0 );
		$instance = wp_parse_args((array) $instance, $defaults);

		$title = esc_attr($instance['title']);
		
		
		$groups = array(
			'Croatia'=> array(
				20	=>	'HTV1, HTV2, RTL, NOVA',
				0	=>	'HTV1, HTV2, RTL, NOVA, RTL2, Doma',
				1	=>	'HTV1, HTV2, NOVA, FTV, BHT1'),
			'BiH'	=> array(
				3	=>	'BHT1, PINK BH, OBN, FTV',
				6	=>	'RTRS, ATV, PINK',
				9	=>	'BHT1, FTV, RTRS, HTV1, NOVA, OBN'),
			'Serbia'	=> array(
				21	=>	'B92, PrvaTV, PINK, Avala, Happy, RTS1, RTS2',
				19	=>	'PrvaTV, Avala, Vojvodina1, Vojvodina2',
				5	=>	'B92, PINK, RTS1, RTS2'),
			'Slovenia'	=> array(
				13	=>	'SLO1, SLO2, POP TV, KANAL A, TV3'),
			'Montenegro'	=> array(
				16	=>	'CG1, CG2, TVIN, PINKM'),
			'Macedonia'	=> array(
				10	=>	'MRT, AlfaTV, Sitel, Kanal5'),
			'Sport'	=> array(
				7	=>	'SK, SK+, Arena1, Arena2',
				17	=>	'Eurosport1, Eurosport2, SK, SK+',
				18	=>	'Arena1, Arena2, Arena3, Arena4'),
			'Hungary'	=> array(
				8	=>	'm1, m2, TV2, RTL Klub'),
			'Germany'	=> array(
				22	=>	'RTL, RTL2, PRO7, SAT1'),
			'Austria'	=> array(
				26	=>	'ORF1, ORF2, ATV1, ATV2'),
			'Italy'	=> array(
				23	=>	'RAI1, RAI2, Rete4, Canale5'),
			'Romania'	=> array(
				24	=>	'TVR1, TVR2, TVRi'),
			'Turkey'	=> array(
				25	=>	'TRT1, TRT Haber, TRT3, TRT TURK')
		);
		
		$customCSS="(Custom CSS file)";
		$styles = array(
			"tvprofil.css", "black.css", "plasma.css","windows8.css","xjure.css", "xjure2.css", "boxstyle.css", "sportnet.css", "crveni.css", "livada.pondi.css", "mojportal.css", "sivi.css", $customCSS
		);
		
		echo '<p>';
		echo '<label for="'.$this->get_field_id('title').'">'._e('Title:').'</label> <input id="'.$this->get_field_id('title').'" name="'.$this->get_field_name('title').'" value="'.esc_attr($instance['title']).'" class="widefat" /></p>';
		echo '<p><label for="'.$this->get_field_id('width').'">'._e('Width: (px)').'</label> <input id="'.$this->get_field_id('width').'" name="'.$this->get_field_name('width').'" value="'.round($instance['width']).'" class="widefat" /></p>';
		echo '<p><label for="'.$this->get_field_id('height').'">'._e('Height: (px)').'</label> <input id="'.$this->get_field_id('height').'" name="'.$this->get_field_name('height').'" value="'.round($instance['height']).'" class="widefat" /></p>';
		
		echo '<p><label for="'.$this->get_field_id('group').'">'._e('Group:').'</label> <select style="max-width:220px" id="'.$this->get_field_id('group').'" name="'.$this->get_field_name('group').'">';
		foreach ($groups as $gname=>$gval)
		{
			echo '<optgroup label="'.$gname.'">';
			foreach ($gval as $key=>$val)
			{
				echo '<option value="'.$key.'"'.($key==$instance['group']?' selected="selected"':'').'>&nbsp; '.$val.'</option>';
			}
			echo '</optgroup>';
		}
		echo '</select></p>';
		
		echo '<p><label for="'.$this->get_field_id('style').'">'._e('Style:').'</label> <select id="'.$this->get_field_id('style').'" name="'.$this->get_field_name('style').'">';
		foreach ($styles as $val)
		{
			echo '<option value="'.$val.'"'.($val==$instance['style']?' selected="selected"':'').'>'.$val.'</option>';
		}
		echo '</select></p>';
		
		echo '<p><label for="'.$this->get_field_id('custom').'">'._e('Custom CSS full URL path').'</label> <input id="'.$this->get_field_id('custom').'" name="'.$this->get_field_name('custom').'" value="'.esc_attr($instance['custom']).'" class="widefat" /></p>';

	}

}

add_action('widgets_init', create_function('', 'return register_widget("TvProfilWidget");'));
register_activation_hook(__FILE__, array('TvProfilWidget', 'install'));
