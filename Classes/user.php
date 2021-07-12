<?php
class User
{
    //Connection
    private $conn;

    // Table
    private $user_table = "tbl_students";
    private $project_table = "tbl_project";

    // Columns
    public $name;
    public $email;
    public $password;
    public $age;
    public $designation;
    public $user_id;
    public $project_name;
    public $description;
    public $status;
    public $id;
    

    // Db connection
    public function __construct($db)
    {
        $this->conn = $db;

    }

    public function createEmployee()
    {

        $user_query = "INSERT INTO " . $this->user_table . " SET name = :name, email = :email, password = :password, age = :age, designation = :designation";

        $user_obj = $this->conn->prepare($user_query);

        // sanitize
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = htmlspecialchars(strip_tags($this->password));
        $this->age = htmlspecialchars(strip_tags($this->age));
        $this->designation = htmlspecialchars(strip_tags($this->designation));

        // bind data
        $user_obj->bindParam(":name", $this->name);
        $user_obj->bindParam(":email", $this->email);
        $user_obj->bindParam(":password", $this->password);
        $user_obj->bindParam(":age", $this->age);
        $user_obj->bindParam(":designation", $this->designation);

        if ($user_obj->execute()) {
            return true;
        }
        return false;

    }

    public function checkEmail()
    {
        $sql = "SELECT * FROM " . $this->user_table . " WHERE email = :email";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(":email", $this->email);

        $stmt->execute();
        
        $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);
            
      return $dataRow;
    }


    public function user_login()
    {
        $sql = "SELECT * FROM " . $this->user_table . " WHERE email = :email";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(":email", $this->email);

        $stmt->execute();
        
        $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);
            
      return $dataRow;
    }

    public function createProject()
    {

        $project_query = "INSERT INTO " . $this->project_table . " SET user_id = :user_id, name = :name, description = :description, status = :status";

        $project_obj = $this->conn->prepare($project_query);

        // sanitize
        $this->project_name = htmlspecialchars(strip_tags($this->project_name));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->status = htmlspecialchars(strip_tags($this->status));
        
        // bind data
        $project_obj->bindParam(":user_id", $this->user_id);
        $project_obj->bindParam(":name", $this->project_name);
        $project_obj->bindParam(":description", $this->description);
        $project_obj->bindParam(":status", $this->status);
       

        if ($project_obj->execute()) {
            return true;
        }
        return false;

    }
    public function get_all_users()
    {
        $project_query_all = "SELECT * FROM " . $this->project_table . " ORDER BY id DESC";
        
        $stmt = $this->conn->prepare($project_query_all);

        $stmt->execute();

        return $stmt->get_result();
    }
    public function get_single_users()
    {
        $project_query_all = "SELECT * FROM " . $this->project_table . " WHERE user_id = ? ORDER BY id DESC";
        
        $stmt = $this->conn->prepare($project_query_all);

        $stmt->bind_param("i", $this->user_id);

        $stmt->execute();

        return $stmt->get_result();
    }
    public function updat_project_tbl(){
        $sqlQuery = "UPDATE ". $this->project_table ." SET name = :name, description = :description, status = :status WHERE user_id = :user_id AND name = :name";
    
        $stmt = $this->conn->prepare($sqlQuery);
    
        $this->project_name=htmlspecialchars(strip_tags($this->project_name));
        $this->description=htmlspecialchars(strip_tags($this->description));
        $this->status=htmlspecialchars(strip_tags($this->status));
        
        // bind data
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":name", $this->project_name);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":status", $this->status);
       
    
        if($stmt->execute()){
           return true;
        }
        return false;
    }

    function deleteProject(){

        $sqlQuery = "DELETE FROM " . $this->project_table . " WHERE user_id = :user_id AND name = :name";

        $stmt = $this->conn->prepare($sqlQuery);
    
        $this->project_name=htmlspecialchars(strip_tags($this->project_name));
    
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":name", $this->project_name);
    
        if($stmt->execute()){
            return true;
        }
        return false;
    }
}