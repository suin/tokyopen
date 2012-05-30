<?php
/**
 * フォーム
 */

class Pengin_View_Smarty_Plugin_Form implements Pengin_View_Smarty_PluginInterface,
                                                Pengin_View_Smarty_PluginBlockInterface
{
	protected static $parameters = array(
		'form' => null,
	);

	public static function getType()
	{
		return self::TYPE_BLOCK;
	}

	public static function getName()
	{
		return 'form';
	}

	public static function run(array $parameters, $content, &$smarty, &$repeat)
	{
		if ( $repeat === true ) {
			return; // 閉じタグでのみ実行
		}

		$form = $parameters['form'];

		if ( self::_isFormObject($form) === false ) {
			return false;
		}

		$defaultParameters = self::$parameters;
		$defaultParameters['id'] = $form->getName();
		$parameters = array_merge($defaultParameters, $parameters);

		unset($parameters['form']);

		$attributes = Pengin_TextFilter::buildAttributeString($parameters);

		if ( $form->checksXSRF() === true ) {
			$key      = $form->getTransactionKey();
			$keyName  = $form->getTransactionKeyName();
			$tokenTag = '<input type="hidden" name="%s" value="%s" />';
			$tokenTag = sprintf($tokenTag, $keyName, $key);
			$content  = $tokenTag.$content;
		}

		if ( $form->preventsDoubleSubmission() === true ) {
			$content .= self::_getJavaScriptPreventingDoubleSubmission($parameters);
		}

		$formTag = '<form action="%s" method="%s"%s>%s</form>';
		$formTag = sprintf($formTag, $form->getAction(), $form->getMethod(), $attributes, $content);

		return $formTag;
	}

	protected static function _isFormObject($object)
	{
		if ( is_object($object) === false ) {
			return false;
		}

		if ( is_subclass_of($object, 'Pengin_Form') === false ) {
			return false;
		}

		return true;
	}

	protected static function _getJavaScriptPreventingDoubleSubmission($parameters)
	{
		$id = $parameters['id'];
		ob_start();
		?>
		<script><!--
		(function(){
			var submitted = false;
			var form;

			var init = function(){
				form = document.getElementById('<?php echo $id ?>');
				addEvent(form, 'submit', preventDoubleSubmission);
				addEventToSubmitButtons();
			};

			var addEvent = function(element, type, event){
				if(element.addEventListener) {
					element.addEventListener(type, event, false);
				} else if(element.attachEvent) {
					element.attachEvent('on'+type, event);
				} else {
					element['on'+type] = event;
				}
			};

			var addEventToSubmitButtons = function(){
				for ( var i = 0; i < form.length; i++ ) {
					var elem = form.elements[i];
					if ( elem.type == 'submit' ) {
						addEvent(elem, 'click', setHideValude);
					}
				}
			};

			var preventDoubleSubmission = function(event){
				if ( submitted == true ) {
					if ( event.preventDefault ) {
						event.preventDefault();
					} else {
						event.returnValue = false;
					}
				} else {
					submitted = true;
					disableButtons();
				}
			};

			var disableButtons = function(){
				for ( var i = 0; i < form.length; i++ ) {
					var elem = form.elements[i];
					if ( elem.type == 'submit' ) {
						disalbeButton(elem);
					}
				}
			};

			var disalbeButton = function(button){
				button.disabled = true;
			};

			var setHideValude = function(event){
				var button;
				if ( event.target ) {
					button = event.target
				} else if ( window.event.srcElement ) {
					button = window.event.srcElement;
				}
				var elem = document.createElement('input');
				elem.type  = 'hidden';
				elem.name  = button.name;
				elem.value = button.value;
				button.form.appendChild(elem);
			};

			init();
		})();
		// -->
		</script>
		<?php
		return ob_get_clean();
	}
}
