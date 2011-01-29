<?php
## demo widget
class gigx_post_highlights extends WP_Widget {
  function gigx_post_highlights() {
    parent::WP_Widget( false, $name = 'GIGX Post Highlights' );
  }

  function widget( $args, $instance ) {
    extract( $args );
    $title = apply_filters( 'widget_title', $instance['title'] );
    ?>

    <?php
	echo $before_widget;
    ?>

    <?php
      if ($title) {
	echo $before_title . $title . $after_title;
      }
    ?>

        <?php if(function_exists("insert_post_highlights")) insert_post_highlights(); ?>


     <?php
       echo $after_widget;
     ?>
     <?php
  }

  function update( $new_instance, $old_instance ) {
    return $new_instance;
  }

  function form( $instance ) {
    $title = esc_attr( $instance['title'] );
    ?>

    <p>
      <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?>
      <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
      </label>
    </p>
    <?php
  }
}
## demo widget

?>
