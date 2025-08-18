<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class User_mention extends AdminController {

    public function __construct()
    {
        parent::__construct();

    }


    public function manage()
    {

        if (!is_admin()) {

            access_denied('user_mention');

        }



        $data['title'] = _l('user_mention_menu');


        $this->load->view('v_manage' , $data);

    }

    public function manage_save()
    {


        $user_mention_send_email = $this->input->post('user_mention_send_email');

        if ( !empty( $user_mention_send_email ) )
            update_option('user_mention_send_email' , 1 , 0);
        else
            update_option('user_mention_send_email' , 0 , 0);


        $user_mention_send_notification = $this->input->post('user_mention_send_notification');

        if ( !empty( $user_mention_send_notification ) )
            update_option('user_mention_send_notification' , 1 , 0);
        else
            update_option('user_mention_send_notification' , 0 , 0);


        set_alert('success' , _l('updated_successfully', _l('user_mention_menu') ) );

        redirect(admin_url('user_mention/manage'));

    }

}
