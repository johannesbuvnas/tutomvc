<?php

	namespace TutoMVC\Form\Formatter\Bootstrap5;

	use TutoMVC\Form\Formatter\IFormElementFormatter;
	use TutoMVC\Form\FormElement;
	use TutoMVC\Form\Group\FissileFormGroup;
	use tutomvc\wp\metabox\MetaBox;
	use tutomvc\wp\metabox\UserMetaBox;
	use tutomvc\wp\settings\Settings;

	class BS5FissileFormmatter implements IFormElementFormatter
	{

		/**
		 * @param FissileFormGroup $el
		 *
		 * @return string
		 */
		public function formatOutput( FormElement $el )
		{
			$classes = "list-group fissile-form-group";
			if ( $el instanceof MetaBox ) $classes .= " tutomvc-metabox";
			if ( $el instanceof UserMetaBox ) $classes .= " tutomvc-user_metabox";
			if ( $el instanceof Settings ) $classes .= " tutomvc-settings";
			$output = '<div class="bs5"><ul class="' . $classes . '" id="' . $el->getID() . '">';
			if ( !$el->count() )
			{
				$output .= "<input type='hidden' name='" . $el->getName() . "' value='0' />";
			}
			$output .= $this->formatHeaderOutput( $el );
			$output .= $this->formatFormElementOutput( $el );
			if ( !$el->hasReachedMaxFissions() ) $output .= $el->formatFooterOutput();
			$output .= '</ul></div>';

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
							<input name="' . $el->formatRootElementName( $index ) . '[' . FissileFormGroup::BUTTON_NAME_DELETE . ']" class="fissile-form-group-nuke" type="checkbox" style="margin:0 6px 0 0;"> <span class="glyphicon glyphicon-remove"></span>
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
			$output = $errorOutput = $this->formatErrorMessageOutput( $el );
//			$output      = '
//					<li class="list-group-item">
//						<h2>
//							' . $el->getLabel() . '
//							<small class="help-block">
//								' . $el->getDescription() . '
//							</small>
//							' . $errorOutput . '
//						</h2>
//					</li>
//			';
			if ( !$el->hasReachedMaxFissions() ) $output .= $this->getTopNavOutput( $el );

			return $output;
		}

		protected function getTopNavOutput( FissileFormGroup $el )
		{
			$output = '
					<li class="list-group-item fissile-form-group-top-nav" style="text-align: center">
					    <label class="btn btn-default">
							<input name="' . $el->formatRootElementName( FissileFormGroup::BUTTON_NAME_ADD_BEFORE ) . '" class="fissile-form-group-nuke" type="checkbox" style="margin:0 6px 0 0;"> <span class="glyphicon glyphicon-plus"></span> 1
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
							<input name="' . $el->formatRootElementName( FissileFormGroup::BUTTON_NAME_ADD_AFTER ) . '" class="fissile-form-group-nuke" type="checkbox" style="margin:0 6px 0 0;"> <span class="glyphicon glyphicon-plus"></span> 1
						</label>
					</li>';

			return $output;
		}

		public function formatErrorMessageOutput( FormElement $el )
		{
			$errorMsg = $el->getErrorMessage();
			if ( is_string( $errorMsg ) )
			{
				return '<div class="alert alert-danger" role="alert">' . $errorMsg . '</div>';
			}

			return '';
		}

		/**
		 * @param FissileFormGroup $el
		 *
		 * @return string
		 */
		public function formatFormElementOutput( FormElement $el )
		{
			$output = '';
			for ( $i = 0; $i < $el->count(); $i ++ )
			{
				$output .= $this->formatFissionOutput( $el, $i );
			}

			return $output;
		}
	}
