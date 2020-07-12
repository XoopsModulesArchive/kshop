<?php

// Recursive function to generate a parent/child tree
// Without the need for a Root parent
// Written by: Brian Parnes
// 13 March 2006

//$connect = mysql_connect(HOST_NAME, USERNAME, PASSWORD);
//mysql_select_db(DATABASE);
$nav_query = mysql_query("SELECT * FROM `categories` ORDER BY `category_id`");
$tree = "";					// Clear the directory tree
$depth = 1;					// Child level depth.
$top_level_on = 1;			// What top-level category are we on?
$exclude = array();			// Define the exclusion array
array_push($exclude, 0);	// Put a starting value in it

while ( $nav_row = mysql_fetch_array($nav_query) )
{
	$goOn = 1;			// Resets variable to allow us to continue building out the tree.
	for($x = 0; $x < count($exclude); $x++ )		// Check to see if the new item has been used
	{
		if ( $exclude[$x] == $nav_row['category_id'] )
		{
			$goOn = 0;
			break;				// Stop looking b/c we already found that it's in the exclusion list and we can't continue to process this node
		}
	}
	if ( $goOn == 1 )
	{
		$tree .= $nav_row['title'] . "<br>";				// Process the main tree node
		array_push($exclude, $nav_row['category_id']);		// Add to the exclusion list
		if ( $nav_row['category_id'] < 6 )
		{ $top_level_on = $nav_row['category_id']; }
		
		$tree .= build_child($nav_row['category_id']);		// Start the recursive function of building the child tree
	}
}

function build_child($oldID)			// Recursive function to get all of the children...unlimited depth
{
	global $exclude, $depth;			// Refer to the global array defined at the top of this script
	$child_query = mysql_query("SELECT * FROM `categories` WHERE parent_id=" . $oldID);
	while ( $child = mysql_fetch_array($child_query) )
	{
		if ( $child['category_id'] != $child['parent_id'] )
		{
			for ( $c=0;$c<$depth;$c++ )			// Indent over so that there is distinction between levels
			{ $tempTree .= "&nbsp;"; }
			$tempTree .= "- " . $child['title'] . "<br>";
			$depth++;		// Incriment depth b/c we're building this child's child tree  (complicated yet???)
			$tempTree .= build_child($child['category_id']);		// Add to the temporary local tree
			$depth--;		// Decrement depth b/c we're done building the child's child tree.
			array_push($exclude, $child['category_id']);			// Add the item to the exclusion list
		}
	}
	
	return $tempTree;		// Return the entire child tree
}

echo $tree;

?>