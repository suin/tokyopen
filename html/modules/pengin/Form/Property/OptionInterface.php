<?php
/**
 * {header_doc}
 */

interface Pengin_Form_Property_OptionInterface
{
	/**
	 * 選択肢をセットする.
	 * 
	 * @access public
	 * @param array $options 選択肢
	 * @return $this
	 */
	public function options(array $options);

	/**
	 * 選択肢を返す.
	 * 
	 * @access public
	 * @return array 選択肢
	 */
	public function getOptions();

	/**
	 * 選択肢を翻訳して返す.
	 * 
	 * @access public
	 * @return array 選択肢
	 */
	public function getOptionsLocal();

	/**
	 * 選択肢についてのバリデーション.
	 * 
	 * @access public
	 * @return $this
	 */
	public function validateOptions();
}
