<?php
// 'user' object
class User{

    // database connection and table name
    private $conn;
    private $table_name = "users";

    // object properties
    public $id;
    public $firstname;
    public $lastname;
    public $email;
    public $password;

    // constructor
    public function __construct($db){
        $this->conn = $db;
    }


// create new user record
function create(){


    // insert query
$query = "INSERT INTO " . $this->table_name. " (firstname, lastname, email, password) VALUES (?, ?, ?, ?)";



    // prepare the query
    $stmt = $this->conn->prepare($query);

    // sanitize
    $this->firstname=$this->test_input($this->firstname);
    $this->lastname=$this->test_input($this->lastname);
    $this->email=$this->test_input($this->email);
    $this->password=$this->test_input($this->password);

    // hash the password before saving to database
    $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
    // for mysqli its bind_param
        // bind the values
    $stmt->bind_param("ssss", $this->firstname, $this->lastname, $this->email, $password_hash);

    // execute the query, also check if query was successful
    if($stmt->execute()){
        return true;
    }
    else{
    return false;
    print_r("Error preparing: $conn->error");
  }
  $stmt->close();
}

// emailExists() method
// check if given email exist in the database
function emailExists(){

    // query to check if email exists
    $query = "SELECT id, firstname, lastname, password FROM " . $this->table_name . " WHERE email = ? LIMIT 0,1";

    // prepare the query
    $stmt = $this->conn->prepare( $query );

    //sanitize
    $this->email = $this->test_input($this->email);

    // bind given email value
    $stmt->bind_param("s", $this->email);

    // execute the query
    $stmt->execute();

    // bind the result
    $stmt->bind_result($this->id, $this->firstname, $this->lastname, $this->password);

    //store the mysqli_results
    $stmt->store_result();

    // get number of rows
    $num = $stmt->num_rows;

    // if email exists, assign values to object properties for easy access and use for php sessions
    if($num>0){

        // get record details / values

        while ($stmt->fetch()) {
            $this->id = $this->id;
            $this->firstname = $this->firstname;
            $this->lastname = $this->lastname;
            $this->password = $this->password;
          }
        // return true because email exists in the database

        return true;
    }

    // return false if email does not exist in the database
    return false;

    $stmt->close();
}

// update a user record
public function update(){

    // if password needs to be updated
    if(empty($this->password)){
    // if no posted password, do not update the password
    $query = "UPDATE $this->table_name
              SET firstname = ?,
              lastname = ?
              WHERE email = ?" ;

              // prepare the query
              $stmt = $this->conn->prepare($query);

              // sanitize
              $this->firstname=$this->test_input($this->firstname);
              $this->lastname=$this->test_input($this->lastname);
              $this->email=$this->test_input($this->email);

              // bind the values from the form
              $stmt->bind_param("sss", $this->firstname, $this->lastname, $this->email);
            }
    else{

      // if password also need to update
      $query = "UPDATE $this->table_name
                SET firstname = ?,
                lastname = ?,
                password = ?
                WHERE email = ?" ;

                // prepare the query
                $stmt = $this->conn->prepare($query);

                // sanitize
                $this->firstname=$this->test_input($this->firstname);
                $this->lastname=$this->test_input($this->lastname);
                $this->email=$this->test_input($this->email);
                $this->password=$this->test_input($this->password);

                    // hash the password before saving to database
                $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
                echo $password_hash;
                $stmt->bind_param("ssss", $this->firstname, $this->lastname,$password_hash, $this->email);
    }
    // execute the query
    if($stmt->execute()){
            return true;
        }



    return false;

    $stmt->close();
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = mysqli_real_escape_string(Database::$conn, $data);;
  return $data;
}



}
 ?>
