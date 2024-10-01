      </div>
   </div>
   <footer id="page-footer">
      <div class="constrained-to-mid">
         <div id="fat-footer">
            <table>
               <tbody>
                  <tr>
                     <td>
                        <a id="page-footer-schedule-link" target="_blank" href="<?php echo get_theme_mod( 'dhamma_schedule_link'); ?>">
                           <h2>View Schedule and Apply</h2>
                           <img id="footer-schedule" alt="Schedule" src="<?php echo get_template_directory_uri();?>/images/footer-schedule6.png">
                        </a>
                     </td>
                     <td>
                        <h2><a href='/contact/' style="text-decoration:none;">Contact Us</a></h2>
                        <a href="mailto:webmaster@boston.us.dhamma.org">webmaster@boston.us.dhamma.org</a><br>
                        <br>
			617-444-9355

                     </td>
                  </tr>
               </tbody>
            </table>
         </div>
      </div>
      <div id="footer-copyright-and-sitemap">
         <div class="constrained-to-mid">
            &copy; <?php echo date("Y"); ?> <?php bloginfo('name'); ?><!-- | <a href="/sitemap/">Sitemap</a>-->
         </div>
      </div>
   </footer>
   <!--[if gte IE 9]><!--> 
      <script type="text/javascript" src="https://code.jquery.com/jquery-1.7.2.min.js"></script>
      <script type="text/javascript" src="<?php bloginfo('template_url'); ?>/menu.js"></script>
   <!--<![endif]-->
   <?php wp_footer(); ?>
</body>
</html>

<?php
   function showMainNavigation() {
      global $wp; 
      global $post;
      echo "<ul>";
      if ( is_os_page ( $wp ) ) {
         $children = get_os_top_nav_items();
      } else {
         $children = get_ns_top_nav_items();
      }
      echo apply_filters( 'simple_section_page_list', $children );
      echo "</ul>";
      return true; 
   }

   function get_ns_top_nav_items () {
      return wp_list_pages(array( 
         'exclude' => '306,1727,4336,120',  //sitemap (306), recipes(1727), trustee list (4336), and os home (120)
         'title_li' => '', 
         'depth' => 1, 
         'echo' => false
      ));
   }

   function get_os_top_nav_items () {
      return wp_list_pages(array( 
         'exclude' => '306,1727,4336',  //sitemap (306), recipes(1727), and trustee list (4336)
         'title_li' => '', 
         'child_of' => '120', //os home
         'depth' => 1, 
         'echo' => false
      ));
   }

   function get_addon_page ( $parent_ID, $current_page_parent ) {
      $page_list = get_pages ( array(
         'sort_column' => 'menu_order',
         'sort_order' => 'asc',
         'parent' => $parent_ID,
         'exclude' => '1727'  //recipes(1727)
      ));
      $pages = array();
      foreach ( $page_list as $page ) {
         $pages[] += $page->ID;
      }
      $current = array_search ( $current_page_parent, $pages );
      $nextID = $pages [ ( $current + 1 ) % count ( $pages ) ];
      $children_array = get_pages ( array (
         'parent' => $nextID,
         'sort_column' => 'menu_order',
         'sort_order' => 'asc',
         'exclude' => '306' //sitemap (306) 
      ));
      if ( !empty ( $children_array ) ) {
         $nextID = reset ( $children_array ); 
      }

      return "<li><a href='" . get_permalink ( $nextID ) . "'>" . get_the_title ( $nextID ) . "</a>";
   }

   function showRelatedPages() {
      global $exclude_pages;
      global $post;
      if ( is_page() ) {
         if ( isset($post) && is_object($post) ) /* _get_post_ancestors($post) */;   //workaround for occassional problems - JDH 7/29/2013 I Have no idea what this does. _get_post_ancestors commented out by JCH 10/24/23.
      }

      $parent_id = wp_get_post_parent_id ( $post ) ; 
      
      if( is_single() or is_category() or is_home() ) {
         $pages = wp_list_categories( array('hierarchical'=> '1', 'title_li' => '', 'depth' => '2', 'echo'=> false) );
         echo "News";
         echo "<ul>";
         echo apply_filters( 'simple_section_page_list', $pages );
         echo "</ul>";
         return true;

      } else if ( $parent_id == 0 || empty ( $parent_id ) ) {
         showMainNavigation();
         return true;

      } else if( is_page() ) {
         global $post;
         global $wp; 
         $current_page_parent = ( $post->post_parent ? $post->post_parent : $post->ID );
         
         if ( is_os_page( $wp ) ) {
            $addon_page = get_addon_page ( 120, $current_page_parent );
         } else {
            $addon_page = get_addon_page ( 0, $current_page_parent );
         } 
         $pages = wp_list_pages( array(
            'exclude'   => '306,1727,4336',  //sitemap (306), recipes(1727), and trustee list (4336)
            'title_li'  => '',
            'child_of'  => $current_page_parent,
            'depth'     => '1',
            'echo'      => false)
         );
         echo "<ul>";
         echo apply_filters( 'simple_section_page_list', $pages );
         echo $addon_page;
         echo "</ul>";
         return true;
      }
   }
?>
