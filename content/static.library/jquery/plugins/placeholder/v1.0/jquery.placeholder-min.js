/*
*
* Placeholder.js minified (jQuery version) 1.0
* Creates a placeholder for browsers that don't support it
*
* @ Created by Guillaume Gaubert
* @ http://widgetulous.com/placeholderjs/
* @ © 2011 Guillaume Gaubert
*
* @ Default use :
* 	Placeholder.init();
*
*/

Placeholder={defaultSettings:{normal:'#000000',placeholder:'#C0C0C0'},init:function(b){if(b){$j.extend(Placeholder.defaultSettings,b)}$j('input[type=text], textarea').each(function(){if($j(this).attr("placeholder")){$j(this).focus(function(){Placeholder.onSelected(this)});$j(this).blur(function(){Placeholder.unSelected(this)});var a=$j(this).attr("placeholder");$j(this).css("color",Placeholder.defaultSettings.placeholder);$j(this).val(a)}})},onSelected:function(a){if($j(a).val()==$j(a).attr("placeholder")){$j(a).val('')}$j(a).css("color",Placeholder.defaultSettings.normal)},unSelected:function(a){if($j(a).val().length<=0){$j(a).css("color",Placeholder.defaultSettings.placeholder);$j(a).val($j(a).attr("placeholder"))}}};