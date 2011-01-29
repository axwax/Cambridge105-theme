=== Author Image ===
Contributors: Denis-de-Bernardy
Donate link: http://www.semiologic.com/partners/
Tags: author-image, semiologic
Requires at least: 2.8
Tested up to: 3.0
Stable tag: trunk


Lets you easily add author images on your site.


== Description ==

The author image plugin for WordPress lets you easily add author images on your site.

It creates a widget that you can insert in a sidebar, or much about anywhere if using the [Semiologic theme](http://www.semiologic.com/software/sem-reloaded/).

Alternatively, place the following call in the loop where you want the author image to appear:

    <?php the_author_image(); ?>

To configure your author image, browse Users / Your Profile in the admin area.

= Author's Bio =

You can configure the widget so it outputs the author's description in addition to his image.

This fits well on a site where the author's image is placed in a sidebar, or the [Semiologic theme](http://www.semiologic.com/software/sem-reloaded/) when the widget is placed immediately after the posts' content -- i.e. "About The Author."

= Multi-Author Sites =

For sites with multitudes of authors, the widget offers the ability to insert a link to the author's posts -- his archives.

= Single Author Sites =

Normally, the widget will only display an author image when it can clearly identify who the content's author actually is. In other words, on singular pages or in the loop.

If you run a single author site, or a site with multiple ghost writers, be sure to check the "This site has a single author" option. The widget will then output your image at all times.

= Help Me! =

The [Semiologic forum](http://forum.semiologic.com) is the best place to report issues. Please note, however, that while community members and I do our best to answer all queries, we're assisting you on a voluntary basis.

If you require more dedicated assistance, consider using [Semiologic Pro](http://www.getsemiologic.com).


== Installation ==

1. Upload the plugin folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Make the `wp-content` folder writable by your server (chmod 777)


== Screenshots ==

1. Screenshot of Author Image in action


== Frequently Asked Questions ==

= Image Style =

You can use the `.entry_author_image` CSS class to customize where and how the image appears.

For instance:

    .entry_author_image {
      float: left;
      border: solid 1px outset;
      margin: 1.2em 1.2em 0px .1em;
    }


= Overriding CSS Floats =

When displaying wide videos, images or tabular data, it becomes desirable to bump the content below the author's image. To achieve this, insert the following code in your post:

	<div style="clear:both;"></div>


== Change Log ==

= 4.0.2 =

- WP 3.0 compat

= 4.0.1 =

- Fix for authors with a space in their username
- Tweak the default Widget Contexts

= 4.0 =

- WP_Widget class
- Allow to add the author's bio after the image
- Allow to add a link to the author's posts on the image
- Localization
- Code enhancements and optimizations