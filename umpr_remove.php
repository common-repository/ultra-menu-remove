<?php

/**
 * Plugin Name:       Ultra Menu Remove
 * Plugin URI:        http://uzzal.plugin.eduinblog.com/wp-admin/admin.php?page=remove_menu_setting
 * Description:       It able to remove menu page.
 * Version:           1.0.0
 * Author:            Uzzal Mondal
 * Author URI:        https://profiles.wordpress.org/mondal
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       google_map_maker
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

register_activation_hook( __FILE__, 'umr_text_domain_activate' );

function umr_text_domain_activate() {
load_plugin_textdomain(
			'umrp',
			 false,
			 dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);
}

class UMR_Admin{

	protected $version ;

	public function __construct() {
      $this->version = '1.0.0';
      define("UMR_PATH",plugin_dir_url( __FILE__ ));
      add_action( 'admin_menu', array($this,'umr_remove_menus'));
      add_action( 'admin_init', array($this,'umr_enqueue_styles' ));
      add_action( 'admin_init', array($this,'umr_enqueue_scripts' ));
	  add_action( 'admin_menu',array($this,'umr_page_creater'));

      
	}

	public function umr_enqueue_styles() {

		  if(is_admin()){ 
	             wp_enqueue_style('umr_bootstrap' , 
				 UMR_PATH . 'css/umr_bootstrap.css',  
				 array(), $this->version, 'all' );

				 wp_enqueue_style('urm_bootstrap-toggle-style' , 
				 UMR_PATH. 'css/urm_bootstrap-toggle-style.min.css',  
				 array(), $this->version, 'all' );
	       }
	}


	public function umr_enqueue_scripts() {

        if(is_admin()){   	
	        wp_enqueue_script('jquery');
	        wp_enqueue_script('jquery-ui-core'); 

	        wp_enqueue_script('umr_bootstrap.min' ,
		      UMR_PATH. 'js/umr_bootstrap.min.js', 
		      array( 'jquery' ), $this->version, false );

	        wp_enqueue_script('umr_bootstrap-toggle.min' ,
		      UMR_PATH. 'js/umr_bootstrap-toggle.min.js', 
		      array( 'jquery' ), $this->version, false );

		    wp_enqueue_script('umr-setting' ,
		      UMR_PATH. 'js/umr-setting.js', 
		      array( 'jquery' ), $this->version, false );	    

		    
        } 

	}

	public function umr_define_page(){
		
		$parents = array(
			            array(

			                 'page_title'  => 'Remove Menu Settings',              //$parent_slug
						     'menu_title'  => 'Remove Menu Settings',          //$page_title
						     'capability'  => 'manage_options',           //$capability
						     'menu_slug'   => 'remove_menu_settings',              //$menu_title
						     'dashicons'   => 'dashicons-calendar-alt'    //$dashicons
			            ));

		 return $parents ;

	}

	public function umr_define_subpage(){

		$parents = array(
			          
			           array(
			                 'parent_slug' => 'remove_menu_settings',    //$parent_slug
						     'page_title'  => 'Settings Menu page',       //$page_title
						     'menu_title'  => 'Settings Menu page',       //$menu_title
						     'capability'  => 'manage_options', //$capability
						     'menu_slug'   => 'remove_menu_setting', 
			            ));
                       

		return $parents ;
	}

	public function umr_create_menu_page(){
        $parents = $this->umr_define_subpage();
        if ( $parents ) {
            foreach ($parents as $parent) {
                add_menu_page(   $parent['page_title'], 
                	             $parent['menu_title'],
                	             $parent['capability'],
                	             $parent['menu_slug'],
                	             array( $this , $parent['menu_slug'].'_callback')
                	             ) ; 
             }
        
        }
        
    }

    public function umr_create_submenu_page(){
        $parents = $this->umr_define_subpage();
        if ( $parents ) {
            foreach ($parents as $parent) {
                add_submenu_page($parent['parent_slug'] , 
                	             $parent['page_title'],
                	             $parent['menu_title'],
                	             $parent['capability'],
                	             $parent['menu_slug'],
                	             array( $this , $parent['menu_slug'].'_callback' )) ; 
             }
        
        }
      }

    public function umr_page_creater(){
       	   $this->umr_create_menu_page();
       	   $this->umr_create_submenu_page();
     }

    public static function umr_get_instance(){
    	$instance = new UMR_Admin();
    }
    public function remove_menu_setting_callback(){

    $current_user = wp_get_current_user();
    if ( 1 == $current_user->ID ):
       (isset($_POST['umr_submit']))? UMR_Admin::umr_set_value(): '';
    else :
          _e('You are not permited');
    	  remove_menu_page( 'index.php' );                  //Dashboard
		  remove_menu_page( 'jetpack' );                    //Jetpack* 
		  remove_menu_page( 'edit.php' );                   //Posts
		  remove_menu_page( 'upload.php' );                 //Media
		  remove_menu_page( 'edit.php?post_type=page' );    //Pages
		  remove_menu_page( 'edit-comments.php' );          //Comments
		  remove_menu_page( 'themes.php' );                 //Appearance
		  remove_menu_page( 'plugins.php' );                //Plugins
		  remove_menu_page( 'users.php' );                  //Users
		  remove_menu_page( 'tools.php' );                  //Tools
		  remove_menu_page( 'options-general.php' );        //Settings

		  return ;
       
    endif;
    	?>
           <h2><?php esc_html_e( 'Remove Menu page', 'umrp' );?></h2>  
			<div class="umr">
			  <div class="contener">
			   <div class="row">
			     <div class="col-sm-5">
			   	   <form action="" method="post">
			   	      <div class="col-sm-4">
                          <label for=""><?php esc_html_e( 'Dashboard', 'umrp' );?></label>
				   		  <div class="form-group">
		                  <input class="toggle-one form-control" 
		                         name="dashbord"
		                         <?php if( get_option('dashbord') == 'on'){echo "checked";}?>
		                         type="checkbox">
		                  </div>
			   	      </div>

			   	      <div class="col-sm-4">
                          <label for=""><?php esc_html_e( 'Jetpack', 'umrp' );?></label>
				   		  <div class="form-group">
		                  <input class="toggle-one form-control" 
		                          name="jetpack"
		                          <?php if( get_option('jetpack') == 'on'){echo "checked";}?>
		                          type="checkbox">
		                  </div>
			   	      </div>

			   	      <div class="col-sm-4">
                          <label for=""><?php esc_html_e( 'Posts', 'umrp' );?></label>
				   		  <div class="form-group">
		                  <input class="toggle-one form-control"
		                         name="posts" 
		                         <?php if( get_option('posts') == 'on'){echo "checked";}?>
		                         type="checkbox">
		                  </div>
			   	      </div>
			   	      <div class="col-sm-4">
                          <label for=""><?php esc_html_e( 'Media', 'umrp' );?></label>
				   		  <div class="form-group">
		                  <input class="toggle-one form-control" 
		                         name = "media"
		                         <?php if( get_option('media') == 'on'){echo "checked";}?>
		                         type="checkbox">
		                  </div>
			   	      </div>
			   	      <div class="col-sm-4">
                          <label for=""><?php esc_html_e( 'Pages', 'umrp' );?></label>
				   		  <div class="form-group">
		                  <input class="toggle-one form-control"
		                         name = "pages" 
		                         <?php if( get_option('pages') == 'on'){echo "checked";}?>
		                         type="checkbox">
		                  </div>
			   	      </div>

                       <div class="col-sm-4">
                          <label for=""><?php esc_html_e( 'Comments', 'umrp' );?></label>
				   		  <div class="form-group">
		                  <input class="toggle-one form-control"
		                         name = "comments" 
		                         <?php if( get_option('comments') == 'on'){echo "checked";}?>
		                         type="checkbox">
		                  </div>
			   	      </div>
			   	      

			   	      <div class="col-sm-4">
                          <label for=""><?php esc_html_e( 'Appearance', 'umrp' );?></label>
				   		  <div class="form-group">
		                  <input class="toggle-one form-control" 
		                         name ="appearance" 
		                         <?php if( get_option('appearance') == 'on'){echo "checked";}?>
		                         type="checkbox">
		                  </div>
			   	      </div>

			   	      <div class="col-sm-4">
                          <label for=""><?php esc_html_e( 'Plugins', 'umrp' );?></label>
				   		  <div class="form-group">
		                  <input class="toggle-one form-control"
		                         name ="plugins" 
		                         <?php if( get_option('plugins') == 'on'){echo "checked";}?>
		                         type="checkbox">
		                  </div>
			   	      </div>

			   	      <div class="col-sm-4">
                          <label for=""><?php esc_html_e( 'Users', 'umrp' );?></label>
				   		  <div class="form-group">
		                  <input class="toggle-one form-control" 
		                         name="users" 
		                         <?php if( get_option('users') == 'on'){echo "checked";}?>
		                         type="checkbox">
		                  </div>
			   	      </div>
			   	      <div class="col-sm-4">
                          <label for=""><?php esc_html_e( 'Tools', 'umrp' );?></label>
				   		  <div class="form-group">
		                  <input  class="toggle-one form-control" 
		                          name="tools" 
		                          <?php if( get_option('tools') == 'on'){echo "checked";}?>
		                          type="checkbox">
		                  </div>
			   	      </div>
			   	      <div class="col-sm-4">
                          <label for=""><?php esc_html_e( 'Settings', 'umrp' );?></label>
				   		  <div class="form-group">
		                  <input class="toggle-one form-control" 
		                         name="settings"
		                         <?php if( get_option('settings') == 'on'){echo "checked";}?>
		                         type="checkbox">
		                  </div>
			   	      </div>

			   	      <div class="col-sm-12">
					   <button type="submit" name="umr_submit" class="btn btn-primary">
					   	<?php esc_html_e( 'Submit', 'umrp' );?></button>
					  </div>
			   	   </form> 
			     </div>
			   </div>
			  </div>
			</div>
	<?php

   
      

    }
   
   public static function umr_set_value(){

     $dashbord = (isset($_POST['dashbord'])) ? sanitize_text_field( $_POST['dashbord'] ) :'';
     $jetpack =(isset($_POST['jetpack'])) ?  sanitize_text_field( $_POST['jetpack'] ) :'';
     $posts = (isset($_POST['posts'])) ? sanitize_text_field( $_POST['posts'] ) :'';
     $media = (isset($_POST['media'])) ? sanitize_text_field( $_POST['media'] ) :'' ;
     $pages = (isset($_POST['pages'])) ? sanitize_text_field( $_POST['pages'] ) : '';
     $comments = (isset($_POST['comments'])) ? sanitize_text_field( $_POST['comments'] ) : '';
     $appearance = (isset($_POST['appearance'])) ? sanitize_text_field( $_POST['appearance'] ) : '' ;
     $plugins = (isset($_POST['plugins'])) ? sanitize_text_field( $_POST['plugins'] ) :'' ;
     $users = (isset($_POST['users'])) ? sanitize_text_field( $_POST['users'] ) : '' ;
     $tools = (isset($_POST['tools'])) ? sanitize_text_field( $_POST['tools'] ) : '' ;
     $settings = (isset($_POST['settings'])) ? sanitize_text_field( $_POST['settings'] ) : '';

     

      update_option( 'dashbord', $dashbord , null ,  'no' );
      update_option( 'jetpack', $jetpack , null ,  'no' );
      update_option( 'posts', $posts , null ,  'no' );
      update_option( 'media', $media , null ,  'no' ) ;
      update_option( 'pages', $pages , null ,  'no' ) ;
      update_option( 'comments', $comments , null ,  'no' ) ;
      update_option( 'appearance',$appearance , null ,  'no' ) ;
      update_option( 'plugins', $plugins , null ,  'no' );
      update_option( 'users', $users  , null ,  'no' );
      update_option( 'tools', $tools , null ,  'no' );
      update_option( 'settings', $settings , null ,  'no' );
   }

   public static function umr_default_value_set(){

     ( get_option('dashbord' ) == false )? 
                  update_option( 'dashbord', 'on' , null ,  'no' ) : '' ;
     ( get_option('jetpack' ) == false )? 
                  update_option( 'jetpack', 'on' , null ,  'no' ) : '' ;
     ( get_option('posts' ) == false )? 
                  update_option( 'posts', 'on', null ,  'no' ) : '' ;
     ( get_option('media' ) == false )? 
                  update_option( 'media', 'on', null ,  'no' ) : '' ;
     ( get_option('pages' ) == false )? 
                  update_option( 'pages', 'on' , null ,  'no' ) : '' ;
     ( get_option('comments' ) == false )? 
                  update_option( 'comments', 'on' , null ,  'no' ) : '' ;
     ( get_option('appearance' ) == false )? 
                  update_option( 'appearance','on' , null ,  'no' ) : '' ;
     ( get_option('plugins' ) == false )? 
                  update_option( 'plugins', 'on' , null ,  'no' ) : '' ;
     ( get_option('tools' ) == false )? 
                  update_option( 'tools', 'on' , null ,  'no' ) : '' ;
     ( get_option('settings' ) == false )? 
                  update_option( 'settings', 'on' , null ,  'no' ) : '' ;
   }

   public function umr_remove_menus(){

   	if( get_option('dashbord') == 'on'){ remove_menu_page( 'index.php' ); }
   	if( get_option('jetpack') == 'on'){ remove_menu_page( 'jetpack' ); }
   	if( get_option('posts') == 'on'){ remove_menu_page( 'edit.php' ); }
   	if( get_option('media') == 'on'){ remove_menu_page( 'upload.php' ); }
   	if( get_option('pages') == 'on'){ remove_menu_page( 'edit.php?post_type=page' ); }
   	if( get_option('comments') == 'on'){ remove_menu_page( 'edit-comments.php' ); }
   	if( get_option('plugins') == 'on'){ remove_menu_page( 'plugins.php' ); }
   	if( get_option('appearance') == 'on'){ remove_menu_page( 'themes.php' ); }
   	if( get_option('users') == 'on'){ remove_menu_page( 'users.php' ); }
   	if( get_option('tools') == 'on'){ remove_menu_page( 'tools.php' ); }
   	if( get_option('settings') == 'on'){ remove_menu_page( 'options-general.php' ); }
   }
}

UMR_Admin::umr_get_instance();