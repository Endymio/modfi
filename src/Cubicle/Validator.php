<?php

namespace Cubicle;

class Validator
{
	/********************************************************************
	* Returns true if the argument is an integer
	********************************************************************/
	static public function IsInt($val) {
	    if ($val != strval(intval($val))) {
	        return false;
	    }
	    return true;
	}

	/********************************************************************
	* Returns true if $val is an integer greater than $min argument
	********************************************************************/
	static public function IsIntMin($val, $min) {
	    if ($val != strval(intval($val))) {
	        return false;
	    }
	    if ($val < $min) {
	        return false;
	    }
	    return true;
	}

	/********************************************************************
	* Returns true if $val is an integer less than $max argument
	********************************************************************/
	static public function IsIntMax($val, $max) {
	    // Make sure the argument is a valid integer
	    if ($val != strval(intval($val))) {
	        return false;
	    }
	    if ($val > $max) {
	        return false;
	    }
	    return true;
	}

	/********************************************************************
	* Returns true if the argument is an integer between min and max
	********************************************************************/
	static public function IsIntMinMax($val, $min, $max) {
	    if ($val != strval(intval($val))) {
	        return false;
	    }
	    if ($val < $min) {
	        return false;
	    }
	    if ($val > $max) {
	        return false;
	    }
	    return true;
	}
}
