<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Mailflow_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function searchLeadsEmails($leadSource=[], $assignedStaff=[], $leadCountry=[],$leadGroups=[])
    {

        $this->db->from('tblleads');

        if (!empty($leadSource)) {
            $this->db->where_in('source', $leadSource);
        }

        if (!empty($assignedStaff)) {
            $this->db->where_in('assigned', $assignedStaff);
        }

        if (!empty($leadCountry)) {
            $this->db->where_in('country', $leadCountry);
        }

        if (!empty($leadGroups)) {
            $this->db->where_in('status', $leadGroups);
        }

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $emails = $query->result_array();
            $emails = array_column($emails, 'email');
            $emails = array_filter($emails, 'strlen');
            $emails = array_unique($emails);

            return array_values($emails);
        }

        return array();
    }

    public function searchCustomersEmails($customerStatus = 'active', $customerGroups=[], $customerCountries=[])
    {

        $status = '1';

        if ($customerStatus == 'inactive') {
            $status = '0';
        }

        $searchQuery = $this->db->select('ct.email')
            ->from('tblclients c')
            ->join('tblcontacts ct', 'c.userid = ct.userid');

        if (!empty($customerStatus)) {
            $this->db->where('c.active', $status);
        }

        if (!empty($customerGroups)) {
            $searchQuery->join('tblcustomer_groups cg', 'c.userid = cg.customer_id');
            $this->db->where_in('cg.groupid', $customerGroups);
        }

        if (!empty($customerCountries)) {
            $this->db->where_in('c.country', $customerCountries);
        }

        $query = $this->db->get();

        $emails = array_column($query->result_array(), 'email');
        $emails = array_filter($emails, 'strlen');

        return array_unique($emails);
    }
    
    public function add($data)
    {
        $this->db->insert(db_prefix() . 'mailflow_newsletter_history', $data);
        $insert_id = $this->db->insert_id();

        if ($insert_id) {
            return $insert_id;
        }

        return false;
    }

    public function get($id)
    {
        $this->db->where('id', $id);
        return $this->db->get(db_prefix() . 'mailflow_newsletter_history')->row();
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'mailflow_newsletter_history');

        if ($this->db->affected_rows() > 0) {
            return true;
        }

        return false;
    }

    public function addUnsubscribedEmail($data)
    {
        $this->db->insert(db_prefix() . 'mailflow_unsubscribed_emails', $data);
        $insert_id = $this->db->insert_id();

        if ($insert_id) {
            return $insert_id;
        }

        return false;
    }

    public function getUnsubscribedEmails()
    {
        $emailList = $this->db->get(db_prefix() . 'mailflow_unsubscribed_emails')->result_array();

        $emails = array_column($emailList, 'email');
        $emails = array_filter($emails, 'strlen');

        return array_unique($emails);
    }

    public function getUnsubscribedEmail($id)
    {
        $this->db->where('email', $id);
        return $this->db->get(db_prefix() . 'mailflow_unsubscribed_emails')->row();
    }

    public function deleteUnsubscribedEmail($id)
    {

        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'mailflow_unsubscribed_emails');

        if ($this->db->affected_rows() > 0) {
            return true;
        }

        return false;
    }

    public function addTemplate($data)
    {
        $this->db->insert(db_prefix() . 'mailflow_email_templates', $data);
        $insert_id = $this->db->insert_id();

        if ($insert_id) {
            return $insert_id;
        }

        return false;
    }

    public function getTemplate($id)
    {
        $this->db->where('id', $id);
        return $this->db->get(db_prefix() . 'mailflow_email_templates')->row();
    }

    public function getTemplates()
    {
        return $this->db->get(db_prefix() . 'mailflow_email_templates')->result_array();
    }

    public function updateTemplate($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'mailflow_email_templates', $data);

        return $this->db->affected_rows() > 0;
    }

    public function deleteTemplate($id)
    {

        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'mailflow_email_templates');

        if ($this->db->affected_rows() > 0) {
            return true;
        }

        return false;
    }
}
