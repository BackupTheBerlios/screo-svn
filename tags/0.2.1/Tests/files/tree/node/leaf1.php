<?php

// Leaf 1

	define( 'LEAF1', 'LEAF' );
	
	$heredoc = <<< HEREDOC

This is a
	
	Heredoc
		string.
        
HEREDOC;

	require_once '../leaf.php';

?>