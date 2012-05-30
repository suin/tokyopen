<?php

function smarty_modifier_xoops_blocktitleimage( $string , $width = null , $height = null )
{
	list( $title , $imgsrc ) = @explode( '[[' , $string ) ;
	if( empty( $imgsrc ) ) return $title ;

	$width_desc = isset( $width ) ? 'width="'.$width.'" ' : '' ;
	$height_desc = isset( $height ) ? 'height="'.$height.'" ' : '' ;

	return '<img src="'.XOOPS_URL.$imgsrc.'" alt="'.$title.'" '.$width_desc.$height_desc.' />' ;

}

?>