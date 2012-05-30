<?php
class Flipr_Library_TemporaryFileCleaner
{
	public static function getTmpFiles()
	{
		$root =& Pengin::getInstance();
		$configHandler =& $root->getModelHandler('Config');
		$configs = $configHandler->getConfigs();

		$upDir = $root->cms->uploadPath.DS.$root->context->dirname; // TODO >> config
		$filePath = $upDir.DS.'tmp_*';

		$files = glob($filePath);

		foreach ( $files as $k => $file )
		{
			if ( filemtime($file) + 60 * 60 * 24 > time() )
			{
				unset($files[$k]); // If file was created in 24 hours, remove from the delete targets.
			}
		}

		return $files;
	}

	public static function clean()
	{
		$files = self::getTmpFiles();

		foreach ( $files as $file )
		{
			unlink($file);
		}
	}

	public static function cleanSomeTime()
	{
		$number = mt_rand(1, 100);

		if ( $number > 50 )
		{
			self::clean();
		}
	}
}