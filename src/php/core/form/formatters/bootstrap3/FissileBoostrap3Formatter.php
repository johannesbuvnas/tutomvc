<?php

	namespace tutomvc\core\form\formatters\bootstrap3;

	use tutomvc\core\form\formatters\IFormElementFormatter;
	use tutomvc\core\form\FormElement;
	use tutomvc\core\form\groups\FissileFormGroup;

	class FissileBoostrap3Formatter implements IFormElementFormatter
	{

		/**
		 * @param FissileFormGroup $el
		 *
		 * @return string
		 */
		public function formatOutput( FormElement $el )
		{
			$output = '<ul class="list-group fissile-form-group" id="' . $el->getID() . '">';
			$output .= $this->formatHeaderOutput( $el );
			for ( $i = 0; $i < $el->count(); $i ++ )
			{
				$output .= $this->formatFissionOutput( $el, $i );
			}
			if ( !$el->hasReachedMaxFissions() ) $output .= $el->formatFooterOutput();
			$output .= '</ul>';

			$output .= '
			<script type="text/javascript">
			jQuery(document).ready(function()
			{
				jQuery("#' . $el->getID() . '").sortable({
					items: ".fissile-form-group-item",
					opacity: 0.5,
					handle: ".fissile-form-group-item-header",
					tolerance: "pointer",
					update: function( event, ui ) 
					{
						var i = 0;
						jQuery(this).find(".fissile-form-group-item").each(function()
						{
							jQuery(this).find("input.fissile-form-group-index").val(i);
							i++;
						});
					}
				});
			});
			</script>
			';

			return $output;
		}

		protected function formatFissionOutput( FissileFormGroup $el, $index = 0 )
		{
			$el->cacheFission();
			$el->switchToFission( $index );
			$output = '<div class="list-group-item ui-sortable fissile-form-group-item">';
			$output .= '<input type="hidden" value="' . $index . '" class="fissile-form-group-index" name="' . $el->formatRootElementName( $index ) . '[' . FissileFormGroup::INPUT_INDEX . ']" >';
			if ( $el->getMinimumFissions() == 1 && $el->getMaximumFissions() == 1 )
			{
				$output .= $el->formatFormElementOutput();
			}
			else
			{
				$output .= '
				<li class="list-group-item list-group-item-info fissile-form-group-item-header" style="text-align: right;cursor:move;">
					' . $this->formatFissionIndexSelectorOutput( $el, $index ) . '
				</li>
				<li class="list-group-item fissile-form-group-item-body">
					' . $el->formatFormElementOutput() . '
				</li>';
			}
			$output .= '</div>';

			$el->switchToCachedFission();

			return $output;
		}

		protected function formatFissionIndexSelectorOutput( FissileFormGroup $el, $index )
		{
			$output = "";
			$output .= '<label class="btn btn-danger btn-sm">
							<input name="' . $el->formatRootElementName( $index ) . '[' . FissileFormGroup::BUTTON_NAME_DELETE . ']" type="checkbox" style="margin:0 6px 0 0;"> <span class="glyphicon glyphicon-remove"></span>
						</label>';

			return $output;
		}

		/**
		 * @param FissileFormGroup $el
		 *
		 * @return string
		 */
		public function formatHeaderOutput( FormElement $el )
		{
			$output = '
					<li class="list-group-item">
						<h2>
							' . $el->getLabel() . '
							<small class="help-block">
								' . $el->getDescription() . '
							</small>
						</h2>
					</li>
			';
			if ( !$el->hasReachedMaxFissions() ) $output .= $this->getTopNavOutput( $el );

			return $output;
		}

		protected function getTopNavOutput( FissileFormGroup $el )
		{
			$output = '
					<li class="list-group-item fissile-form-group-top-nav" style="text-align: center">
					    <label class="btn btn-default">
							<input name="' . $el->formatRootElementName( FissileFormGroup::BUTTON_NAME_ADD_BEFORE ) . '" type="checkbox" style="margin:0 6px 0 0;"> <span class="glyphicon glyphicon-plus"></span> 1
						</label>
					</li>';

			return $output;
		}

		/**
		 * @param FissileFormGroup $el
		 *
		 * @return string
		 */
		public function formatFooterOutput( FormElement $el )
		{
			$output = '
					<li class="list-group-item fissile-form-group-footer" style="text-align: center">
						<label class="btn btn-default">
							<input name="' . $el->formatRootElementName( FissileFormGroup::BUTTON_NAME_ADD_AFTER ) . '" type="checkbox" style="margin:0 6px 0 0;"> <span class="glyphicon glyphicon-plus"></span> 1
						</label>
					</li>';

			return $output;
		}

		public function formatErrorMessageOutput( FormElement $el )
		{
			return '';
		}

		public function formatFormElementOutput( FormElement $el )
		{
			return '';
		}
	}