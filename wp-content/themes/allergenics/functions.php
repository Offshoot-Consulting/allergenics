<?php
/*
	DO NOT PUT FUNCTION CODE DIRECTLY IN THIS FILE. EITHER APPEND YOUR FUNCTION TO ONE OF THE FILES THAT ALREADY EXSITS IF AN APPROPRIATE FILE EXISTS, 
	OR OTHER WISE CREATE A NEW PHP FILE IN THE FUNCTION_INCLUDES DIRECTORY AND THE ADD A SINLE REQQUIRE LINE IN THIS FILE.
	
	MAKE SURE YOU PROVIDE CLEAR PLAIN ENGLISH COMMENTS IN YOUR PHP FILES TO EXPLAIN WHAT YOUR FUCNTION DOES
*/

/** Timer code
function microtime_float()
{
     list($usec, $sec) = explode(" ", microtime());
     return ((float)$usec + (float)$sec);
}

$time_start = microtime_float();
//do something here
$time_end = microtime_float();
$time = $time_end - $time_start;
echo "Took $time seconds\n";

 */

/** Put required files below */
require 'function_includes/check_if_this_is _staging_site.php';

require 'function_includes/start_session.php';

include( get_template_directory() . '/widgets.php' );

require 'function_includes/general_theme_stuff.php';

require 'function_includes/Gravity_form_functions.php';

require 'function_includes/Team_section_functions.php';

?>
