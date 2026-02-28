<?php
include_once("sp108e.php");
$lights = new SP108E("192.168.1.10");
$lights->set_monocolor_animation("static");
$lights->set_color("00FF00");
$lights->set_leds(100);
$lights->set_speed(185);
print_r($lights->settings());


//$lights->toggle_power();			      //toggle power on and off
//echo $lights->is_ready();			      //is device ready (1/0)
//$lights->set_strip("WS2811");		  	//(SM16703,TM1804,UCS1903,WS2811,WS2801,SK6812,LPD6803,LPD8806,APA102,APA105,DMX512,TM1914,TM1913,P9813,INK1003,P943S,P9411,P9413,TX1812,TX1813,GS8206,GS8208,SK9822,TM1814,SK6812_RGBW,P9414,P9412)
//$lights->set_order("GBR");			    //(RGB,RBG,GRB,GBR,BRG,BGR)
//$lights->set_speed(185);			      //sets animation speed (1-255)
//$lights->set_monocolor_animation("static");//sets mono animation values=(meteor,breathing,wave,catch up,static,stack,flash,flow)
//$lights->enable_multicolor_animation();//sets animation to the multicolor ones (auto)
//$lights->set_multicolor_animation(0);//sets mixed colors animation (1-180)
//$lights->set_leds(101);			        //1-2048
//$lights->set_segments(1);			      //1-10
//$lights->set_color("00FF00");			  //sets color to html color codes FF0000=RED, R=FF,G=00,B=00, FF=255,00=0, so FF is max color RED,00 is lowest color for Green and Blue
//echo $lights->get_name();			      //returns device name
//echo $lights->set_name("FXS_LIGHTS");//only accepts 10 characters give a couple secs to set after getting name
//echo $lights->leds;				          //gets led count
//echo $lights->get_raw_settings();		//gets only raw settings
//print_r($lights->get_settings()[1]);//re-gets new settings
//print_r($lights->get_error());		  //outputs last error info (0=>err number,1=>err text) [errors clear after getting error info]
