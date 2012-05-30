<?php
/**
 * @file fix server setting params.
 */
if (defined('XCL_MEMORY_LIMIT')){
	ini_set('memory_limit', XCL_MEMORY_LIMIT);
}
