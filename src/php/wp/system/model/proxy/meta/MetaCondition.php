<?php
namespace tutomvc\wp;

class MetaCondition
{
	/* CONSTANTS */
	const CALLBACK_SHOW = "show";
	const CALLBACK_HIDE = "hide";


	/* VARS */
	public $metaBoxName;
	public $metaFieldName;
	public $value;
	public $callbackOnMatch;
	public $callbackOnElse;

	function __construct( $metaBoxName, $metaFieldName, $value, $callbackOnMatch = NULL, $callbackOnElse = NULL )
	{
		$this->metaBoxName = $metaBoxName;
		$this->metaFieldName = $metaFieldName;
		$this->value = $value;
		$this->callbackOnMatch = $callbackOnMatch;
		$this->callbackOnElse = $callbackOnElse;
	}

	function getJavaScriptValidationMethod()
	{
		return "
			function( metaBoxName, metaFieldName, value )
			{
				if(metaBoxName == '".$this->metaBoxName."' && metaFieldName == '".$this->metaFieldName."')
				{
					if( value == '".$this->value."' )
					{
						return true;
					}
					else
					{
						return false;
					}
				}

				return undefined;
			}
		";
	}

	function toArray()
	{
		return array
		(
			"jsValidation" => trim(preg_replace('/[\s\t\n\r\s]+/', ' ', $this->getJavaScriptValidationMethod())),
			"metaBoxName" => $this->metaBoxName,
			"metaFieldName" => $this->metaFieldName,
			"value" => $this->value,
			"onMatch" => $this->callbackOnMatch,
			"onElse" => $this->callbackOnElse
		);
	}
}