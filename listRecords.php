<?php
require_once(__DIR__ . '/config/database.settings.php');

if(isset($_GET['id'])) {
    if(is_numeric($_GET['id'])) {
    print readDatabaseRecord($_GET['id']);
    } else {
    print "record ID has to be numeric";
    }                 
} else {
    print readAllDatabaseRecords();
}

    function readDatabaseRecord($recordID)
    {
        try {
            
            $conn = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
            $sql  = "SELECT * FROM transactions where id=:ID";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':ID', $recordID, PDO::PARAM_INT);
            $stmt->execute();
            
            $result = $stmt->fetchAll();
            if(isset($result[0])) {
                $orderFull = $result[0];
            }

            $orderData       = @unserialize($result[0]['orderData']);
            $transactionData = @unserialize($result[0]['transactionResult']);
            if (isset($orderData) && !empty($orderData)) {
                print "<table>";
                print "<tr><td><strong> Order Number " . $orderFull['id'] . "</strong></td></tr>";
                foreach ($orderData as $key => $value) {
                    print "<tr><td>" . ucfirst($key) . ":<td><td>" . $value . "<td></tr>";
                }
                print "</table>";
                
                print "<table>";
                
                if (is_array($transactionData)) {
                    foreach ($transactionData as $key => $value) {
                        if (!is_array($value)) {
                            print "<tr><td>" . ucfirst($key) . ":<td><td>" . $value . "</td></tr>";
                        } else {
                            foreach ($value as $k => $v)
                                if (!is_array($v)) {
                                    print "<tr><td></td><td>" . ucfirst($k) . ":</td><td>" . $v . "</td></tr>";
                                } else {
                                    foreach ($v as $key2 => $value2) {
                                        if (!is_array($value2)) {
                                            print "<tr><td></td><td>" . ucfirst($key2) . ":</td><td>" . $value2 . "</td></tr>";
                                        }
                                    }
                                }
                        }
                    }
                    print "</table><br /><br />";
                } else {
                    // Parse Braintree
                $brainTree = explode(',', $transactionData);
                    if(is_array($brainTree)) { 
                        foreach ($brainTree as $k => $v) {
                                if (!is_array($v)) {
                                    print "<tr><td></td><td>" . ucfirst($k) . ":</td><td>" . $v . "</td></tr>";
                                } else {
                                    foreach ($v as $key2 => $value2) {
                                        if (!is_array($value2)) {
                                            print "<tr><td></td><td>" . ucfirst($key2) . ":</td><td>" . $value2 . "</td></tr>";
                                        }
                                    }
                                }
                        
                    }
                
                
                }
                     print "</table><br /><br />";
            }
            } else {
                // Invalid Order data, then skip past, no need to printout to screen.
            }
        }
        catch (PDOException $e) {
	    echo "An error has occured. Please check the Database settings <br/>";
            echo $sql . "<br>" . $e->getMessage();
        }
        
        $conn = null;
    }
    
    function readAllDatabaseRecords()
    {
        // get latest record ID
        $conn = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        $sql  = "SELECT id FROM transactions ORDER BY id DESC LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();

        if(isset($result)&&!empty($result)) {
        $last   = intVal($result[0]['id']);
            if($last!=1) {
                for ($x = 1; $x <= $last; $x++) {
                    readDatabaseRecord($x);
                } 
            } else {
                readDatabaseRecord('1');
            }
        } else {
            print "No records currently in the Database";
        }
        
    }
    

?>
