<?php
      
# change header image size
add_filter('gigx_header_image_width','cam105_header_image_width');
add_filter('gigx_header_image_height','cam105_header_image_height');
function cam105_header_image_width($size){
   return 510;
}
function cam105_header_image_height($size){
   return 120;
}

?>