<?php
require __DIR__ . '/HQlibrary.php'; // Library to handle inputs and controller functionality.
$HQ        = new HQLibrary();

if(isset($_GET['id'])) {
    if(is_numeric($_GET['id'])) {
    print $HQ->readDatabaseRecord($_GET['id']);
    } else {
    print "record ID has to be numeric";
    }                 
} else {
    print $HQ->readAllDatabaseRecords();
}
unset($HQ); // Garbage Collection
?>