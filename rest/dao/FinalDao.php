<?php
require_once "BaseDao.php";

class FinalDao extends BaseDao {

    public function __construct(){
        parent::__construct();
    }

    /** TODO
    * Implement DAO method used login user
    */
    public function login($email, $password) {
        $query = "SELECT * FROM users WHERE email = :email AND password = :password";
        $params = array(':email' => $email, ':password' => $password);
    
        // Execute the query and fetch the user data
        $user = $this->executeQuery($query, $params)->fetch();
    
        return $user; // Return the user data if found, or null if not found
    }
    
    

    /** TODO
    * Implement DAO method used to add new investor to investor table and cap-table
    */
    public function investor($firstName, $lastName, $email, $company, $shareClassId, $shareClassCategoryId, $dilutedShares) {
        // Check if email is unique
        $query = "SELECT COUNT(*) AS count FROM investors WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $result = $stmt->fetch();

        if ($result['count'] > 0) {
            return "Email address is already registered.";
        }

        // Check if the sum of diluted shares exceeds the authorized assets for the share class
        $query = "SELECT authorized_assets FROM share_classes WHERE id = :share_class_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':share_class_id', $shareClassId);
        $stmt->execute();
        $result = $stmt->fetch();

        if ($result && $result['authorized_assets'] < $dilutedShares) {
            return "The sum of diluted shares exceeds the authorized assets for the share class.";
        }

        // Add investor to the investors table
        $query = "INSERT INTO investors (name, email, company) VALUES (:name, :email, :company)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $firstName . ' ' . $lastName);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':company', $company);
        $stmt->execute();

        // Get the inserted investor ID
        $investorId = $this->conn->lastInsertId();

        // Add investor to the cap_table
        $query = "INSERT INTO cap_table (share_class_id, share_class_category_id, investor_id, diluted_shares)
                  VALUES (:share_class_id, :share_class_category_id, :investor_id, :diluted_shares)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':share_class_id', $shareClassId);
        $stmt->bindParam(':share_class_category_id', $shareClassCategoryId);
        $stmt->bindParam(':investor_id', $investorId);
        $stmt->bindParam(':diluted_shares', $dilutedShares);
        $stmt->execute();

        return "Investor has been created successfully.";
    }

    /** TODO
    * Implement DAO method to return list of all share classes from share_classes table
    */
    public function share_classes() {
        $query = "SELECT * FROM share_classes";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $shareClasses = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $shareClasses;
    }   

    /** TODO
    * Implement DAO method to return list of all share class categories from share_class_categories table
    */
     public function share_class_categories() {
        $query = "SELECT * FROM share_class_categories";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $shareClassCategories = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $shareClassCategories;
    }
}
?>
