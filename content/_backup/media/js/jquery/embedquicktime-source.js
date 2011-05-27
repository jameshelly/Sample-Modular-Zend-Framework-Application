/*
 * Embed QuickTime - jQuery plugin for embedding videos
 *
 * Copyright (c) 2007-2008 Andreas Haugstrup Pedersen
 * 
 * Mail: andreas [at] solitude [dot] dk
 * Web: http://www.solitude.dk/archives/embedquicktime/
 *
 * Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 *
 */
(function(jQuery) {
// Locate hvlog entries and embed them.
jQuery.embedquicktime = function(options) {
  // Global options.
  options = jQuery.extend({jquery: null, plugin: null, target: null}, options);
  // Accepted file types.
  var types = new Array('video/quicktime', 'video/mp4', 'video/x-m4v');
  var files = new Array('mov', 'mp4', 'm4v', '3gp');
  var wmvfiles = new Array('avi', 'wmv', 'mpeg', 'mpg', 'asf', 'asx');
  var wmvtypes = new Array('video/mpeg', 'video/x-ms-asf', 'video/x-msvideo', 'video/x-ms-wmv');
  var audiotypes = new Array('audio/x-wav', 'audio/x-aiff', 'audio/x-mpegurl', 'audio/mpeg');
  var audiofiles = new Array('wav', 'aiff', 'aif', 'mp3', 'm3u', 'm4a', 'm4b');
  
  function in_array(needle, haystack) {
    for (i in haystack) {
      if (needle == haystack[i]) {
        return true;
      }
    }
    return false;
  }
  // Loop over hvlog elements
  jQuery('.hvlog').not('.hvlog-processed').each(function(){
    if (options.target != null && jQuery(this).attr('id') != options.target) {
      return;
    }
    // Check if element contains quicktime link.
    var currenttype = null;
    jQuery(this).find('a[rel=enclosure]').each(function(){
      var a = jQuery(this);
      var href = a.attr('href');
      var hreftype = href.substr(href.lastIndexOf('.')+1);
      var type = a.attr('type');
      currenttype = null;
      if (in_array(hreftype, files) || in_array(type, types)) {
        // We have a quicktime.
        currenttype = 'qt';
      } else if (in_array(hreftype, wmvfiles) || in_array(type, wmvtypes)) {
        // We have windows media
        currenttype = 'wmv';
      }  else if (in_array(hreftype, audiofiles) || in_array(type, audiotypes)) {
        // We have audio
        currenttype = 'audio';
      }
      if (currenttype != null) {
        jQuery(a).click(function(){
          jQuery(a).embedquicktime(a, {'eqttype': currenttype});
          return false;
        });
      }
    });
    // Insert 'share' link for embedding.
    if (currenttype != null && jQuery(this).hasClass('share')) {
      embedHTML = 'Error: JavaScript URLs not found.';
      if (options.jquery != null && options.plugin != null) {
        var id = 'eqt-'+ (new Date()).getTime();
        embedHTML = '<script type="text/javascript" src="'+ options.jquery +'"></script> <script type="text/javascript" src="'+ options.plugin +'"></script> <script type="text/javascript">jQuery.noConflict();jQuery(document).ready(function() { jQuery.embedquicktime({jquery: "'+options.jquery+'", plugin: "'+options.plugin+'", target: "'+ id +'"});});</script><div class="'+ jQuery(this).attr('class') +'" id="'+ id +'">'+ jQuery(this).html() +'</div>';        
      }
      
      // Insert 'share' link at the end of hvlog.
      var hvlog = jQuery(this);
      hvlog.append('<div class="hvlog_share"><div class="hvlog_share_link">share media</div></div>').find('div.hvlog_share_link').each(function(){
        jQuery(this).css({'background': '#fc9', 'color': 'black', 'font': '12px sans-serif', 'cursor': 'pointer', 'width': '12ex', 'text-align': 'center', 'padding': '1px'}).hover(function() {
          jQuery(this).css({'color': 'green'});
        }, function() {
          jQuery(this).css({'color': 'black'});
        }).click(function(){
          var wrap = jQuery(this).parents('.hvlog_share');
          if (wrap.find('.hvlog_share_share:visible').length > 0) {
            wrap.find('.hvlog_share_share').slideUp('slow');
            wrap.find('.hvlog_share_link').empty().append('share media');
          } else {
            // Set width/height again to ensure correct dimensions in FireFox
            var thumb = wrap.parents('.hvlog').find('img').eq(0);
            if (thumb.length == 0) {
              thumb = wrap.parents('.hvlog').find('object').eq(0);
            }
            wrap.width(thumb.width());
            wrap.find('.hvlog_share_link').empty().append('close');
            if (wrap.find('div.hvlog_share_share').length == 0) {
              jQuery('<div class="hvlog_share_share" style="display:none"><span style="text-align:right;padding:4px">Copy &amp; paste</span><textarea readonly="readonly">'+ embedHTML +'</textarea></div>').css({'width': wrap.width()-20, 'height': '100px', 'background': '#fc9', 'color': 'black', 'font': '12px sans-serif', 'padding': '8px 10px'}).find('textarea').css({'font-size': '10px', 'display': 'block', 'overflow': 'hidden', 'margin': '0 auto 15px auto', 'width': wrap.width()-30, 'height': '85px'}).end().hide().click(function() {
                jQuery(this).find('textarea').select();
                jQuery(this).find('textarea').focus();
                return false;
              }).appendTo(wrap).slideDown('slow');
            } else {
              wrap.find('div.hvlog_share_share').slideDown('slow');
            }
          }
          return false;
        });
      });
    };
    jQuery(this).addClass('hvlog-processed');
  });
  return this;
};
// Embed an object
jQuery.fn.embedquicktime = function(a, options) {
  // Global options.
  var opts = jQuery.extend({}, jQuery.fn.embedquicktime.defaults, options);
  return this.each(function(){
    // Grab hvlog.
    var hvlog = jQuery(this).parents('.hvlog');
    // Element options.
    var o = hvlog.metadata ? jQuery.extend({}, opts, hvlog.metadata()) : opts;
    o = a.metadata ? jQuery.extend({}, o, a.metadata()) : o;
    // Set source
    var src = jQuery(this).attr('href');
    if (o.src) {
      src = o.src;
    }
    // Grab width/height of thumbnail.
    var thumb = hvlog.find('img').eq(0);
    if (thumb.length > 0) {
      var width = thumb.width();
      var height = thumb.height();
      if (o.width) {
        width = o.width;
      }
      if (o.height) {
        height = o.height;
      }
      if (o.eqttype == 'audio') {
        height = 0;
      }
      if ((!o.controller || o.controller != 'true') || (!o.showcontrols || o.showcontrols != 'true')) {
        height = parseInt(height)+16;
      }
      // Loop through options and build <param> and <embed> attributes.
      var params = '';
      var embed = '';
      for (i in o) {
        if (i != 'height' && i != 'width' && i != 'src' && i != 'autoplay' && i != 'autoStart' && i != 'pluginspage' && i != 'eqttype') {
          params = params+'<param name="'+i+'" value="'+o[i]+'">';
          embed = embed+' '+i+'="'+o[i]+'"';          
        }
      }
      // Save any 'share' features.
      var share = hvlog.find('.hvlog_share').eq(0).clone(true);
      // Insert video code.
      hvlog.empty();
      if (o.eqttype == 'qt' || o.eqttype == 'audio') {
        hvlog.append('<object width="'+width+'" height="'+height+'" autoplay="'+o.autoplay+'" classid="clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B" codebase="http://www.apple.com/qtactivex/qtplugin.cab"><param name="src" value="'+src+'"><param name="autoplay" value="'+o.autoplay+'">'+params+'<embed src="'+src+'" width="'+width+'" height="'+height+'" autoplay="'+o.autoplay+'" pluginspage="http://www.apple.com/quicktime/download/"'+embed+'></embed></object>').append(share);
      } else if (o.eqttype == 'wmv') {
        hvlog.append('<object CLASSID="CLSID:22d6f312-b0f6-11d0-94ab-0080c74c7e95" codebase="http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=5,1,52,701" type="application/x-oleobject" width="' + width + '" height="' + height + '"><param name="fileName" value="' + src + '" ><param name="autoStart" value="' + o.autoStart +'">'+params+'<embed type="application/x-mplayer2" pluginspage="http://www.microsoft.com/Windows/MediaPlayer/" width="' + width + '" height="'+ height + '" src="' + src + '" autostart="' + o.autoStart + '"'+embed+'></embed></object>').append(share);
      }
      return true;
    }
    return false;
  });
};
// Default options.
jQuery.fn.embedquicktime.defaults = {
  autoplay: 'true',
  autoStart: 'true',
  eqttype: 'qt'
};
})(jQuery);

/*
 * Metadata - jQuery plugin for parsing metadata from elements
 *
 * Copyright (c) 2006 John Resig, Yehuda Katz, J�örn Zaefferer, Paul McLanahan
 *
 * Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 *
 * Revision: $Id: jquery.metadata.js 3620 2007-10-10 20:55:38Z pmclanahan $
 *
 */
(function($){$.extend({metadata:{defaults:{type:'class',name:'metadata',cre:/({.*})/,single:'metadata'},setType:function(type,name){this.defaults.type=type;this.defaults.name=name;},get:function(elem,opts){var settings=$.extend({},this.defaults,opts);if(!settings.single.length)settings.single='metadata';var data=$.data(elem,settings.single);if(data)return data;data="{}";if(settings.type=="class"){var m=settings.cre.exec(elem.className);if(m)data=m[1];}else if(settings.type=="elem"){if(!elem.getElementsByTagName)return;var e=elem.getElementsByTagName(settings.name);if(e.length)data=$.trim(e[0].innerHTML);}else if(elem.getAttribute!=undefined){var attr=elem.getAttribute(settings.name);if(attr)data=attr;}if(data.indexOf('{')<0)data="{"+data+"}";data=eval("("+data+")");$.data(elem,settings.single,data);return data;}}});$.fn.metadata=function(opts){return $.metadata.get(this[0],opts);};})(jQuery);