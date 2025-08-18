<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Customer_registration_confirmed extends App_mail_template
{
    protected $for = 'customer';

    protected $contact;

    public $slug = 'client-registration-confirmed';

    public $rel_type = 'contact';

    public function __construct($contact)
    {
        parent::__construct();
        $this->contact = $contact;
    }

    // public function build()
    // {
    //     $this->to($this->contact->email)
    //     ->set_rel_id($this->contact->id)
    //     ->set_merge_fields('client_merge_fields', $this->contact->userid, $this->contact->id);
    // }

    public function build()
    {
        $this->to($this->contact->email)
            ->set_rel_id($this->contact->id)
            ->set_merge_fields('client_merge_fields', $this->contact->userid, $this->contact->id);
    
        $CI =& get_instance();
        $CI->load->library('pdf/Nda_pdf', [$this->contact]);
    
        $pdf = new Nda_pdf($this->contact);
    
        $pdf_path = FCPATH . 'uploads/tmp/nda_' . $this->contact->id . '.pdf';
        $pdf->Output($pdf_path, 'F');
    
        if (file_exists($pdf_path)) {
            $this->add_attachment($pdf_path);
        }
    }
    
    

}
