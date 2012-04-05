<?php
/*
File Description: The default Loop (used for posts, including podcasts)
Author: Axel Minet
Theme Version: 0.6.1
*/

/*
   $request_array = explode('/',$_SERVER['REQUEST_URI']);
   foreach($request_array as $segment){
      if($segment=="shows") echo "it's a show!";
   }
*/
$q='';
if ($pos=stripos($haystack=$_SERVER['REQUEST_URI'],$needle='shows/')) {
   $is_show=true;
   $showsearch = substr($haystack,($pos+strlen($needle)));
   $q=esc_attr($showsearch);
}

    $form = '<form role="search" method="get" id="searchform" action="' . home_url( '/' ) . '" >
    <div><label class="screen-reader-text" for="s">' . __('Search for:') . '</label>
    <input type="text" value="' . $q . '" name="s" id="s" />
    <input type="submit" id="searchsubmit" value="'. esc_attr__('Search') .'" />
    </div>
    </form>';
?>

<div class="posts">
      		<h2 class="center">404 - Not Found</h2>
      		<p class="center">Sorry, but you are looking for something that isn't here.</p>
      		<?php echo $form; ?>
</div><!-- end of posts div -->