<?php
if (!defined('XOOPS_ROOT_PATH')) exit();
@include_once XOOPS_ROOT_PATH . '/modules/cubeUtils/class/MultiLanguage.class.php';
class MultiLanguagePostMerger extends XCube_ActionFilter
{
    function preFilter()
    {
        if ( ! empty( $_POST ) && file_exists(XOOPS_ROOT_PATH.'/modules/cubeUtils/class/MultiLanguage.class.php')) {
            // just after MultiLanguagePreLoad
            $this->mController->mGetLanguageName->add(array(&$this, 'postMerger'),XCUBE_DELEGATE_PRIORITY_FINAL+1);
        }
    }

    function postMerger()
    {
        global $cubeUtilMlang ;

        if( is_object( $cubeUtilMlang ) ) $this->mergeRecursive( $_POST ) ;
    }

    function mergeRecursive( &$data )
    {
        global $cubeUtilMlang ;

        $merged_string = '' ;
        $langs_counter = 0 ;
        foreach( array_keys( $data ) as $index ) {
            if( is_array( $data[ $index ] ) ) {
                $this->mergeRecursive( $data[ $index ] ) ;
            } else if( in_array( $index , $cubeUtilMlang->mLanguages ) ) {
                $merged_string .= '['.$index.']'.$data[ $index ].'[/'.$index.']' ;
                $langs_counter ++ ;
            }
        }
    
        if( $langs_counter == sizeof( $cubeUtilMlang->mLanguages ) ) {
            $data = $merged_string ;
        }

    }


}
?>