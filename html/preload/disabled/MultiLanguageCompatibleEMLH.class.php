<?php

function easiestml( $s , $lang = '' )
{
	global $cubeUtilMlang ;

	if( is_object( @$cubeUtilMlang ) ) {
		if( $lang ) {
			$orig_lang = $cubeUtilMlang->mLanguage ;
			$cubeUtilMlang->mLanguage = $lang ;
			$ret = $cubeUtilMlang->obFilter( $s ) ;
			$cubeUtilMlang->mLanguage = $orig_lang ;
			return $ret ;
		} else {
			return $cubeUtilMlang->obFilter( $s ) ;
		}
	} else {
		return $s ;
	}
}



?>