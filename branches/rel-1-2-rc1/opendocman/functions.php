<?php
include ('config.php');
if( !defined('function') )
{
  	define('function', 'true', false);
	// BEGIN FUNCTIONS
	// function to format mySQL DATETIME values
	function fix_date($val)
	{
		//split it up into components
		if( $val != 0 )
		{
			$arr = explode(' ', $val);
			$timearr = explode(':', $arr[1]);
			$datearr = explode('-', $arr[0]);
			// create a timestamp with mktime(), format it with date()
			return date('d M Y (H:i)', mktime($timearr[0], $timearr[1], $timearr[2], $datearr[1], $datearr[2], $datearr[0]));
		}
		else
		{	return 0;	}
	}
	
	// Return a copy of $string where all the spaces are converted into underscores
	function space_to_underscore($string)
	{
	    $string_len = strlen($string);
	    $index = 0;
	    while( $index< $string_len )
	        {
	            if($string[$index] == ' ')
	                $string[$index]= '_';
	                $index++;
	        }
	    return $string;
	}
	// Draw the status bar for each page
	function draw_status_bar($message, $lastmessage='')
	{
	    if(!isset($_REQUEST['state']))
	    	$_REQUEST['state']=1;
		echo "\n".'<!------------------begin_draw_status_bar------------------->'."\n";
		if (!isset ($message))
		{
			$message='Select';
        }
		echo '<link rel="stylesheet" type="text/css" href="linkcontrol.css">'."\n";
		echo '<center>'."\n";
		echo '<table width="100%" border="0" cellspacing="0" cellpadding="5">'."\n";
		echo '<tr>'."\n";
		echo '<td bgcolor="#0000A0" align="left" valign="middle" width="110">'."\n";
		echo '<b><font size="-2" face="Arial" color="White">'."\n";
		echo $message;
		echo '</font></b></td>'."\n";
		echo '<td bgcolor="#0000A0" align="left" valign="middle" width="10">'."\n";
		echo '<a class="statusbar" href="out.php" style="text-decoration:none">Home</a>'."\n</td>";
	    	echo '<td bgcolor="#0000A0" align="left" valign="middle" width="10">'."\n";
		echo '<a class="statusbar" href="profile.php" style="text-decoration:none">Preferences</a>'."\n</td>";
	    	echo '<td bgcolor="#0000A0" align="left" valign="middle" width="10">'."\n";
		echo '<a class="statusbar" href="help.html" onClick="return popup(this, \'Help\')" style="text-decoration:none">Help</a>'."\n</td>";
?>	    <TD bgcolor="#0000A0" align="middle" valign="middle" width="0"><font size="3" face="Arial" color="White">|</FONT></TD>
		<TD bgcolor="#0000A0" align="left" valign="middle">
<?php	$crumb = new crumb();
		$crumb->addCrumb($_REQUEST['state'], $message, $_SERVER['REQUEST_URI']);	
		$crumb->printTrail($_REQUEST['state']);
		echo '<td bgcolor="#0000A0" align="right" valign="middle">'."\n";
	    echo '<b><font size="-2" face="Arial" color="White">';
		echo 'Last Message: '.$lastmessage;
	    echo '</td>';
	    
?>	    </font></b>
		</TD>
	    </tr>
	    </table>
	    </center>
	    
	    <!------------------end_draw_status_bar------------------->
	    <?php
	}
	
	function int_array2D_sort($int_array, $sort_order)
	{
		$start_time = time();
		$arraysize = sizeof($int_array);
		$largest_num = 0;
		for($i = 0; $i<$arraysize; $i++)
		{
			if($int_array[$i][1]> $largest_num)
			{	$largest_num = $int_array[$i][1];	}
		}
		$str_largest_num = ''.$largest_num;
		$prefix_zeros = '';
		for($i = 0; $i < strlen($str_largest_num)-1; $i++)
		{	$prefix_zeros = $prefix_zeros.'0';	}
		$smallest_allow_num = (int)('1'.$prefix_zeros);
		$smallest_allow_num_digits = strlen($prefix_zeros)+1;
		for($i = 0; $i<$arraysize; $i++)
		{
			if($int_array[$i][1]<$smallest_allow_num)
			{
				$current_num_digits = strlen((string)($int_array[$i][1]));
				$num_of_zeros = $smallest_allow_num_digits - $current_num_digits;
				for($j = 0; $j < $num_of_zeros; $j++)
				{	$int_array[$i][1] = '0'.$int_array[$i][1];	}
			}
		}
		$sorted_array = str_array2D_sort($int_array, $sort_order);
		echo '<br> <b> int_array2D_sort Time: ' . (time() - $start_time) . ' </b><br>';
		return $sorted_array;
	}
	function str_array2D_sort($str_array, $sort_order)
	{
		$start_time = time();
		switch($sort_order)
		{
			case 'asc':
				$str_array_len = sizeof($str_array);
				$sorted_array = array();
				$current_index = 0;
				$swap_array = array();
				for($i = 0; $i<$str_array_len; $i++)
				{
					$current_index = $i;
					for($j = $i - 1; $j>=0; $j--)
					{
						$result = strcasecmp($str_array[$j][1], $str_array[$current_index][1]);
						if($result > 0)
						{
							$swap_array=$str_array[$j];
							$str_array[$j] = $str_array[$current_index];
							$str_array[$current_index] = $swap_array;
							$current_index = $j;
						}
					}
				}
				
				break;
			case 'desc':
				$str_array_len = sizeof($str_array);
				$sorted_array = array();
				$current_index = 0;
				$swap_array = array();
				for($i = 0; $i<$str_array_len; $i++)
				{
					$current_index = $i;
					for($j = $i - 1; $j>=0; $j--)
					{
						$result = strcasecmp($str_array[$j][1], $str_array[$current_index][1]);
						if($result < 0)
						{
							$swap_array=$str_array[$j];
							$str_array[$j] = $str_array[$current_index];
							$str_array[$current_index] = $swap_array;
							$current_index = $j;
						}
					}
				}
				break;		
		}
		echo '<br> <b> str_array2D_sort Time: ' . (time() - $start_time) . ' </b><br>';
		return $str_array;		
	}
	function obj_array_sort_interface($obj_array, $sort_order, $sort_by)
	{
		if(sizeof($obj_array)<=1 and !isset($obj_array) )
			return $obj_array;
		switch($sort_by)
		{
			case 'file_name':
				$obj_array_len = sizeof($obj_array);
				$str_array = array();
				for($i = 0; $i< $obj_array_len; $i++)
				{
					$str_array[$i] = array($i, $obj_array[$i]->getName());
				}
				break;
			case 'description':
				$obj_array_len = sizeof($obj_array);
				$str_array = array();
				for($i = 0; $i< $obj_array_len; $i++)
				{
					$str_array[$i] = array($i, $obj_array[$i]->getDescription());
				}
				break;
			case 'modified_on':
				$obj_array_len = sizeof($obj_array);
				$str_array = array();
				for($i = 0; $i< $obj_array_len; $i++)
				{
					$str_array[$i] = array($i, $obj_array[$i]->getModifiedDate());
				}
				break;
			case 'author':
				$obj_array_len = sizeof($obj_array);
				$str_array = array();
				for($i = 0; $i< $obj_array_len; $i++)
				{
				   	$full_name_array = $obj_array[$i]->getOwnerFullName();           	
					$str_array[$i] = array($i, $full_name_array[1] . ', ' . $full_name_array[0]);
				}
				break;
			case 'created_date':
				$obj_array_len = sizeof($obj_array);
				$str_array = array();
				for($i = 0; $i< $obj_array_len; $i++)
				{
					$str_array[$i] = array($i, $obj_array[$i]->getCreatedDate());
				}
				break;
			case 'size':
				$obj_array_len = sizeof($obj_array);
				$str_array = array();
				for($i = 0; $i< $obj_array_len; $i++)
				{
					$str_array[$i] = array($i, $obj_array[$i]->getId());
					$str_array[$i][1] = filesize($GLOBALS['CONFIG']['dataDir'].$str_array[$i][1].'.dat');
				}
				break;
			case 'id':
				$obj_array_len = sizeof($obj_array);
				$str_array = array();
				for($i = 0; $i< $obj_array_len; $i++)
				{
					$str_array[$i] = array($i, $obj_array[$i]->getId());
				}
				break;
			case 'department':
				$obj_array_len = sizeof($obj_array);
				$str_array = array();
				for($i = 0; $i< $obj_array_len; $i++)
				{
					$str_array[$i] = array($i, $obj_array[$i]->getDeptName());
				}
				break;
			default : // do an id sort
				$obj_array_len = sizeof($obj_array);
				$str_array = array();
				for($i = 0; $i< $obj_array_len; $i++)
				{
					$str_array[$i] = array($i, $obj_array[$i]->getId());
				}
				break;
		}
		if($sort_by == 'id' or $sort_by == 'size')
		{	$str_sorted_array = int_array2D_sort($str_array, $sort_order);	}
		else
		{	$str_sorted_array = str_array2D_sort($str_array, $sort_order);	}
			
		$str_sorted_array_len = sizeof($str_sorted_array);
		$obj_sorted_array = array();
		for($i = 0; $i<$str_sorted_array_len; $i++)
		{
			$obj_sorted_array[$i] = $obj_array[$str_sorted_array[$i][0]];
		}
		return $obj_sorted_array;
	} 
	function my_sort ($id_array, $sort_order = 'asc', $sort_by = 'id')
	{
		if (sizeof($id_array) == 0 )
			return $id_array;
		if($sort_order == 'asc')
			$sort_order = 'asc';
		else
			$sort_order = 'desc';
		$lwhere_or_clause = '';
		$larray_len = sizeof($id_array);
		for($li = 0; $li < $larray_len; $li++)
		{
			$lwhere_or_clause .= 'data.id=' . $id_array[$li];
			if($li != $larray_len-1)
			{   $lwhere_or_clause .= ' OR ';  }
		}
		if( $sort_by == 'id' )
		{
			$lquery = 'SELECT id from data WHERE ';
			$lquery .= $lwhere_or_clause . ' ORDER BY id ' . $sort_order;
		}
		elseif($sort_by == 'author')
		{
			$lquery = 'SELECT data.id FROM data, user WHERE data.owner = user.id AND (';
			$lquery .= $lwhere_or_clause . ') ORDER BY user.last_name ' . $sort_order . ' , user.first_name ' . $sort_order  . ', data.id asc';
		}
		elseif($sort_by == 'file_name')
		{
			$lquery = 'SELECT data.id FROM data WHERE ';
			$lquery .= $lwhere_or_clause . ' ORDER BY data.realname ' . $sort_order . ', data.id asc';
		}
		elseif($sort_by == 'department')
		{
			$lquery = 'SELECT data.id FROM data, department WHERE data.department = department.id AND (';
			$lquery .= $lwhere_or_clause . ') ORDER BY department.name ' . $sort_order . ', data.id asc';
		}
		elseif($sort_by == 'created_date' )
		{
			$lquery = 'SELECT data.id FROM data WHERE ';
            $lquery .= $lwhere_or_clause . ' ORDER BY data.created ' . $sort_order . ', data.id asc';
		}
		elseif($sort_by == 'modified_on')
		{
			$lquery = 'SELECT data.id FROM log, data WHERE data.id = log.id AND log.revision="current" AND (';
			$lquery .= $lwhere_or_clause . ') GROUP BY id ORDER BY modified_on ' . $sort_order . ', data.id asc';
		}
		elseif($sort_by == 'description')
		{
			$lquery = 'SELECT data.id FROM data WHERE  (';
			$lquery .= $lwhere_or_clause . ') ORDER BY data.description ' . $sort_order . ', data.id asc';
		}
		elseif($sort_by == 'size')
		{
			$lquery = 'SELECT data.id FROM data WHERE  (';
			$lquery .= $lwhere_or_clause . ') ORDER BY data.filesize ' . $sort_order . ', data.id asc';
		}
		$time = time(); 
		$lresult = mysql_query($lquery) or die('Error in querying:' . $lquery . mysql_error());
		echo "load mysql time: " . (time() - $time);
		for($li = 0; $li<mysql_num_rows($lresult); $li++)
			list($array[$li]) = mysql_fetch_row($lresult);
		return $array;
	}
	function my_sort2 ($lquery, $sort_order = 'asc', $sort_by = 'id')
	{
		if (strlen($lquery) == 0 )
			return $lquery;
		if($sort_order == 'asc')
			$sort_order = 'asc';
		else
			$sort_order = 'desc';
		$clauses = array();
		$clauses[0] = substr($lquery, 0, strpos($lquery, 'from'));
		
		if( $sort_by == 'id' )
		{
			$lquery = 'SELECT id from data WHERE ';
			$lquery .= $lwhere_or_clause . ' ORDER BY id ' . $sort_order;
		}
		elseif($sort_by == 'author')
		{
			$lquery = 'SELECT data.id FROM data, user WHERE data.owner = user.id AND (';
			$lquery .= $lwhere_or_clause . ') ORDER BY user.last_name ' . $sort_order . ' , user.first_name ' . $sort_order  . ', data.id asc';
		}
		elseif($sort_by == 'file_name')
		{
			$lquery = 'SELECT data.id FROM data WHERE ';
			$lquery .= $lwhere_or_clause . ' ORDER BY data.realname ' . $sort_order . ', data.id asc';
		}
		elseif($sort_by == 'department')
		{
			$lquery = 'SELECT data.id FROM data, department WHERE data.department = department.id AND (';
			$lquery .= $lwhere_or_clause . ') ORDER BY department.name ' . $sort_order . ', data.id asc';
		}
		elseif($sort_by == 'created_date' )
		{
			$lquery = 'SELECT data.id FROM data WHERE ';
            $lquery .= $lwhere_or_clause . ' ORDER BY data.created ' . $sort_order . ', data.id asc';
		}
		elseif($sort_by == 'modified_on')
		{
			$lquery = 'SELECT data.id FROM log, data WHERE data.id = log.id AND log.revision="current" AND (';
			$lquery .= $lwhere_or_clause . ') GROUP BY id ORDER BY modified_on ' . $sort_order . ', data.id asc';
		}
		elseif($sort_by == 'description')
		{
			$lquery = 'SELECT data.id FROM data WHERE  (';
			$lquery .= $lwhere_or_clause . ') ORDER BY data.description ' . $sort_order . ', data.id asc';
		}
		elseif($sort_by == 'size')
		{
			$lquery = 'SELECT data.id FROM data WHERE  (';
			$lquery .= $lwhere_or_clause . ') ORDER BY data.filesize ' . $sort_order . ', data.id asc';
		}
		$time = time(); 
		$lresult = mysql_query($lquery) or die('Error in querying:' . $lquery . mysql_error());
		echo "load mysql time: " . (time() - $time);
		for($li = 0; $li<mysql_num_rows($lresult); $li++)
			list($array[$li]) = mysql_fetch_row($lresult);
		return $array;
	}
	// This function draws the menu screen
        function draw_menu($uid='')
        {
            echo "\n".'<!------------------begin_draw_menu------------------->'."\n";
            echo "\n".'<!------------------UID is ' . $uid . '------------------->'."\n";
            if($uid != NULL)
            {
            	$current_user_obj = new User($uid, $GLOBALS['connection'], $GLOBALS['database']);
            }
            echo '<table width="100%" cellspacing="0" cellpadding="0">'."\n";
            echo '<tr>'."\n";
            echo '<td align="left"><a href="out.php"><img src="images/companylogo.gif" alt="'.$GLOBALS['CONFIG']['title'].'" border="0"></a></td>'."\n";
            echo '<td align="right" nowrap>'."\n";
            echo '<a href="in.php"><img src="images/check-in.png" alt="Check In" border=0></a>'."\n";
            echo '<a href="search.php"><img src="images/search.png" alt="Search" border=0></a>'."\n";
            echo '<a href="add.php"><img src="images/add.png" alt="Add" border="0"></a>'."\n";
            if($uid != NULL and $current_user_obj->isAdmin())
            {
                echo '<a href="admin.php"><img src="images/setting.png" alt="Administration" border="0"></a>'."\n";
            }
            echo '<a href="logout.php"><img src="images/logout.png" alt="Logout" border="0"></a>'."\n";
            echo '</td>'."\n";
            echo '</tr>'."\n";
            echo '</table>'."\n";
            echo "\n".'<!------------------end_draw_menu------------------->'."\n";
        }
	function draw_header($page_title)
	{
		if (!isset($page_title))
		{
			$page_title='Main';
		}
		echo '<!---------------------------Start drawing header----------------------------->'."\n";
		echo '<html>'."\n";
		echo '	<HEAD>'."\n";
		echo '  	<TITLE>'.$GLOBALS['CONFIG']['title'].' - '.$page_title.'</TITLE>'."\n";
?>
		<SCRIPT TYPE="text/javascript">
		<!--
		function popup(mylink, windowname)
		{
			if (! window.focus)return true;
			var href;
			if (typeof(mylink) == 'string')
				href=mylink;
			else
				href=mylink.href;
			window.open(href, windowname, 'width=300,height=500,scrollbars=yes');
			return false;
		}
		//-->
		</SCRIPT>
<?php
		echo '	</HEAD>'."\n";
		echo '  	<body bgcolor="white">'."\n";
		echo ' 		<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>'."\n";
		echo ' 		<script language="JavaScript" src="./overlib.js"><!-- overLIB (c) Erik Bosrup --></script>'."\n";
		echo '<!----------------------------End drawing header----------------------------->'."\n";
	}

	function draw_error($message)
	{
		header ('Location:' . $message);
	}
	
	function draw_footer()
	{
		echo "\n".'<!-------------------------------begin_draw_footer------------------------------>'."\n";
		echo '<hr>'."\n";
		echo ' <h5>'.$GLOBALS['CONFIG']['current_version'].'<BR>';
		echo '&copy; <a href="mailto:'.$GLOBALS['CONFIG']['site_mail'].'">'.$GLOBALS['CONFIG']['title'].'</a>'."\n";
		echo ' </body>'."\n";
		echo '</html>'."\n";
		echo '<!-------------------------------end_draw_footer------------------------------>'."\n";
	}
        function view_file($filename)
        {
                header('Content-Length: '.filesize($GLOBALS['CONFIG']['dataDir'].'/'.$filename));
                //header("Content-Type: application/pdf");
                header('Content-Disposition: inline; filename='.$GLOBALS['CONFIG']['dataDir'].'/'.$filename);
                // Apache is sending Last Modified header, so we'll do it, too
                //header("Last-Modified:  Just now");   // something like Thu, 0
                readfile($GLOBALS['CONFIG']['dataDir'].'/'.$filename);
        }

        function parse_string($string, $delimiter)
        {
                $parsedArray = array();
                for($i = 0; $i<strlen($string); $i++)
                {
                        $pos = strpos($string, $delimiter, $i);
                        if($pos ==NULL)
                        { 
                                $pos = strlen($string);
                                $parsedArray[sizeof($parsedArray)]=substr($string, $i, ($pos-$i));
                                $i = strlen($string);
                        }
                        else
                        {
                                $parsedArray[sizeof($parsedArray)]=substr($string, $i, ($pos-$i));
                                $i = $pos;
                        }
                }
                return $parsedArray;
        }
	function add_arrays($array1, $array2)
	{
		$result_array = $array1;
		$index = sizeof($result_array);
		for($i = 0; $i<sizeof($array2); $i++)
			$result_array[$index++] = $array2[$i];
		return $result_array;
	}
	function trim_strings($str_array, $percentage)
	{
		$result_array = array();
		$result_array_index = 0;
		for($i=0; $i<sizeof($str_array); $i++)
		{
			$word_len = strlen($str_array[$i]);
			$matching_len = (int)($percentage / 100 * $word_len);
			$temp_array = array();
			$temp_array_index = 0;
			for($j = 0; $j<=$word_len-$matching_len; $j++)
			{
				$temp_array[$temp_array_index++] = substr($str_array[$i], $j, $matching_len);
			}
			$result_array[$result_array_index++] = $temp_array;
		}
		return $result_array;
	}
	function len_filter_str_array($str_array, $len)
	{
		$array_len = sizeof($str_array);
		$result_array = array();
		$index = 0;
		for($i = 0; $i<$array_len; $i++)
		{
			if(strlen($str_array[$i])>=$len)
				$result_array[$index++] = $str_array[$i];
		}
		return $result_array;
	}
				
	function word_count($string)
	{
		$count = 0;
		for($i = 0; $i<strlen($string); $i++)
			if($string[$i] == " ")
				$count++;
		return (count+1);
	}
        function str_search($query, $str_array, $exactWord, $case_sensitivity = false)
        {
                $found_array = array();
                if(!isset($case_sensitivity))
                {
                        $query = strToLower($query);
                        for($i = 0; $i<sizeof($str_array); $i++)
                        {
                                $str_array[$i] = strToLower($str_array[$i]);
                        }
                }

                if(isset($exactWord))
                {
                        $index = 0 ;
                        for($i = 0; $i<sizeof($str_array); $i++)
                        {
                                $found_array[$index++]=($query == $str_array[$i]);
                        }

                        return $found_array;
                }
                else
                {
                        $found_exact_array = str_search($query, $str_array, true, $case_sensitivity);
                        $found_not_exact_array = array();
                        $index = 0;
                        for($i = 0; $i<sizeof($str_array); $i++)
                        {
                                $small_array = parse_string($str_array[$i], " ");
                                $temp_array = str_search($query, $small_array, true, $case_sensitivity);
                                $temp_array_size = sizeof($temp_array);
                                $found_flag=false;
                                for($j = 0; $j<$temp_array_size; $j++)
                                {
                                        if($temp_array[$j]==1)
                                        {
                                                $found_not_exact_array[$index++] = true;
                                                $j = $temp_array_size;
                                                $found_flag = 1;
                                        }
                                }
                                if(!$found_flag)
                                {
                                	$found_not_exact_array[$index++] = false;
                                }


                        }
                        return mergeArrays($found_not_exact_array, $found_exact_array, true);
                }
        }
        function mergeArrays($high_priority_array, $low_priority_array, $priority_factor)
        {
                $array_len = sizeof($high_priority_array);
                if($array_len != sizeof($low_priority_array) )
                        return 0;
                for($i = 0; $i < $array_len; $i++)
                {
                        if($high_priority_array[$i] != $priority_factor && $low_priority_array[$i] == $priority_factor)
                        {
                                $high_priority_array[$i] = $low_priority_array[$i];
                        }
                }
                return $high_priority_array;
        }
        function merge2DArrays($high_priority_array, $low_priority_array, $priority_factor, $comparison_index)
        {
                $array_len = sizeof($high_priority_array);
                if($array_len != sizeof($low_priority_array) )
                        return 0;
                for($i = 0; $i < $array_len; $i++)
                {
                        if($high_priority_array[$i][$comparison_index] != $priority_factor && $low_priority_array[$i][$comparison_index] == $priority_factor)
                        {
                                $high_priority_array[$i] = $low_priority_array[$i];
                        }
                }
                return $high_priority_array;
        }
        function email_all($mail_from, $mail_subject, $mail_body, $mail_header)
        {
                $query = "SELECT Email from user";
                $result = mysql_query($query, $GLOBALS['connection']) or die ("Error in query: $query . " . mysql_error());	
                while( list($mail_to) = mysql_fetch_row($result) )
                {
                        mail($mail_to, $mail_subject, $mail_body, $mail_header);
                }
                mysql_free_result($result);
        }
        function email_dept($mail_from, $dept_id, $mail_subject, $mail_body, $mail_header)
        {
                $query = 'SELECT Email from user where user.department = '.$dept_id;
                $result = mysql_query($query, $GLOBALS['connection']) or die ("Error in query: $query . " . mysql_error());	
                while( list($mail_to) = mysql_fetch_row($result) )
                {
                        mail($mail_to, $mail_subject, $mail_body, $mail_header);
                }
                mysql_free_result($result);
        }
        function email_users_obj($mail_from, $user_OBJ_array, $mail_subject, $mail_body, $mail_header)
        {
                for($i = 0; $i< sizeof($user_OBJ_array); $i++)
                {
                        mail($user_OBJ_array[$i]->getEmailAddress(), $mail_subject, $mail_body, $mail_header);
                }
        }
        function email_users_id($mail_from, $user_ID_array, $mail_subject, $mail_body, $mail_header)
        {
                for($i = 0; $i<sizeof($user_ID_array); $i++)
                        $OBJ_array[$i] = new User($user_ID_array[$i], $GLOBALS['connection'], $GLOBALS['database']);
                email_users_obj($mail_from, $OBJ_array, $mail_subject, $mail_body, $mail_header);
		}
		function getmicrotime(){ 
			list($usec, $sec) = explode(" ",microtime()); 
			return ((float)$usec + (float)$sec); 
		}
        function list_files($fileid_array, $userperms_obj, $page_url, $dataDir, $sort_order = 'asc', $sort_by = 'id', $starting_index = 0, $stoping_index = 5, $showCheckBox = 'false', $with_caption = 'false')
        {
                echo "\n".'<!----------------------Table Starts----------------------->'."\n";
                $checkbox_index = 0;
                $count = sizeof($fileid_array);
                $css_td_class = "'listtable'";
                if($sort_order == 'asc')
                {
                        $sort_img = 'images/icon_sort_az.gif';
                        $next_sort = 'desc';
                }
                else if($sort_order == 'desc')
                {
                        $sort_img = 'images/icon_sort_za.gif';
                        $next_sort = 'asc';
                }
                else 
                {
                        $sort_img ='images/icon_sort_null';
                        $next_sort = 'asc';
                }		

                echo '<B><FONT size="-2"> '.$starting_index.'-'.$stoping_index.'/';
                echo $count; 
                echo(" found document(s)</FONT></B>\n");
                echo('<BR><BR>'."\n");
                $index = $starting_index;
                $url_pre = '<TD class=' . $css_td_class . 'NOWRAP><B><A HREF="' . $page_url . '&sort_order=' . $next_sort . '&sort_by=' . $sort_by . '">';
                $url_post = '<B></A> <IMG SRC=' . $sort_img . '></TD>';
                $default_url_pre = "<TD class=$css_td_class NOWRAP><B><A HREF=\"$page_url"."&sort_order=asc&sort_by=";
                $default_url_mid = '">';
                $default_url_post = "<B></TD>";
                echo("<TABLE name='list_file' border='0' hspace='0' hgap='0' CELLPADDING='1' CELLSPACING='1' >");
                echo("<TR bgcolor='83a9f7' id = '1'>");
                if($showCheckBox=='true')
                {
                        echo '<TD><input type="checkbox" onClick="selectAll(this)"></TD>';
                }
                if($sort_by == 'id')
                {
                        $str = $url_pre.'ID'.$url_post;
                }
                else
                {
                        $str = $default_url_pre.'id'.$default_url_mid.'ID'.$default_url_post;
                }
                echo($str);

                if($sort_by == 'file_name')
                {
                        $str = $url_pre.'File Name'.$url_post;
                }
                else
                { 
                        $str = $default_url_pre.'file_name'.$default_url_mid.'File Name'.$default_url_post;
                }
                echo($str);

                if($sort_by == 'description')
                {
                        $str = $url_pre.'Descripton'.$url_post;
                }
                else
                {
                        $str = $default_url_pre.'description'.$default_url_mid.'Description'.$default_url_post;
                }
                 echo($str);

                if($sort_by == 'access_right')
                {
                        $str = '<TD class="' . $css_td_class . '"><B>Rights<B><IMG SRC="' . $sort_img . '"></TD>';
                }
                else
                { 
                        $str = '<TD class="' . $css_td_class . '"><B>Rights<B></TD>';
                }
                echo($str);

                if($sort_by == 'comments')
                {
                        $str = '<TD class="' . $css_td_class . '" NOWRAP><B>Comments<B> <IMG SRC="' . $sort_img . '"></TD>';
                }
                else 
                {
                        $str = '<TD class="' . $css_td_class . '" NOWRAP><B>Comments<B></TD>';
                }
                echo($str);

                if($sort_by == 'created_date')
                {
                        $str = $url_pre.'Created Date'.$url_post;
                }
                else
                {
                        $str = $default_url_pre.'created_date'.$default_url_mid.'Created Date'.$default_url_post;
                }
                echo($str);

                if($sort_by == 'modified_on')
                {
                        $str = $url_pre.'Modifed Date'.$url_post;
                }
                else
                {
                        $str = $default_url_pre.'modified_on'.$default_url_mid.'Modified Date'.$default_url_post;
                }                
                echo($str);

                if($sort_by == 'author')
                {
                        $str = $url_pre.'Author'.$url_post;
                }
                else
                {
                        $str = $default_url_pre.'author'.$default_url_mid.'Author'.$default_url_post;
                }
                echo($str);

                if($sort_by == 'department')
                {
                        $str = $url_pre.'Department'.$url_post;
                }
                else
                {
                        $str = $default_url_pre.'department'.$default_url_mid.'Department'.$default_url_post;
                }
                echo($str);

                if($sort_by == 'size')
                {
                        $str = $url_pre.'Size'.$url_post;
                }
                else
                {
                        $str = $default_url_pre.'size'.$default_url_mid.'Size'.$default_url_post;
                }
                echo($str);

                if($sort_by == 'status')
                {
                        $str = '<TD NOWRAP class="' . $css_td_class . '"><B>Avail<B> <IMG SRC="' . $sort_img . '"></TD>';
                }
                else
                {                
                        $str = '<TD NOWRAP class="' . $css_td_class . '"><B>Avail<B></TD>';
                }
                echo($str);		
                echo '</TR>';
                echo '<HD6>';
                $even_row_color = 'FCFCFC';
                $odd_row_color = 'E3E7F9';
                $unlock_highlighted_color = '#bdf9b6';
                $lock_highlighted_color = '#ea7741';
                echo "\n";
                if(!isset($fileid_array))
                {
                        echo '</TABLE>';
                        return 0;
                }
        		if(!isset($_REQUEST['state']))
        			$_REQUEST['state']=1;
                while($index<sizeof($fileid_array) and $index>=$starting_index and $index<=$stoping_index)
                {
						if($index%2!=0)
                        {
                                $tr_bgcolor = $odd_row_color;
                        }
                        else
                        { 
                                $tr_bgcolor = $even_row_color;
                        }
                        $file_obj = new FileData($fileid_array[$index], $GLOBALS['connection'], $GLOBALS['database']);
						if ($file_obj->getStatus() == 0 and $userperms_obj->getAuthority($fileid_array[$index]) >= $userperms_obj->WRITE_RIGHT)
                        {
                                $lock = false;
                                $highlighted_color = $unlock_highlighted_color;
                        }
                        else
                        {
                                $lock = true;
                                $highlighted_color = $lock_highlighted_color;
                        }
                        
                        if($with_caption == true )
                        {
                                // correction for empty description
                                ?><TR bgcolor="<?php echo $tr_bgcolor;?>" id="<?php echo $index;?>" onMouseOver="this.style.backgroundColor='<?php echo $highlighted_color;?>'"; return overlib('Comments');" onMouseOut="this.style.backgroundColor='<?php echo $tr_bgcolor;?>'; return nd();"><?
                        }
                        else
                        {
	                        ?><TR bgcolor=<?php echo $tr_bgcolor;?> id = <?php echo $index;?> onMouseOver="this.style.backgroundColor='<?php echo $highlighted_color;?> ';" onMouseOut="this.style.backgroundColor='<?php echo $tr_bgcolor;?>';"><?
                        } 
                        if ($file_obj->getDescription() == '') 
                        { 
                                $description = 'No description available';
                        }
                        // set filename for filesize() call below
                        $filename = $dataDir . $file_obj->getId() . '.dat';
                        $fid = $file_obj->getId();


                        // begin displaying file list with basic information
                        $comment = $file_obj->getComment();
                        $description = $file_obj->getDescription();
                        $created_date = fix_date($file_obj->getCreatedDate());
                        if ($file_obj->getModifiedDate())
                        {
                                $modified_date = fix_date($file_obj->getModifiedDate());
                        }
                        //echo "$modified_date  and $fid fid";
                        $full_name_array = $file_obj->getOwnerFullName();
                        $owner_name = $full_name_array[1].', '.$full_name_array[0];
                        //$user_obj = new User($file_obj->getOwner(), $file_obj->connection, $file_obj->database);
                        $dept_name = $file_obj->getDeptName();
                        $realname = $file_obj->getRealname();
                        $filesize = $file_obj->getFileSize();
                        if($showCheckBox=='true')
                        {
?>
						<TD><input type="checkbox" value="<?php echo $fid; ?>" name="checkbox<?php echo $checkbox_index;?>"></B></TD>
<?php
                        }
?>                        <TD class="<?php echo $css_td_class; ?>"><?php echo $fid;?><B></TD>
                        <TD class="<?php $css_td_class;?>" NOWRAP><a class="listtable" href="details.php?id=<?php echo $fid.'&state=' . ($_REQUEST['state']+1);?>"><?php echo $realname;?></a></TD>
                        <TD class="<?php echo $css_td_class;?>" NOWRAP><?php echo $description;?></TD>
<?php
                        $read = array($userperms_obj->READ_RIGHT, 'r');
                        $write = array($userperms_obj->WRITE_RIGHT, 'w');
                        $admin = array($userperms_obj->ADMIN_RIGHT, 'a');
                        $rights = array($read, $write, $admin);
                        $userright = $userperms_obj->getAuthority($file_obj->getId());
                        $index_found = -1;
                        //$rights[max][0] = admin, $rights[max-1][0]=write, ..., $right[min][0]=view
                        //if $userright matches with $rights[max][0], then this user has all the rights of $rights[max][0]
                        //and everything below it. 
                        for($i = sizeof($rights)-1; $i>=0; $i--)
                        {
                                if($userright==$rights[$i][0])
                                {
                                        $index_found = $i;
                                        $i = 0;
                                }
                        }
                        //Found the user right, now bold every below it.  For those that matches, make them different.
            			for($i = $index_found; $i>=0; $i--)
                        {
                                $rights[$i][1]='<b>'. $rights[$i][1] . '</b>';
                        }
                        //For everything above it, blanck out
                        
                        for($i = $index_found+1; $i<sizeof($rights); $i++)
                        {
                                $rights[$i][1] = '-';
                        }
?>						<TD class="<?php echo $css_td_class; ?>" NOWRAP>
<?
                        for($i = 0; $i<sizeof($rights); $i++)
                        {
                                echo $rights[$i][1] . '|';
                        }
?>                      </TD>
<?                        
                        if($comment == '')
                        {
                                $comment='No comments available';                     
                        }
                        
                        if(strlen($comment) > $GLOBALS['CONFIG']['displayable_len'])
                        {
                                $comment = substr($comment, 0, $GLOBALS['CONFIG']['displayable_len']).'...';
                        }
?>                        
                        <TD class="<?php echo $css_td_class; ?>" NOWRAP><?php echo $comment; ?></TD>
                        <TD class="<?php echo $css_td_class; ?>" NOWRAP><?php echo $created_date;?></TD>
                        <TD class="<?php echo $css_td_class; ?>" NOWRAP><?php echo $modified_date;?></TD>
                        <TD class="<?php echo $css_td_class; ?>" NOWRAP><?php echo $owner_name; ?></TD>
                        <TD class="<?php echo $css_td_class; ?>" NOWRAP><?php echo $dept_name; ?></TD>
						<TD class="<?php echo $css_td_class; ?>" NOWRAP><?php echo $filesize; ?></TD> 	      <?              
						if ($lock == false)
						{
							?><TD NOWRAP><CENTER><img src="images/file_unlocked.png"></CENTER></TD><?
						}
						else
						{
							?><TD align="center" NOWRAP><img src="images/file_locked.png"></TD><?
						}
                        
                        $index++;
                        ?></TR><?
                        $checkbox_index++;
                }
                ?><INPUT type="hidden" name="num_checkboxes" value="<?php echo $checkbox_index;?>">
                </HD6>
                </TABLE>
                <Script Language="javascript">
                function selectAll(ctrl_checkbox)
                {
                        elements = document.forms[0].elements;
                        for(i = 0; i< elements.length; i++)
                                {
                                        if(elements[i].type == "checkbox")
                                                elements[i].checked = ctrl_checkbox.checked;
                                        }
                                } 
                </script>
                
                <!----------------------Table Ends----------------------->
<?php
                if (!isset($num_checkboxes))
                {
                        $num_checkboxes='0';
                }
                
                return $num_checkboxes;	
        }

        function list_nav_generator($total_hit, $page_limit, $link_limit, $page_url, $current_page = 0, $sort_by = 'id', $sort_order = 'asc')
        {
                if($total_hit<$page_limit)
                {
                        return 0;
                }

                echo '<center>Result Page:&nbsp;&nbsp;';
                $num_pages = ceil($total_hit/($page_limit));
          		$shown_pages = 0;
          		if($num_pages > $link_limit )
          		{	$shown_pages = $link_limit;	}
          		else { $shown_pages = $num_pages; }
                $index_result = 0;
                
                if( $current_page > 0 )
                {
                     echo "<a href='$page_url&sort_by=$sort_by&sort_order=$sort_order&starting_index=".($page_limit*($current_page-1))."&stoping_index=".($current_page*$page_limit-1)."&page=".($current_page-1)."'>Prev</a>&nbsp; &nbsp;";
                }
                
				if($current_page >= $link_limit/2)
                {	$i = $current_page - $link_limit/2; 	}
				else if($current_page < $link_limit/2)
				{	$i = 0;	}
				else
				{	$i = $current_page;	}
				if( $current_page + ceil($link_limit/2) > $num_pages)
					$last_page = $num_pages;
				else
					$last_page =  $current_page + ceil($link_limit/2);
				for(; $i < $last_page; $i++)
				{       
					if($current_page== $i)
					{
						echo $i . '&nbsp;&nbsp;';
					}
					else
					{
						echo "<a href='$page_url&sort_by=$sort_by&sort_order=$sort_order&starting_index=$index_result&stoping_index=".($index_result+$page_limit-1)."&page=$i'>$i</a>&nbsp; &nbsp;"; 
					}
					$index_result = $index_result + $page_limit;
				}
                if( $current_page < $num_pages-1 )
                {
                        echo "<a href='$page_url&sort_by=$sort_by&sort_order=$sort_order&starting_index=".($page_limit*($current_page+1))."&stoping_index=".(($current_page+2)*$page_limit-1)."&page=".($current_page+1)."'>Next</a>&nbsp; &nbsp;";
                }
        }

	function sort_browser()
	{
?>
		<SCRIPT language="javascript">
		var category_option = '';
		var category_item_option = '';
		
		function loadItem(select_box)
		{
			options_array = document.forms['browser_sort'].elements['category_item'].options;
			// Clear the list
			for(i=0; i< options_array.length; i++)
			{	options_array[i]=null;	}
			options_array.length = 0;
			switch(select_box.options[select_box.selectedIndex].value)
			{
				case 'author':
					info_Array = author_array;
					break;
				case 'department':
					info_Array = department_array;
					break;
				case 'category':
					info_Array = category_array;
					break;
				default : 
					order_array = document.forms['browser_sort'].elements['category_item_order'].options;
					info_Array = new Array();
						info_Array[0] = new Array('Empty', 0);
					break;
			}
			category_option = select_box.options[select_box.selectedIndex].value;
			options_array[0] = new Option('Choose a(n) ' + category_option);
			options_array[0].id= 0;
			options_array[0].value = 'choose_an_author';
			
			for(i=0; i< info_Array.length; i++)
			{
				options_array[ i + 1 ]= new Option(info_Array[i][0]);
				options_array[ i + 1 ].id= i + 1;
				options_array[ i + 1 ].value = info_Array[i][0];
			}
			category_option = select_box.options[select_box.selectedIndex].value;
		}
		function loadOrder(select_box)
		{
			category_item_option = select_box.options[select_box.selectedIndex].value;
			if(category_item_option == 'choose_an_author')
				exit();
			order_array = new Array();
				order_array[0] = new Array('Ascending', 0, 'asc');
				order_array[1] = new Array('Descending', 1, 'desc');
			options_array = document.forms['browser_sort'].elements['category_item_order'].options;
			
			options_array[0] = new Option('Choose an Order');
			options_array[0].id= 0;
			options_array[0].value = 'choose_an_order';
			for(i=0; i< order_array.length; i++)
			{
				options_array[i+1]= new Option(order_array[i][0]);
				options_array[i+1].id= i + 1;
				options_array[i+1].value = order_array[i][2];
			}	
		}

		function load(select_box)
		{
			window.location = "search.php?submit=submit&sort_by=id&where=" + category_option + "_only&sort_order=" + select_box.options[select_box.selectedIndex].value + "&keyword=" + category_item_option;
		}
<?php
		///////////////////////////////FOR AUTHOR///////////////////////////////////////////
		$query = "SELECT last_name, first_name, id FROM user ORDER BY username ASC";
		$result = mysql_query($query, $GLOBALS['connection']) or die('Error in query'. mysql_error());
		$count = mysql_num_rows($result);
		$index = 0;
		echo("author_array = new Array();\n");
		while($index < $count)
		{	
			list($last_name, $first_name, $id) = mysql_fetch_row($result);
			echo("\tauthor_array[$index] = new Array(\"$last_name $first_name\", $id);\n");
			$index++;
		}
		///////////////////////////////FOR DEPARTMENT//////////////////////////
		$query = "SELECT name, id FROM department ORDER BY name ASC";
		$result = mysql_query($query, $GLOBALS['connection']) or die('Error in query'. mysql_error());
		$count = mysql_num_rows($result);
		$index = 0;
		echo("department_array = new Array();\n");
		while($index < $count)
		{	
			list($dept, $id) = mysql_fetch_row($result);
			echo("\tdepartment_array[$index] = new Array(\"$dept\", $id);\n");
			$index++;
		}
		///////////////////////////////FOR FILE CATEGORY////////////////////////////////////////
		$query = "SELECT name, id FROM category ORDER BY name ASC";
		$result = mysql_query($query, $GLOBALS['connection']) or die('Error in query'. mysql_error());
		$count = mysql_num_rows($result);
		$index = 0;
		echo("category_array = new Array();\n");
		while($index < $count)
		{	
			list($category, $id) = mysql_fetch_row($result);
			echo("\tcategory_array[$index] = new Array(\"$category\", $id);\n");
			$index++;
		}
		///////////////////////////////////////////////////////////////////////
		echo '</script>'."\n";
?>
		<form name="browser_sort">
			<table name="browser" border="1" cellspacing="1">
			<tr><td>Browse by:</td>
				<td NOWRAP ROWSPAN="0">
					<select name='category' onChange='loadItem(this)' width='0' size='1'>
						<option id='0' selected>Select one</option>
						<option id='1' value='author'>Author</option>
						<option id='2' value='department'>Department</option>
						<option id='3' value='category'>File Category</option>
					</select>
				</td>
				<td>
					<select name='category_item' onChange='loadOrder(this)'>
						<option id='0' selected>Empty</option>
					</select>	
				</td>
				<td>
					<select name='category_item_order' onChange='load(this)'>
						<option id='0' selected>Empty</option>
					</select>	
				</td>
			</tr>
			</table>
		</form>
<?php
	}		
	
	function display_filesize($filename)
	{
		$filesize='';
		$size=filesize($filename);
		if($size > 1024 && $size < 1048576 )
		{
			$filesize=($size/1024);
			$filesize .=' Kilo-Bytes';
			echo ($filesize);
		}
		else if($size >= 1048576 )
		{
			$filesize = ($size / 1048576);
			$filesize .=' Mega-Bytes';
			echo ($filesize);
		}
		else 
		{
			$filesize=$size;
			$filesize .=' Bytes';
			echo ($filesize);
		}
	}
	/* fixQuote is used to make a normal string with embbed single and double quotation
	 into a proper HTML string that a browser can understand without loosing any characters
	 in it.  Let's say your string is 
	$your_str1 = "I like the author "Khoa Nguyen".  That's the author i like".  
	$your_str2 = "I like the author \"Khoa Nguyen\".  That's the author i like".  
	Let's say you want to echo this string to the value of some textfield in your HTML
	Then have this in your HTML:
	<INPUT type="text" value="<?php echo(fixQuote($your_str1)); ?>"
	<INPUT type="text" value="<?php echo(fixQuote($your_str2)); ?>"
	*/

	function fixQuote($string)
	{
		$string[$i] = str_replace('"', '&quot;', $string);
		$string[$i] = str_replace('\\', '', $string);
	}
	/////////////////////////////////////////////////Debuging function/////////////////////////////////

	function display_array($array)
	{
		for($i=0; $i<sizeof($array); $i++)
                {
			echo($i.":".$array[$i]."<br>");
                }
	}

	function display_array2D($array)
	{
		for($i=0; $i<sizeof($array); $i++)
                {
			for($j=0; $j<sizeof($array[$i]); $j++)
                        {
				echo($i.":"."$j".":".$array[$i][$j]."<br>");
                        }
                }
	}

    function makeRandomPassword() 
    {
            $pass='';
	    $salt = 'abchefghjkmnpqrstuvw3456789';
	    srand((double)microtime()*1000000); 
	    $i = 0;
	    while ($i <= 7) 
	    {
	            $num = rand() % 33;
	            $tmp = substr($salt, $num, 1);
	            $pass = $pass . $tmp;
	            $i++;
	    }
    return $pass;	
	}

	function removeElements($master_array, $removing_array)
	{
		$found = false;
		$result_array = array();
		for($i = 0; $i < sizeof($master_array); $i++)
		{
			$found=false;
			for($j=0;$j < sizeof($removing_array); $j++)
			{
				if($master_array[$i] == $removing_array[$j])
				{	$found=true;break;	}
			}
			if(!$found)
			{	$result_array[sizeof($result_array)] = $master_array[$i];	}
		}
		return $result_array;
	}
	function checkUserPermission($file_id, $permittable_right)
	{
		$user_perm_obj = new User_Perms($_SESSION['uid'], $GLOBALS['connection'], $GLOBALS['database']);
		if(!$user_perm_obj->user_obj->isRoot() && $user_perm_obj->getPermission($file_id) < $permittable_right)
		{
			echo 'Error: OpenDocMan is unable to find the requested file.' . "\n";
			echo '       Please email <A href="mailto:' . $GLOBALS['CONFIG']['site_mail'] . '">Document Repository</A> for further assistance.';
			exit();
		}
	}
	function fmove($source_file, $destination_file)
	{
		//read and close
		$lfhandler = fopen ($source_file, "r");
		$lfcontent = fread($lfhandler, filesize ($source_file));
		fclose ($lfhandler);
		//write and close
		$lfhandler = fopen ($destination_file, "w");
		fwrite($lfhandler, $lfcontent);
		fclose ($lfhandler);
		//delete source file
		unlink($source_file);
	}
}
?>