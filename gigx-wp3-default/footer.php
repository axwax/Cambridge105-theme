<?php
/*
File Description: Default Footer
Built By: GIGX
Theme Version: 0.5.11
*/
?>
        </div><!-- end of container-->
      	<?php if ( is_active_sidebar( 'above_footer_widgets' ) ) : // Widgets Above Footer ?>
        	<div id="above-footer-widgets">
        		<?php dynamic_sidebar('above_footer_widgets'); ?>
        	</div>  
        <?php endif; ?> 
      </div><!-- end of main -->      
      <div id="footer">
      	<p class="pleft blacktext smallcaps">&copy; <?php bloginfo('show'); ?> <?php echo date("Y"); ?></p>
      	<p class="pright blacktext smallcaps">Created by: <a href="http://gigx.co.uk/"><img src="<?php bloginfo('template_url'); ?>/images/gigx-logo36x11.png" width="36" height="11" alt="GIGX.co.uk" title="GIGX.co.uk"/></a></p>
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
