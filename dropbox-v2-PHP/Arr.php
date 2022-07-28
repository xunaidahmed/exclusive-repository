<?php
/**
 * Array class
 *
 * @author Junaid Ahmed <xunaidahmed@live.com>
 * @version 1.1.1
 * @copyright Copyright (c), Junaid Ahmed. All rights reserved.
 * @license PSR-4 License
 */

class Arr
{
	/*
	 * Set Arr
	 * - - - - - - - - - - - -
	 *
	 **/
	public function __construct()
	{
		//code
	}

	/*
	 * Set the DD
	 * - - - - - - - - - - - -
	 *
	 **/
	public static function dd( $arr, $is_exist = true, $heading = '' )
	{
		if ( $heading ) {
			echo "<strong>$heading</strong>";
		}

		echo '<pre>'; print_r( $arr ); echo '</pre>';

		if ( $is_exist ) {
			exit;
		}
	}

	public static function deleteDir($dirPath)
	{
	    if (! is_dir($dirPath)) {
	        throw new InvalidArgumentException("$dirPath must be a directory");
	    }
	    if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
	        $dirPath .= '/';
	    }
	    $files = glob($dirPath . '*', GLOB_MARK);
	    foreach ($files as $file) {
	        if (is_dir($file)) {
	            self::deleteDir($file);
	        } else {
	            unlink($file);
	        }
	    }
	    rmdir($dirPath);
	}

	public static function groupArrIndexMap($array, $index, $remove_index = false)
	{
	    $newArray = array();

	    foreach ($array as $key => $arr)
	    {
	        $arr = (array) $arr;

	        if ( isset($arr[$index]) )
	        {
	            $_tmp = $arr[$index];

	            if ( $remove_index )
	                unset($arr[$index]);

	            $newArray[ $_tmp ][] = $arr;
	        }
	    }

	    return $newArray;
	}

	public static function arrIndexMap($object, $index, $remove_index = false)
	{
	    $newArray = array();

	    foreach ($object as $key => $obj)
	    {
	        if ( isset($obj[$index]) )
	        {
	            $_tmp = $obj[$index];

	            if ( $remove_index )
	                unset($obj[$index]);

	            $newArray[$_tmp] = $obj;
	        }
	    }

	    return $newArray;
	}
}
