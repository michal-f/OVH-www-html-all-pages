/**
 * Plugin's jquery functions
 * Version 2.23
 */
function mapComplete(e,t){if(e.is(":hidden")){$.each(["show"],function(e,n){var r=$.fn[n];$.fn[n]=function(){this.trigger(n);r.apply(this,arguments);jQuery(".responsive-map").each(function(){if(jQuery(this).data("gmap")){jQuery(this).gMapResp("fixAfterResize");var e=jQuery(this).data("gmap").markers;if(t){for(var n=0;n<e.length;n++){google.maps.event.trigger(e[n],"click")}}}})}})}}function mapFix(){jQuery(".responsive-map").each(function(e,t){data=jQuery(this).data("gmap");if(data){var n=jQuery(this).data("gmap").gmap;google.maps.event.trigger(n,"resize");jQuery(this).gMapResp("fixAfterResize")}})}function openMarker(e){jQuery(".responsive-map").each(function(){if(jQuery(this).data("gmap")){var t=jQuery(this).data("gmap").markers;google.maps.event.trigger(jQuery(this).gMapResp("getMarker",e),"click")}})}