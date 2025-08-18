<?php



defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Version_102 extends App_module_migration

{

     public function up()

     {


         add_option('user_mention_send_email' , 1 , 0 );

         add_option('user_mention_send_notification' , 1 , 0 );

     }

}

