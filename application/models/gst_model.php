<?php

defined('BASEPATH') or exit('No direct script access allowed');


/*
CREATE TABLE gst_auth_tokens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    gstin VARCHAR(15) NOT NULL UNIQUE,
    eway_auth_token TEXT NOT NULL,
    eway_token_expiry DATETIME NOT NULL,
    einv_auth_token TEXT NOT NULL,
    einv_token_expiry DATETIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
CREATE TABLE gst_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    gstin VARCHAR(15) NOT NULL,
    invoice_number VARCHAR(20) NOT NULL,
    invoice_date DATE NOT NULL,
    invoice_value DECIMAL(10,2) NOT NULL,
    buyer_gstin VARCHAR(15) NOT NULL,
    seller_gstin VARCHAR(15) NOT NULL,
    eway_bill_no VARCHAR(20) NULL,
    eway_bill_date DATE NULL,
    eway_bill_status ENUM('Active', 'Cancelled', 'Expired') DEFAULT 'Active',
    irn VARCHAR(50) NULL,
    irn_status ENUM('Valid', 'Cancelled', 'Rejected') DEFAULT 'Valid',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (gstin) REFERENCES gst_auth_tokens(gstin) ON DELETE CASCADE
);
*/

class Gst_model extends App_Model
{
    protected $table = 'gst_auth_tokens';
    protected $primaryKey = 'id';
    protected $allowedFields = ['gstin', 'eway_auth_token', 'eway_token_expiry', 'einv_auth_token', 'einv_token_expiry'];

    /**
     * Get a valid authentication token for e-Way Bill or e-Invoice
     * @param string $gstin - GSTIN Number
     * @param string $type - 'eway' for e-Way Bill, 'einv' for e-Invoice
     * @return object|null - Token details if valid, otherwise NULL
     */
    public function getValidToken($gstin, $type)
    {
        return $this->db->where('gstin', $gstin)
                        ->where($type . '_token_expiry >', date('Y-m-d H:i:s'))
                        ->get($this->table)
                        ->row();
    }

    /**
     * Update or Insert a new authentication token for a GSTIN
     * @param string $gstin - GSTIN Number
     * @param string $token - New Token
     * @param string $expiry - Token Expiry DateTime
     * @param string $type - 'eway' for e-Way Bill, 'einv' for e-Invoice
     * @return bool - True on success, False on failure
     */
    public function updateToken($gstin, $token, $expiry, $type)
    {
        $existing = $this->db->where('gstin', $gstin)->get($this->table)->row();

        if ($existing) {
            return $this->db->where('gstin', $gstin)
                            ->update($this->table, [
                                $type . '_auth_token' => $token,
                                $type . '_token_expiry' => $expiry
                            ]);
        } else {
            return $this->db->insert($this->table, [
                'gstin' => $gstin,
                $type . '_auth_token' => $token,
                $type . '_token_expiry' => $expiry
            ]);
        }
    }

    /**
     * Get all stored tokens (for debugging or management)
     * @return array - List of all tokens
     */
    public function getAllTokens()
    {
        return $this->db->get($this->table)->result();
    }

    /**
     * Remove expired tokens from the database (for Cron Job)
     * @return bool - True on success, False on failure
     */
    public function deleteExpiredTokens()
    {
        return $this->db->where('eway_token_expiry <', date('Y-m-d H:i:s'))
                        ->or_where('einv_token_expiry <', date('Y-m-d H:i:s'))
                        ->delete($this->table);
    }
}
