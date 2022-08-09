<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 2018-04-26
	 * Time: 15:01
	 */

	namespace TutoMVC\Form\Formatter;

	use TutoMVC\Form\FormElement;

	interface IFormElementFormatter
	{
		/**
		 * @param FormElement $el
		 *
		 * @return string
		 */
		public function formatOutput( FormElement $el );

		/**
		 * @param FormElement $el
		 *
		 * @return string
		 */
		public function formatHeaderOutput( FormElement $el );

		/**
		 * @param FormElement $el
		 *
		 * @return string
		 */
		public function formatFooterOutput( FormElement $el );

		/**
		 * @param FormElement $el
		 *
		 * @return string
		 */
		public function formatErrorMessageOutput( FormElement $el );

		/**
		 * @param FormElement $el
		 *
		 * @return string
		 */
		public function formatFormElementOutput( FormElement $el );
	}
