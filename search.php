<?php
// +--------------------------------------------------------------------------+
// |							Kshop							      			
// | 							Module for Xoops	   					    
// |            				www.kaotik.biz
// |            				kaotik1@gmail.com              
// +--------------------------------------------------------------------------+

// This code was borrowed from the search function of NewBB .

// The name of this function is up to you, but it must correspond to the name of this function that you inserted in the xoops_version.php file

// and the parameters  "$queryarray, $andor, $limit, $offset, $userid" to this function are, I believe dictated by the core search function

// NOTE:  $andor defaults to AND, but can be 'AND', 'OR' or 'exact', corresponding to the three options in the Advanced Search interface.  Your search logic should take these three possibilities into account.

function search_kshop($queryarray, $andor, $limit, $offset, $userid){

        // I’m assume this is a global class referring to the database in use. J
        global $xoopsDB;

        // Start creating a sql string that will be used to retrieve the fields in the table
        // that your module is making available to search
        // Remember this is a simple module; in the sense is doesn’t have Userids, expiration data for content,
        // or Categories, or multiple tables to worry about

        $sql = "SELECT p_id, p_name, p_desc, p_desc_long FROM ".$xoopsDB->prefix("kshop_products")." WHERE p_name='%$queryarray[0]%' OR p_desc LIKE '%$queryarray[0]%' OR p_desc_long LIKE '%$queryarray[0]%' ORDER BY p_name DESC";

        // because count() returns 1 even if a supplied variable
        // is not an array, we must check if $querryarray is really an array
/*
        if ( is_array($queryarray) && $count = count($queryarray) ) {
                $sql .= " WHERE ((p_name LIKE '%$queryarray[0]%' OR p_desc LIKE '%$queryarray[0]%' OR p_desc_long LIKE
                $queryarray[0]')";
              
			  
			    for($i=1;$i<$count;$i++){
                        $sql .= " AND ";
                        $sql .= "(p_name LIKE '%$queryarray[$i]%' OR p_desc LIKE '%$queryarray[$i]%' OR p_desc_long LIKE
                                '%$queryarray[$i]%')";
                }
				
                $sql .= ") ";
        } // end if

        $sql .= "ORDER BY p_name DESC";
*/
        // Because of the way the GuestBook index file displays it's entries I needed to know the total entries.
        // Borrowed this code from index.php in the guestbook module directory.
       // $query = $xoopsDB->query("SELECT COUNT(*) FROM ".$xoopsDB->prefix("kshop_products")."");
        //list($numrows) = $xoopsDB->fetchrow($query);

        // I assume this is where the sql query gets excuted???
        $result = $xoopsDB->query($sql,$limit,$offset);
        $ret = array();
        $i = 0;
        // with the search results, build the links to the hits the search query made
        while($myrow = $xoopsDB->fetchArray($result)){

                // you can use any gif; I didn't have a specific one and just used one in the images folder.
                $ret[$i]['image'] = "images/url.gif";

                // since guestbook doesn't have a 'viewentry' function, yet :)
                // I simply use the index.php function and calc the offset,
                // the offset is starting at the end of the entries..
                // if your module has a view entry or veiw article, etc function then simple use that for your link.
                $ret[$i]['link'] = "product_details.php?id=".$myrow['p_id'];

                $ret[$i]['title'] = $myrow['p_name'];

                $ret[$i]['time'] = '';

                // no user ids in guestbook, so I left this blank.
                $ret[$i]['uid'] = '';
                $i++;
        }
        return $ret;
}

?>