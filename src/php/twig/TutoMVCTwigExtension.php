<?php

	namespace TutoMVC\Twig;

	use phpDocumentor\Descriptor\ClassDescriptor;
	use phpDocumentor\Descriptor\MethodDescriptor;
	use TutoMVC\Form\FormElement;
	use Twig_SimpleFunction;

	class TutoMVCTwigExtension extends \Twig_Extension
	{
		const NAME = 'TutoMVCTwigExtension';

		protected $_classes = array();

		/**
		 * {@inheritdoc}
		 */
		public function getName()
		{
			return self::NAME;
		}

		/**
		 * {@inheritdoc}
		 */
		public function getFunctions()
		{
			return array(
				new Twig_SimpleFunction( 'jsonEncode', array($this, 'jsonEncode') ),
				new Twig_SimpleFunction( 'outputJSON', array($this, 'outputJSON') ),
				new Twig_SimpleFunction( 'markdownAnchorName', array($this, 'markdownAnchorName') ),
				new Twig_SimpleFunction( 'publicMethods', array($this, 'getPublicMethods') )
			);
		}

		public function markdownAnchorName( $methodName )
		{
			return "{#" . FormElement::sanitizeID( $methodName ) . "}";
		}

		/**
		 * @param ClassDescriptor $class
		 *
		 * @return MethodDescriptor[]
		 */
		public function getPublicMethods( $class )
		{
			if ( !$class instanceof ClassDescriptor )
			{
				return [];
			}

			$methods = $class->getMagicMethods()
			                 ->merge( $class->getInheritedMethods() )
			                 ->merge( $class->getMethods() );

			return array_filter(
				$methods->getAll(),
				function ( MethodDescriptor $method ) {
					return $method->getVisibility() === 'public';
				}
			);
		}

		/**
		 * @param ClassDescriptor $class
		 *
		 * @return mixed|string|void
		 */
		public function jsonEncode( $class )
		{
			$json                = array();
			$package             = substr( str_replace( "\\", ".", $class->getNamespace()->getFullyQualifiedStructuralElementName() ), 1 );
			$json[ 'name' ]      = $class->getName();
			$json[ 'fullName' ]  = $class->getFullyQualifiedStructuralElementName();
			$json[ 'package' ]   = $package;
			$json[ 'path' ]      = $class->getPath();
			$json[ 'namespace' ] = $class->getNamespace()->getFullyQualifiedStructuralElementName();
			$json[ 'parent' ]    = $class->getParent() && !is_string( $class->getParent() ) ? $class->getParent()->getFullyQualifiedStructuralElementName() : "";
			$json[ 'methods' ]   = array();

			foreach ( $this->getPublicMethods( $class ) as $method )
			{
				$json[ 'methods' ][] = FormElement::sanitizeID( $method->getName() );
			}

			$markdownFilename   = str_replace( "\\", ".", $class->getFullyQualifiedStructuralElementName() ) . ".html";
			$markdownFilename   = substr( $markdownFilename, 1 );
			$json[ 'markdown' ] = file_get_contents( "docs/classes/$markdownFilename" );

			$this->_classes[] = $json;
		}

		public function outputJSON()
		{
			echo json_encode( $this->_classes );
		}
	}
