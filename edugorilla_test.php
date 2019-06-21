<?php
/**
 * Plugin Name: Edugorilla Plugin
 * Plugin URI: 
 * Description: This plugin save name and an email address in MySQL by form submission.
 * Version: 1.0
 * Author: Natasha Dixit
 * Author URI: http://www.natashadixit.com
 */

add_action('admin_menu', 'edugorilla_plugin_setup');

function edugorilla_plugin_setup(){
    add_menu_page( 'Edugorilla Plugin Page', 'Edugorilla Plugin', 'manage_options', 'edugorilla-plugin', 'edugorilla_plugin_form' );
}

function edugorilla_plugin_form()
{ 
?>
    <!DOCTYPE html>
    <html>
    <head>
        <title></title>
        <style>
        div {
            margin-bottom:5px;
        }
         
        input{
            margin-bottom:4px;
        }
        #form_div{
            margin-top: 10px;
            margin-left: 500px;
        }
        #display_table{
            margin-top: 10px;
            margin-left: 200px;
            margin-right: 200px;
        }
        table {
          border-collapse: collapse;
        }
        table, th, td {
          border: 1px solid black;
        }
        </style>
    </head>
    <body>
        <div id="form_div">
            <form id="myform" method="post">
                <div>
                    <label for="fullname">Name</label>
                    <input type="text" name="fullname" value="" >
                </div>
                <div>
                    <label for="email">Email</label>
                    <input type="text" name="email" value="">
                </div>
                <input style="margin-left: 70px; height: 30px; width: 100px; background-color: #3498DB; color: white; border: 25%;" type="submit" name="submit" value="Register"/>
            </form>
        </div>
       <?php if (!empty($_POST)) {
               $data = array('fullname' => $_POST['fullname'],'email' => $_POST['email']);

               global $table_prefix, $wpdb;

               $tblname = 'edugorilla_plugin_data';
               $wp_track_table = $table_prefix .$tblname;

               $result = $wpdb->insert( $wp_track_table, $data, array( '%s', '%s' ) );

        } // end of if ?>
        <div id="display_table">
           <table width="100%">
                <thead>
                    <tr>
                        <th>Sr. No.</th>
                        <th>Name</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    <?php global $table_prefix, $wpdb;
                    $tblname = 'edugorilla_plugin_data';
                    $wp_track_table = $table_prefix."$tblname";
                    $result = $wpdb->get_results( "SELECT * FROM $wp_track_table" );
                    foreach ($result as $row){ ?>
                    <tr>
                        <td><?php echo $row->id; ?></td>
                        <td><?php echo $row->fullname; ?></td>
                        <td><?php echo $row->email; ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table> 
        </div>
    </body>
    </html>      
<?php
} // end of edugorilla_plugin_form

function create_edugorilla_user_table()
{
    global $table_prefix, $wpdb;

    $tblname = 'edugorilla_plugin_data';
    $wp_track_table = $table_prefix . "$tblname ";

    //Check to see if the table exists already, if not, then create it

    if($wpdb->get_var( "show tables like '$wp_track_table'" ) != $wp_track_table) 
    {
        $sql = "CREATE TABLE $wp_track_table (
                  id int(11) NOT NULL AUTO_INCREMENT,
                  fullname varchar(100) DEFAULT '' NOT NULL,
                  email varchar(100) DEFAULT '' NOT NULL,
                  PRIMARY KEY  (id)
                ) $charset_collate;";

        require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
        dbDelta($sql);
    }
} // end of create_edugorilla_user_table

register_activation_hook( __FILE__, 'create_edugorilla_user_table' );
?>