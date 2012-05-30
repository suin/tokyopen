<?php
/**
 * {header_doc}
 */

abstract class Pengin_Form
{
	protected $propertyNamespaces = array('Pengin_Form_Property_');
	protected $properties = array(); // フィールド
	protected $name   = null; // フォーム名
	protected $title  = null; // フォーム表示名
	protected $action = null; // 送信先
	protected $method = 'POST'; // 送信メソッド
	protected $errors = array(); // エラーメッセージ

	protected $transactionKeyName = 'trans'; // トランザクションキー名
	protected $transactionKey = null; // トランザクションキー値
	protected $transactionMax = 10; // 最大トランザクション数
	protected $checksXSRF = true; // XSRFをチェックするかどうか

	protected $preventsDoubleSubmission = true; // TRUE: 二重送信防止する
	protected $preventsEnterSubmission  = true; // TRUE: ENTERキーによる送信を防止する TODO>> 実装

	/**
	 * @static
	 */
	protected $validMethods = array('GET', 'POST');

	/**
	 * __construct function.
	 * 
	 * @access public
	 * @final
	 * @return void
	 * @note フォームの設定はsetUpForm、プロパティの設定はsetUpPropertiesメソッドで行ってください。
	 */
	public function __construct()
	{
		$this->name(md5(get_class($this)));
		$this->setUpForm();
		$this->setUpProperties();
	}

	/**
	 * フォームをセットアップするテンプレートメソッド
	 * 
	 * @access public
	 * @return void
	 */
	public function setUpForm()
	{
	}

	/**
	 * プロパティをセットアップするテンプレートメソッド
	 * 
	 * @access public
	 * @return void
	 */
	public function setUpProperties()
	{
	}

	/**
	 * 入力値をモデルに反映する.
	 * 
	 * @access public
	 * @param Pengin_Model_AbstractModel $model
	 * @return Pengin_Form
	 */
	public function updateModel(Pengin_Model_AbstractModel $model)
	{
		foreach ( $this->properties as $property ) {
			$name  = $property->getName();
			$value = $property->exportValue();
			$model->setVar($name, $value);
		}

		return $this;
	}

	/**
	 * モデルのデータを入力値に反映する.
	 * 
	 * @access public
	 * @param Pengin_Model_AbstractModel $model
	 * @return Pengin_Form
	 */
	public function useModelData(Pengin_Model_AbstractModel $model)
	{
		$values = $model->getVars();

		foreach ( $this->properties as $property ) {
			$name = $property->getName();

			if ( array_key_exists($name, $values) === true ) {
				$property->importValue($values[$name]);
			}
		}

		return $this;
	}

	/**
	 * フォームの名前をセットする
	 * 
	 * @param string $name
	 * @return Pengin_Form
	 */
	public function name($name)
	{
		$this->name = $name;
		return $this;
	}

	/**
	 * フォームの名前を取得する
	 * 
	 * @return string タイトル
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * フォームのタイトルをセットする
	 * 
	 * @param string $title
	 * @return Pengin_Form
	 */
	public function title($title)
	{
		$this->title = $title;
		return $this;
	}

	/**
	 * フォームのタイトルを取得する
	 * 
	 * @return string タイトル
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * フォームのタイトルを翻訳して返す
	 * 
	 * @return string タイトル
	 */
	public function getTitleLocal()
	{
		return t($this->title);
	}

	/**
	 * プロパティを追加する
	 * 
	 * @access public
	 * @param string $name プロパティ名
	 * @param string $type プロパティタイプ
	 * @return Pengin_Form_Property
	 */
	public function add($name, $type)
	{
		$className = $this->_getPropertyClass($type);
		$this->properties[$name] = new $className();
		$this->properties[$name]->name($name);
		return $this->properties[$name];
	}

	/**
	 * プロパティを取得する.
	 * 
	 * @access public
	 * @param string $name プロパティ名
	 * @return Pengin_Form_Property
	 */
	public function property($name)
	{
		return $this->properties[$name];
	}

	/**
	 * プロパティがあるか.
	 * 
	 * @access public
	 * @param string $name プロパティ名
	 * @return bool
	 */
	public function hasProperty($name)
	{
		return array_key_exists($name, $this->properties);
	}

	/**
	 * プロパティを削除する.
	 * 
	 * @access public
	 * @param string $name プロパティ名
	 * @return Pengin_Form
	 */
	public function remove($name)
	{
		unset($this->properties[$name]);
		return $this;
	}

	/**
	 * プロパティをすべて返すする.
	 * 
	 * @access public
	 * @return array プロパティの配列
	 */
	public function getProperties()
	{
		return $this->properties;
	}

	/**
	 * REQUEST_METHODをセットする.
	 * 
	 * @access public
	 * @param string $method Pengin_Form::$validMethodsを参照
	 * @return Pengin_Form
	 */
	public function setMethod($method)
	{
		if ( $this->isValidMethod($method) === false ) {
			throw new InvalidArgumentException('Unexpected method: '.$method);
		}

		$this->method = $method;
		return $this;
	}

	/**
	 * REQUEST_METHODを返す.
	 * 
	 * @access public
	 * @return string REQUEST_METHOD
	 */
	public function getMethod()
	{
		return $this->method;
	}

	/**
	 * 定義されたREQUEST_METHODか確認する.
	 * 
	 * @access public
	 * @param string $method REQUEST_METHOD
	 * @return bool
	 */
	public function isValidMethod($method)
	{
		return in_array($method, $this->validMethods);
	}

	/**
	 * 新しいフォームのトランザクションを開始する.
	 * 
	 * @access public
	 * @return Pengin_Form
	 */
	public function beginTransaction()
	{
		$name = $this->getName();
		$transactionKey = $this->transactionKeyName.uniqid();

		if ( array_key_exists($name, $_SESSION) === false ) {
			$_SESSION[$name] = array();
		}

		if ( count($_SESSION[$name]) >= $this->transactionMax ) {
			$slicing = $this->transactionMax - 1;
			$_SESSION[$name] = array_slice($_SESSION[$name], $slicing * -1, $slicing); // 古いのから消す
		}

		$_SESSION[$name][$transactionKey] = array();

		$this->transactionKey = $transactionKey;

		return $this;
	}

	/**
	 * フォームのトランザクションを終了する.
	 * 
	 * @access public
	 * @return Pengin_Form
	 */
	public function endTransaction()
	{
		if ( isset($_SESSION[$this->name][$this->transactionKey]) === true ) {
			unset($_SESSION[$this->name][$this->transactionKey]);
		}

		return $this;
	}

	/**
	 * トランザクションキーを返す.
	 * 
	 * @access public
	 * @return string トランザクションキー
	 */
	public function getTransactionKey()
	{
		return $this->transactionKey;
	}

	/**
	 * トランザクションキーの名前を返す.
	 * 
	 * @access public
	 * @return string トランザクションキーの名前
	 */
	public function getTransactionKeyName()
	{
		return $this->transactionKeyName;
	}

	/**
	 * XSRF対策を有効にする.
	 * 
	 * @access public
	 * @return Pengin_Form
	 */
	public function enableXSRFCheck()
	{
		$this->checksXSRF = true;
		return $this;
	}

	/**
	 * XSRF対策を無効にする.
	 * 
	 * @access public
	 * @return Pengin_Form
	 */
	public function disableXSRFCheck()
	{
		$this->checksXSRF = false;
		return $this;
	}

	/**
	 * XSRFのチェックするかを返す.
	 * 
	 * @access public
	 * @return bool する設定の場合TRUE、でなければFALSE
	 */
	public function checksXSRF()
	{
		return $this->checksXSRF;
	}

	/**
	 * 送信先をセットする.
	 * 
	 * @access public
	 * @param string $uri 送信先のURI
	 * @return void
	 */
	public function action($uri)
	{
		$this->action = $uri;
		return $this;
	}

	/**
	 * 送信先を返す.
	 * 
	 * @access public
	 * @return string 送信先
	 */
	public function getAction()
	{
		return $this->action;
	}

	/**
	 * 入力値を取得する.
	 * 
	 * @access public
	 * @return Pengin_Form
	 */
	public function fetchInput()
	{
		$this->_fetchTransactionKey();
		$input = $this->_getMethodValues();

		foreach ( $this->properties as $property ) {
			$this->_fetchInput($property, $input);
		}

		return $this;
	}

	/**
	 * 入力値を返す.
	 * 
	 * @access public
	 * @return array 入力値
	 */
	public function getInput()
	{
		$input = array();

		foreach ( $this->properties as $property ) {
			$name = $property->getName();
			$input[$name] = $property->getValue();
		}

		return $input;
	}

	/**
	 * 入力値を一時的にセッションに保管する.
	 * 
	 * @access public
	 * @return Pengin_Form
	 * @throws Pengin_Form_InvalidTransactionException
	 */
	public function preserveInput()
	{
		$name           = $this->name;
		$transactionKey = $this->transactionKey;

		if ( isset($_SESSION[$name][$transactionKey]) === false ) {
			throw new Pengin_Form_InvalidTransactionException("Transaction key not found: ".$name.": ".$transactionKey);
		}

		$_SESSION[$name][$transactionKey] = $this->getInput();
	}

	/**
	 * 入力値をセッションから復帰させる.
	 * 
	 * @access public
	 * @return Pengin_Form
	 * @throws Pengin_Form_InvalidTransactionException
	 */
	public function usePreservedInput()
	{
		$this->_fetchTransactionKey();

		$name           = $this->name;
		$transactionKey = $this->transactionKey;

		if ( isset($_SESSION[$name][$transactionKey]) === false ) {
			throw new Pengin_Form_InvalidTransactionException("Transaction key not found: ".$name.": ".$transactionKey);
		}

		$input = $_SESSION[$name][$transactionKey];

		foreach ( $this->properties as $property ) {
			$this->_fetchInput($property, $input);
		}
	}

	/**
	 * バリデーションを行う.
	 * 
	 * @access public
	 * @return Pengin_Form
	 */
	public function validate()
	{
		if ( $this->_detectXSRF() === true ) {
			return $this;
		}

		foreach ( $this->properties as $property ) {
			$property->validate();
			$this->_validateCustom($property);
			$this->errors = array_merge($this->errors, $property->getErrors());
		}

		return $this;
	}

	/**
	 * エラーメッセージを追加する.
	 * 
	 * @access public
	 * @param string $message
	 * @return Pengin_Form
	 */
	public function addError($message)
	{
		$this->errors[] = $message;
		return $this;
	}

	/**
	 * エラーメッセージを複数追加する.
	 * 
	 * @access public
	 * @param array $messages
	 * @return Pengin_Form
	 */
	public function addErrors(array $messages)
	{
		foreach ( $messages as $message ) {
			$this->addError($message);
		}

		return $this;
	}

	/**
	 * エラーメッセージを返す.
	 * 
	 * @access public
	 * @return array エラーメッセージ
	 */
	public function getErrors()
	{
		return $this->errors;
	}

	/**
	 * エラーメッセージを消去する.
	 * 
	 * @access public
	 * @return Pengin_Form
	 */
	public function clearErrors()
	{
		$this->errors = array();
		return $this;
	}

	/**
	 * エラーメッセージがあるかを返す.
	 * 
	 * @access public
	 * @return bool
	 */
	public function hasError()
	{
		return ( count($this->errors) > 0 );
	}

	/**
	 * Pengin_Formを配列にエクスポートする
	 * 
	 * @access public
	 * @param bool $propertyAsArray (default: false) プロパティも配列にするか
	 * @return array
	 */
	public function toArray($propertyAsArray = false)
	{
		$form = $this->_toArray();
		$form['properties'] = array();

		foreach ( $this->properties as $property ) {
			$name = $property->getName();

			if ( $propertyAsArray === true ) {
				$form['properties'][$name] = $property->toArray();
			} else {
				$form['properties'][$name] = $property;
			}
		}

		return $form;
	}

	/**
	 * 二重送信を防止するかどうかを返す.
	 * 
	 * @access public
	 * @return bool
	 */
	public function preventsDoubleSubmission()
	{
		return $this->preventsDoubleSubmission;
	}

	/**
	 * ENTERキー押下による送信を禁止するかを返す.
	 * 
	 * @access public
	 * @return bool
	 */
	public function preventsEnterSubmission()
	{
		return $this->preventsEnterSubmission;
	}

	protected function _toArray()
	{
		return array(
			'name'   => $this->name,
			'title'  => $this->title,
			'action' => $this->action,
			'method' => $this->method,
			'errors' => $this->errors,
			'transaction_key' => $this->transactionKey,
			'transaction_key_name' => $this->transactionKeyName,
		);
	}

	protected function _getPropertyClass($type)
	{
		foreach ( $this->propertyNamespaces as $namespace ) {
			$className = $namespace.$type;

			if ( class_exists($className) === true ) {
				return $className;
			}
		}

		throw new RuntimeException('Property class not found: '.$type);
	}

	protected function _getMethodValues()
	{
		switch ( $this->method )
		{
			case 'POST':
				$value = $_POST;
				break;
			case 'GET' :
				$value = $_GET;
				break;
			default:
				throw new InvalidArgumentException('Unexpected method: '.$this->method);
				break;
		}

		return $value;
	}

	protected function _fetchInput(Pengin_Form_Property $property, array $input)
	{
		$name = $property->getName();

		if ( array_key_exists($name, $input) === false ) {
			$input[$name] = null;
		}

		$property->value($input[$name]);
	}

	protected function _validateCustom(Pengin_Form_Property $property)
	{
		$name = $property->getName();
		$name = Pengin_Inflector::pascalize($name);
		$customMethod = 'validate'.$name;

		if ( method_exists($this, $customMethod) === true ) {
			$this->$customMethod($property);
		}
	}

	protected function _fetchTransactionKey()
	{
		$this->transactionKey = Pengin::getInstance()->post($this->transactionKeyName);
	}

	/**
	 * XSRF攻撃を検出する.
	 * 
	 * @access protected
	 * @return bool 検出した可能性があるときTRUE、それ以外FALSE
	 */
	protected function _detectXSRF()
	{
		if ( $this->checksXSRF() === false ) {
			return false;
		}

		$name = $this->getName();

		if ( isset($_SESSION[$name][$this->transactionKey]) === true ) {
			return false;
		}

		$this->addError(t("Time out or unexpected screen transition."));
		$this->beginTransaction(); // 新しいトークンを作る

		return true;
	}
}
