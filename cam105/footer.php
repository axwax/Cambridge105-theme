<?php
/*
File Description: Default Footer
Built By: GIGX
Theme Version: 0.5.9
*/
?>
        </div><!-- end of container--> 
      </div><!-- end of main -->
    	<?php if ( is_active_sidebar( 'above_footer_widgets' ) ) : // Widgets Above Footer ?>
      	<div id="above-footer-widgets" class="clearfix">
      		<?php dynamic_sidebar('above_footer_widgets'); ?>
      	</div>  
      <?php endif; ?>            
      <div id="footer" class="clearfix">
      	<p class="pleft blacktext smallcaps">&copy; <?php bloginfo('show'); ?> <?php echo date("Y"); ?></p>
      	<p class="pright blacktext smallcaps">Design by Stewart Paske &amp; <a href="http://axwax.de/"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/axwax16x16.png" width="16" height="16" target="_blank" style="vertical-align: text-top" alt="AxWax.de" title="AxWax.de"/></a> - Hosted by: <a href="http://gigx.co.uk/" target="_blank" ><img src="<?php bloginfo('template_url'); ?>/images/gigx-logo36x11.png" width="36" height="11" alt="GIGX.co.uk" title="GIGX.co.uk"/></a></p>
      </div>
    	<?php if ( is_active_sidebar( 'below_footer_widgets' ) ) : // Widgets Below Footer ?>
      	<div id="below-footer-widgets">
      		<?php dynamic_sidebar('below_footer_widgets'); ?>
      	</div>  
      <?php endif; ?>       
    </div> <!-- end of page--> 
    <?php wp_footer(); ?>
  </body>
</html>
