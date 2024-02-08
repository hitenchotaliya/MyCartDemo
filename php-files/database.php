<?php

class Database
{

    private $host = "localhost";
    private $username = "root";
    private $password = "root";
    private $dbname = "mycartdb";

    private $result = array(); // Any results from a query will be stored here
    private $mysqli = ""; // This will be our mysqli object
    private $myQuery = "";  // used for debugging process with SQL return

    private $conn = false;

    public function __construct()
    {
        if (!$this->conn) {
            $this->mysqli = new mysqli($this->host, $this->username, $this->password, $this->dbname);

            // Check connection
            if ($this->mysqli->connect_error > 0) {
                array_push($this->result, $this->mysqli->connect_error);
                return false; // Problem selecting database return FALSE
            }
            // echo "Connection successfull with $this->dbname";
        } else {
            //Db is connected
        }
    }


    public function sql($sql)
    {
        $this->myQuery = $sql; //Pass back to sql
        $query = $this->mysqli->query($sql);

        if ($query) {
            $this->result = $query->fetch_all(MYSQLI_ASSOC);
            return true;
        } else {
            array_push($this->result, $this->mysqli->error);
            return false;
        }
    }

    public function  getResult()
    {
        $val = $this->result;
        $this->result = array();
        return $val;
    }

    public function select($table, $rows = "*", $join = null, $where = null, $order = null, $limit = null)
    {
        //Before selection we check that is there any table available in db or not
        if ($this->TableExist($table)) {

            $sql = "SELECT $rows FROM $table";

            if ($join != null) {
                $sql .= ' JOIN ' . $join;
            }

            if ($where != null) {
                $sql .= ' WHERE ' . $where;
            }

            if ($order != null) {
                $sql .= ' ORDER BY ' . $order;
            }

            if ($limit != null) {

                if (isset($_GET['page'])) {
                    $page = $_GET['page'];
                } else {
                    $page = 1;
                }
                $start = ($page - 1) * $limit;
                $sql .= ' LIMIT ' . $start . ',' . $limit;
                //  echo "Page: $page, Start: $start, Limit: $limit";

            }

            //echo $sql;

            $this->myQuery = $sql; //Pass back to sql

            //Run the query
            $query = $this->mysqli->query($sql);

            if ($query) {
                $this->result = $query->fetch_all(MYSQLI_ASSOC);
                return true; //Query was successfull
            } else {
                array_push($this->result, $this->mysqli->error);
                return false; // No rows where returned
            }
        } else {
            return false;
            array_push($this->result, $this->mysqli->error);
            //Table does not exit
        }
    }

    // Private function to check if table exists for use with queries
    public function TableExist($table)
    {
        $TableInDB = $this->mysqli->query("SHOW TABLES FROM $this->dbname LIKE '$table' ");
        if ($TableInDB) {
            if ($TableInDB->num_rows == 1) {
                //array_push($this->result, "Table $table exists.");
                return true;
            } else {
                array_push($this->result, "Table $table does not exist.");
                return false;
            }
        }
    }

    //Function that clear userinput remove unccessory item or keywords
    public function escapeString($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlentities($data);
        return $this->mysqli->real_escape_string($data);
    }


    //Insert data 
    public function insert($table, $Values = array())
    {
        if ($this->TableExist($table)) {

            //implode() function returns a string from the elements of an array.
            $table_columns = implode(',', array_keys($Values));
            $table_value = implode("','", $Values);

            $sql = "INSERT INTO $table ($table_columns) VALUES ('$table_value')";

            $this->myQuery = $sql;

            if ($this->mysqli->query($sql)) {
                //mysql_insert_id — Get the ID generated in the last query
                array_push($this->result, $this->mysqli->insert_id);
                return true; //Data has been inserted
            } else {
                array_push($this->result, $this->mysqli->error);
                return false; //Data not inserted
            }
        } else {
            return false; //Table does not exist
        }
    }

    //Update

    public function update($table, $Values = array(), $where = "", $in = null)
    {
        if ($this->TableExist($table)) {
            $args = array();

            foreach ($Values as $keys => $value) {
                $args[] = "$keys='$value'";
            }

            $sql = "UPDATE $table SET " . implode(', ', $args);

            if ($where != "") {
                $sql .= " WHERE $where";
            }
            if ($in != "") {
                $sql .= " IN ($in)";
            }
            //echo $sql;
            if ($this->mysqli->query($sql)) {
                // Return affected rows
                array_push($this->result, $this->mysqli->affected_rows);
                return true; // Update successfully
            } else {
                array_push($this->result, $this->mysqli->error);
                return false;
            }
        } else {
            return false; // Table does not exist
        }
    }

    //Delete
    public function delete($table, $where = null, $in = null)
    {
        if ($this->TableExist($table)) {
            $sql = "DELETE FROM $table";

            if ($where != null) {
                $sql .= " WHERE $where";
            }
            if ($in != null) {
                $sql .= " IN ($in)";
            }

            // echo $sql;
            if ($this->mysqli->query($sql)) {
                array_push($this->result, $this->mysqli->query($sql));
                return true;
            } else {
                array_push($this->result, $this->mysqli->error);
                return false;
            }
        } else {
            return false;
        }
    }
    //Pagination db
    public function Pagination($table, $join = null, $where = null, $limit = null)
    {
        if ($this->TableExist($table)) {
            if ($limit != null) {
                // Count total records
                $sql = "SELECT COUNT(*) FROM $table";

                if ($join != null) {
                    $sql .= " JOIN $join";
                }
                if ($where != null) {
                    $sql .= " WHERE $where";
                }

                $query = $this->mysqli->query($sql);

                // Check if the query was successful
                if ($query) {
                    // Get total records count
                    $totalRecords = $query->fetch_array();

                    // Check if fetch was successful
                    if ($totalRecords !== false) {
                        $totalRecords = $totalRecords[0];

                        // Calculate total pages based on the limit
                        $TotalPage = ceil($totalRecords / $limit);

                        // Get the current page from the URL
                        $page = isset($_GET['page']) ? $_GET['page'] : 1;

                        // Preserve existing query parameters
                        $url = basename($_SERVER['PHP_SELF']);
                        $query_params = $_SERVER['QUERY_STRING'];

                        // Remove 'page' parameter from existing query parameters
                        $query_params = preg_replace('/&?page=[^&]*/', '', $query_params);

                        if (!empty($query_params)) {
                            $url .= '?' . $query_params . '&';
                        } else {
                            $url .= '?';
                        }

                        $TResult = "<ul class='pagination'>";

                        // Display Previous button if not on the first page
                        if ($page > 1) {
                            $TResult .= "<li><a href='{$url}page=" . ($page - 1) . "'>Prev</a></li>";
                        }

                        // Loop for displaying page numbers
                        for ($i = 1; $i <= $TotalPage; $i++) {
                            if ($i == $page) {
                                $cls = "class='active' style='color:red'";
                            } else {
                                $cls = "";
                            }
                            // Page number links
                            $TResult .= "<li><a $cls href='{$url}page=$i'>$i</a></li>";
                        }

                        // Display Next button if not on the last page
                        if ($page < $TotalPage) {
                            $TResult .= "<li><a href='{$url}page=" . ($page + 1) . "'>Next</a></li>";
                        }
                        $TResult .= "</ul>";

                        return $TResult;
                    } else {
                        die("Error fetching total records count: " . $this->mysqli->error);
                    }
                } else {
                    die("Error executing query at line " . __LINE__ . ": (" . $this->mysqli->errno . ") " . $this->mysqli->error);
                }
            } else {
                return false; // Pagination is not applicable without a limit
            }
        } else {
            array_push($this->result, $this->mysqli->error);
        }
    }

    public function uploadFiles($filesArray, $destinationFolder)
    {
        $uploadedFiles = [];

        foreach ($filesArray['name'] as $key => $val) {
            // Check file format and size
            $allowedFormats = ['image/jpeg', 'image/png'];
            $maxFileSize = 1048576; // 1MB in bytes

            if (in_array($filesArray['type'][$key], $allowedFormats) && $filesArray['size'][$key] <= $maxFileSize) {
                $rand = rand(11111, 99999);
                $file = $rand . '_' . $val;
                $destinationPath = $destinationFolder . '/' . $file;

                if (move_uploaded_file($filesArray['tmp_name'][$key], $destinationPath)) {
                    $uploadedFiles[] = $destinationPath;
                } else {
                    // Handle error if file upload fails
                    array_push($this->result, "Failed to move file: " . $val);
                }
            } else {
                // Handle error for invalid file format or size
                array_push($this->result, "Invalid file: " . $val . ". Please upload JPEG or PNG files with size not more than 1MB.");
            }
        }

        return $uploadedFiles;
    }


    // public function uploadFiles($filesArray, $destinationFolder)
    // {
    //     $uploadedFiles = [];

    //     foreach ($filesArray['name'] as $key => $val) {
    //         $rand = rand(11111, 99999);
    //         $file = $rand . '_' . $val;
    //         $destinationPath = $destinationFolder . '/' . $file;

    //         if (move_uploaded_file($filesArray['tmp_name'][$key], $destinationPath)) {
    //             $uploadedFiles[] = $destinationPath;
    //         } else {
    //             // Handle error if file upload fails
    //             array_push($this->result, $this->mysqli->error);
    //             //  array_push($this->result, "Failed to upload file: " . $val);
    //         }
    //     }

    //     return $uploadedFiles;
    // }

    //Destruct
    public function __destruct()
    {
        if ($this->conn) {
            if ($this->mysqli->close()) {
                $this->conn = false;
                return true;
            } else {
                return false;
            }
        }
    }
}
// $db = new Database();
// $db->select('admin', '*', null, null, null, null);
// $result = $db->getResult();
// echo "<pre>";
// print_r($result);
// print_r($result[0]['admin_password']);
