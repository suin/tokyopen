<?php
class Pengin_Translator
{
	protected $moduleDir  = ''; // モジュールがあるディレクトリ
	protected $compileDir = ''; // コンパイルファイル保存先ディレクトリ
	protected $charset    = ''; // 文字コード utf-8など

	protected $currentTranslation = ''; // 使用中の翻訳のキー
	protected $translations = array(); // 一回読み込んだ翻訳が入る

	public function __construct($moduleDir, $compileDir, $charset = 'utf-8')
	{
		$this->moduleDir  = $moduleDir;
		$this->compileDir = $compileDir;
		$this->charset    = strtolower($charset);
	}

	/**
	 * 使用中の翻訳をセットアップする.
	 * 
	 * @param string $dirname
	 * @param string $langcode
	 * @param string $filename
	 * @return void
	 */
	public function useTranslation($dirname, $langcode, $filename)
	{
		$key = $dirname.'.'.$langcode.'.'.$filename;
		$this->currentTranslation = $key;
		$this->getTranslation($dirname, $langcode, $filename);
	}

	/**
	 * 翻訳を返す.
	 * 
	 * @param string $dirname
	 * @param string $langcode
	 * @param string $filename
	 * @return void
	 */
	public function getTranslation($dirname, $langcode, $filename)
	{
		$key = $dirname.'.'.$langcode.'.'.$filename;

		if ( isset($this->translations[$key]) === false ) {
			$this->translations[$key] = $this->loadTranslation($dirname, $langcode, $filename);
		}

		return $this->translations[$key];
	}

	/**
	 * メッセージを翻訳する.
	 * 
	 * @param mixed $message
	 * @param array $args (default: array())
	 * @return void
	 */
	public function translate($message, $args = array())
	{
		if ( isset($this->translations[$this->currentTranslation][$message]) )
		{
			$message = $this->translations[$this->currentTranslation][$message];
		}

		$total = count($args);

		if ( $total === 0 )
		{
			return $message;
		}

		for ( $i = 0; $i < $total; $i++ )
		{
			$message = str_replace('{'.($i+1).'}', $args[$i], $message);
		}

		return $message;
	}

	/**
	 * コンパイルファイル名を返す.
	 * 
	 * @param string $dirname
	 * @param string $langcode
	 * @param string $filename
	 * @return string
	 */
	public function getCompiledFilename($dirname, $langcode, $filename)
	{
		return sprintf('%s/translation.%s.%s.%s.%s.php', $this->compileDir, $dirname, $langcode, $filename, $this->charset);
	}

	/**
	 * ソースファイル名を返す.
	 * 
	 * @param string $dirname
	 * @param string $langcode
	 * @param string $filename
	 * @return string
	 */
	public function getSourceFilename($dirname, $langcode, $filename)
	{
		return sprintf('%s/%s/language/%s/%s.xml', $this->moduleDir, $dirname, $langcode, $filename);
	}

	/**
	 * 翻訳ファイルを読み込み、翻訳を返す.
	 * 
	 * @param string $dirname
	 * @param string $langcode
	 * @param string $filename
	 * @return array
	 */
	public function loadTranslation($dirname, $langcode, $filename)
	{
		$sourceFile   = $this->getSourceFilename($dirname, $langcode, $filename);
		$compiledFile = $this->getCompiledFilename($dirname, $langcode, $filename);

		if ( file_exists($sourceFile) === false ) {
			return array(); // 翻訳ファイルが存在しない場合
		}

		if ( file_exists($compiledFile) === true ) {
			// コンパイル済みファイルがある場合
			if ( $this->_isSourceUpdated($sourceFile, $compiledFile) === false ) {
				// ソースファイルの更新がない場合
				$translation = $this->_loadCompiledTranslation($compiledFile);
				if ( $this->_isImportUpdated($translation['@import'], $langcode) === false ) {
					// インポート先もソースファイルの更新がない場合
					unset($translation['@import']);
					return $translation; // コンパイル済みを使う
				}
			}
		}

		$translation = array();

		$imports = $this->_getAllImports($dirname, $langcode, $filename);

		foreach ( $imports as $import ) {
			$importTranslations = $this->loadTranslation($import['dirname'], $langcode, $import['name']);
			$translation        = array_merge($translation, $importTranslations); // 後勝ち
		}

		$source = $this->loadSource($sourceFile);

		if ( $source === false ) {
			return $translation; // XMLパースエラーなどの場合
		}

		$translation = array_merge($translation, $source->getCatalogue()); // 後勝ち
		$this->_createCompileFile($compiledFile, $translation, $imports);
		return $translation;
	}

	/**
	 * インポートしている翻訳の情報を取得する.
	 * 
	 * @access protected
	 * @param string $dirname
	 * @param string $langcode
	 * @param string $name
	 * @return array
	 */
	protected function _getAllImports($dirname, $langcode, $name)
	{
		$allImports = array();

		$sourceFilename = $this->getSourceFilename($dirname, $langcode, $name);
		$source         = $this->loadSource($sourceFilename);

		if ( $source === false ) {
			return $source;
		}

		$imports = $source->getImports($dirname);

		foreach ( $imports as $k => $import ) {
			$_sourceFilename = $this->getSourceFilename($import['dirname'], $langcode, $import['name']);

			if ( file_exists($_sourceFilename) === false ) {
				continue;
			}

			$_imports = $this->_getAllImports($import['dirname'], $langcode, $import['name']);
			$allImports = array_merge($allImports, $_imports);
			$allImports[] = $import;
		}

		return $allImports;
	}

	/**
	 * ソースファイルの中身を返す.
	 * 
	 * @param string $filename
	 * @return Pengin_Language 失敗した場合FALSE
	 */
	public function loadSource($filename)
	{
		libxml_use_internal_errors(true);

		$xml = simplexml_load_file($filename, 'Pengin_Language', LIBXML_NOCDATA);

		if ( $xml == false ) {

			$message = '';

			foreach ( libxml_get_errors() as $error ) {
				$message .= trim($error->message).' in '.$error->file.PHP_EOL;
			}

			trigger_error($message, E_USER_WARNING);

			libxml_clear_errors();

			return false;
		}

		return $xml;
	}

	/**
	 * 翻訳をコンパイルする.
	 * 
	 * @param string $filename
	 * @param array $translation
	 * @param array $dependings
	 * @return integer 書きこまれたバイト数
	 */
	protected function _createCompileFile($filename, array $translation, array $imports = array())
	{
		if ( $this->charset !== 'utf-8' ) {
			mb_convert_variables($this->charset, 'UTF-8', $translation);
		}

		$translation['@import'] = $imports;

		$compiled = sprintf('<?php return %s;', var_export($translation, true));

		return file_put_contents($filename, $compiled);
	}

	/**
	 * ソースファイルに更新があったかどうかを返す.
	 * 
	 * @param string $sourceFilename
	 * @param string $compiledFilename
	 * @return bool
	 */
	protected function _isSourceUpdated($sourceFilename, $compiledFilename)
	{
		return ( filemtime($sourceFilename) > filemtime($compiledFilename) );
	}

	/**
	 * インポート先のソースファイルに更新があったかを返す.
	 * 
	 * @access protected
	 * @param array $imports
	 * @param string $langcode
	 * @return bool
	 */
	protected function _isImportUpdated(array $imports, $langcode)
	{
		foreach ( $imports as $import ) {
			$sourceFilename   = $this->getSourceFilename($import['dirname'], $langcode, $import['name']);
			$compiledFilename = $this->getCompiledFilename($import['dirname'], $langcode, $import['name']);

			if ( file_exists($sourceFilename) === false or file_exists($compiledFilename) === false ) {
				return true; // ファイルがない場合は更新があったとみなす
			}

			$isUpdated = $this->_isSourceUpdated($sourceFilename, $compiledFilename);

			if ( $isUpdated === true ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * コンパイル済み翻訳を返す.
	 * 
	 * @param string $filename
	 * @return array
	 */
	protected function _loadCompiledTranslation($filename)
	{
		$translation = require $filename;
		return $translation;
	}
}
