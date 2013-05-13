<?php
// Include the test framework
include('enhance/EnhanceTestFramework.php');
// Find the tests - '.' is the current folder
\Enhance\Core::discoverTests('tests');
// Run the tests
\Enhance\Core::runTests();
?>
