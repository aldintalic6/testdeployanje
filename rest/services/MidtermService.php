<?php
require_once __DIR__."/../dao/MidtermDao.php";

class MidtermService {
    protected $dao;

    public function __construct(){
        $this->dao = new MidtermDao();
    }

    /** TODO
    * Implement service method to add new investor to investor table and cap-table
    */
    public function investor($firstName, $lastName, $email, $company, $shareClassId, $dilutedShares) {
        return $this->dao->investor($firstName, $lastName, $email, $company, $shareClassId, $dilutedShares);
    }
    

    /** TODO
    * Implement service method to validate email format and check if email exists
    */
    public function investor_email($email) {
        // Validate the email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return array("message" => "Invalid email format");
        }
    
        // Retrieve the investor by email
        $investor = $this->dao->investor_email($email);
    
        if ($investor) {
            // Return the response with the investor's information
            return array("message" => $investor['output']);
        } else {
            return array("message" => "Investor with this email does not exist in the database");
        }
    }
    

    /** TODO
    * Implement service method to return list of investors according to instruction in MidtermRoutes.php
    */
    public function investors($share_class_id){
        return $this->dao->investors($share_class_id);
    }
}
?>
