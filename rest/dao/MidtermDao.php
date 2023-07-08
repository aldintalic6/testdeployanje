<?php
require_once "BaseDao.php";

class MidtermDao extends BaseDao {

    public function __construct(){
        parent::__construct();
    }

    /** TODO
    * Implement DAO method used add new investor to investor table and cap-table
    */
    public function investor($firstName, $lastName, $email, $company, $shareClassId, $dilutedShares) {
        // Check if the email already exists in the investors table
        $stmt = $this->conn->prepare("SELECT id FROM investors WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $existingInvestor = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($existingInvestor) {
            return array("message" => "Investor with this email already exists");
        }
    
        // Check if the sum of diluted shares exceeds the authorized assets for the given share class
        $stmt = $this->conn->prepare("SELECT SUM(diluted_shares) AS total_diluted_shares
            FROM cap_table
            WHERE share_class_id = :share_class_id");
        $stmt->execute(['share_class_id' => $shareClassId]);
        $totalDilutedShares = $stmt->fetch(PDO::FETCH_ASSOC)['total_diluted_shares'];
    
        $stmt = $this->conn->prepare("SELECT authorized_assets FROM share_classes WHERE id = :share_class_id");
        $stmt->execute(['share_class_id' => $shareClassId]);
        $authorizedAssets = $stmt->fetch(PDO::FETCH_ASSOC)['authorized_assets'];
    
        if (($totalDilutedShares + $dilutedShares) > $authorizedAssets) {
            return array("message" => "Sum of diluted shares exceeds authorized assets for the given share class");
        }
    
        // Add the new investor to the investors table
        $stmt = $this->conn->prepare("INSERT INTO investors (first_name, last_name, email, company)
            VALUES (:first_name, :last_name, :email, :company)");
        $stmt->execute([
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
            'company' => $company
        ]);
    
        $investorId = $this->conn->lastInsertId();
    
        // Add the investor and diluted shares to the cap_table
        $stmt = $this->conn->prepare("INSERT INTO cap_table (share_class_id, investor_id, diluted_shares)
            VALUES (:share_class_id, :investor_id, :diluted_shares)");
        $stmt->execute([
            'share_class_id' => $shareClassId,
            'investor_id' => $investorId,
            'diluted_shares' => $dilutedShares
        ]);
    
        return array("message" => "Investor has been created successfully");
    }
    

    /** TODO
    * Implement DAO method to validate email format and check if email exists
    */
    public function investor_email($email) {
        $stmt = $this->conn->prepare("SELECT CASE
            WHEN i.id IS NOT NULL THEN CONCAT('Investor ', i.first_name, ' ', i.last_name, ' uses this email address')
            ELSE 'Investor with this email does not exist in the database'
        END AS output
        FROM investors i
        WHERE i.email = :email;");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    

    /** TODO
    * Implement DAO method to return list of investors according to instruction in MidtermRoutes.php
    */
    public function investors($share_class_id){
        $stmt = $this->conn->prepare("SELECT sc.description AS share_class_description,
        sc.equity_main_currency,
        sc.price,
        sc.authorized_assets,
        i.first_name,
        i.last_name,
        i.email,
        i.company,
        COALESCE(SUM(ct.diluted_shares), 0) AS total_diluted_assets
        FROM cap_table ct
        JOIN share_classes sc ON ct.share_class_id = sc.id
        JOIN investors i ON ct.investor_id = i.id
        WHERE ct.share_class_id = :share_class_id
        GROUP BY sc.description,
          sc.equity_main_currency,
          sc.price,
          sc.authorized_assets,
          i.first_name,
          i.last_name,
          i.email,
          i.company");
        $stmt->execute(['share_class_id' => $share_class_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
?>
