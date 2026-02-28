<?php
//$lights->toggle_power();			//toggle power on and off
//echo $lights->is_ready();			//is device ready (1/0)
//$lights->set_strip("WS2811");			//(SM16703,TM1804,UCS1903,WS2811,WS2801,SK6812,LPD6803,LPD8806,APA102,APA105,DMX512,TM1914,TM1913,P9813,INK1003,P943S,P9411,P9413,TX1812,TX1813,GS8206,GS8208,SK9822,TM1814,SK6812_RGBW,P9414,P9412)
//$lights->set_order("GBR");			//(RGB,RBG,GRB,GBR,BRG,BGR)
//$lights->set_speed(185);			//sets animation speed (1-255)
//$lights->set_monocolor_animation("static");   //sets mono animation values=(meteor,breathing,wave,catch up,static,stack,flash,flow)
//$lights->enable_multicolor_animation();	//sets animation to the multicolor ones (auto)
//$lights->set_multicolor_animation(0);	        //sets mixed colors animation (1-180)
//$lights->set_leds(101);			//1-2048
//$lights->set_segments(1);			//1-10
//$lights->set_color("00FF00");			//sets color to html color codes FF0000=RED, R=FF,G=00,B=00, FF=255,00=0, so FF is max color RED,00 is lowest color for Green and Blue
//echo $lights->get_name();			//returns device name
//echo $lights->set_name("FXS_LIGHTS");		//only accepts 10 characters give a couple secs to set after getting name
//echo $lights->leds;				//gets led count
//echo $lights->get_raw_settings();		//gets only raw settings
//print_r($lights->get_settings()[1]);		//re-gets new settings
//print_r($lights->get_error());		//outputs last error info (0=>err number,1=>err text) [errors clear after getting error info]
class SP108E{
   public $ip;
   public $port;
   public $name;
   public $color;
   public $speed;
   public $strip;
   public $brightness;
   public $order;
   public $power;
   public $leds;
   public $segments;
   public $ani;
   public $rec_patterns;
   public $white_channel_brightness;
   public $raw_settings;
   public $error_number;
   public $error;
   protected $amono_ani=['meteor'=>'cd','breathing'=>'ce','wave'=>'d1','catch up'=>'d4','static'=>'d3','stack'=>'cf','flash'=>'d2','flow'=>'d0'];
   protected $astrips=['SM16703'=>'00','TM1804'=>'01','UCS1903'=>'02','WS2811'=>'03','WS2801'=>'04','SK6812'=>'05','LPD6803'=>'06','LPD8806'=>'07','APA102'=>'08','APA105'=>'09','DMX512'=>'0a','TM1914'=>'0b','TM1913'=>'0c','P9813'=>'0d','INK1003'=>'0e','P943S'=>'0f','P9411'=>'10','P9413'=>'11','TX1812'=>'12','TX1813'=>'13','GS8206'=>'14','GS8208'=>'15','SK9822'=>'16','TM1814'=>'17','SK6812_RGBW'=>'18','P9414'=>'19','P9412'=>'1a'];
   protected $acolors_orders=['RGB'=>'00','RBG'=>'01','GRB'=>'02','GBR'=>'03','BRG'=>'04','BGR'=>'05'];
   function __construct($ip="",$port=8189){$this->ip=$ip;$this->port=$port;$this->get_settings();}
   function __destruct(){/*echo "exiting".__CLASS__;*/}
   protected function d2h($d){$s=dechex($d);if(strlen($s)==1){return "0".$s;}else{return $s;}}
   protected function send_data($data="",$bres=false,$reslen=0){$fp=fsockopen($this->ip,$this->port,$en,$es,30);if(!$fp){$this->error_number=$en;$this->error=$es;return "Error: {$es} ({$en})";}else{$data=str_replace(" ",'',$data);fwrite($fp,hex2bin($data));if($bres){$res=fread($fp,$reslen);}fclose($fp);if($bres){return $res;}}}
   public function set_ip($ip){$this->ip=$ip;}
   public function set_port($port){$this->$port;}
   public function get_error(){$en=$this->error_number;$es=$this->error;$this->error_number=0;$this->error="";return Array($en,$es);}
   public function is_ready(){return $this->send_data("380000002f83",true,1);}
   public function set_color($color){$color=str_replace('#','',$color);$this->send_data("38".$color."2283");}
   public function set_speed($speed){if($speed<0){$speed=0;}if($speed>255){$speed=255;}$this->send_data("38".$this->d2h($speed)."00000383");}
   public function set_brightness($brightness=255){if(brightness<0){$brightness=0;}if($brightness>255){$brightness=255;}$this->send_data("38".$this->d2h($brightness)."00002a83");}
   public function get_name(){$this->name=$this->send_data("380000007783",true,18);return $this->name;}
   public function get_raw_settings(){$result=$this->send_data("380000001083",true,17);return bin2hex($result);}
   public function enable_multicolor_animation(){$this->send_data("380000000683");}
   public function toggle_power(){$this->send_data("38000000aa83");}
   public function set_leds($leds){$this->send_data("38".$this->d2h($leds)."001F2d83");}
   public function set_segments($segs){$this->send_data("38".$this->d2h($segs)."001F2e83");}
   public function set_strip($type){$this->send_data("38".$astrips[$type]."00001c83");}
   public function set_order($order){$this->send_data("38".$acolors_orders[$order]."00003c83");}
   public function set_monocolor_animation($index){$this->send_data("38".$this->amono_ani[strtolower($index)]."00002c83");}
   public function set_multicolor_animation($index){if($index>180){$index=180;}if($index<0){$index=0;}$this->send_data("38".$this->d2h($index-1)."00002c83");}
   public function set_name($name="FXS_LIGHTS"){$r=$this->send_data("380000001483",true,1);if($r==1){$this->send_data(bin2hex($name));return 1;}else{return 0;}}
   public function set_white_channel_brightness($brightness="255"){if($brightness>255){$brightness=255;}if($brightness<0){$brightmess=0;}$this->send_data("38".$this->d2h($brightness)."00000883");}
   public function get_settings(){$set=$this->get_raw_settings();$this->ani=array_search(substr($set,4,2),$this->amono_ani);if(empty($this->ani)){$this->ani=hexdec(substr($set,4,2))+1;}$this->strip=array_search(substr($set,26,2),$this->astrips);$this->order=array_search(substr($set,10,2),$this->acolors_orders);$this->power=(hexdec(substr($set,2,2))?'On':'Off');$this->color=substr($set,20,6);$this->brightness=hexdec(substr($set,8,2));$this->speed=hexdec(substr($set,6,2));$this->leds=hexdec(substr($set,12,4));$this->segments=hexdec(substr($set,16,4));$this->rec_patterns=hexdec(substr($set,28,2));$this->white_channel_brightness=hexdec(substr($set,30,2));$this->raw_settings=$set;$aset=['power_on'=>$this->power,'cur_ani'=>$this->ani,'ani_speed'=>$this->speed,'cur_brightness'=>$this->brightness,'color_order'=>$this->order,'leds_per_segment'=>$this->leds,'led_segments'=>$this->segments,'cur_color'=>$this->color,'strip'=>$this->strip,'rec_patterns'=>$this->rec_patterns,'white_channel_brightness'=>$this->white_channel_brightness];$pset="";foreach($aset as $key=>$value){$pset.=ucwords(str_replace("_",' ',$key)).": ".$value."<br>";}return array($this->raw_settings,$pset);}
   public function settings(){$aset=['power_on'=>$this->power,'cur_ani'=>$this->ani,'ani_speed'=>$this->speed,'cur_brightness'=>$this->brightness,'color_order'=>$this->order,'leds_per_segment'=>$this->leds,'led_segments'=>$this->segments,'cur_color'=>strtoupper($this->color),'strip'=>$this->strip,'rec_patterns'=>$this->rec_patterns,'white_channel_brightness'=>$this->white_channel_brightness];$pset="";foreach($aset as $key=>$value){$pset.=ucwords(str_replace("_",' ',$key)).": ".$value."<br>";}return array($this->raw_settings,$pset,$aset);}
   public function get_settings_raw(){$set=$this->get_raw_settings();$cur_ani=substr($set,4,2);if(empty($cur_ani)){$cur_ani=substr($set,4,2);}$strip=substr($set,26,2);$clr_order=substr($set,10,2);$pwr_on=substr($set,2,2);$cur_clr=substr($set,20,6); $settings=['power_on'=>$pwr_on,'cur_ani'=>$cur_ani,'ani_speed'=>substr($set,6,2),'cur_brightness'=>substr($set,8,2),'color_order'=>$clr_order,'leds_per_segment'=>substr($set,12,4),'led_segments'=>substr($set,16,4),'cur_color'=>$cur_clr,'strip'=>$strip,'rec_patterns'=>substr($set,28,2),'white_channel_brightness'=>substr($set,30,2)];$pset="";foreach($sett as $key=>$value){$pset.=ucwords(str_replace("_",' ',$key)).": ".$value."<br>";}return array($set,$pset);}
}
?>