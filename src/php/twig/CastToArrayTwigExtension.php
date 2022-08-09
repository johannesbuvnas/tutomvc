<?php

	namespace TutoMVC\Twig;

	use phpDocumentor\Descriptor\ClassDescriptor;
	use Twig_SimpleFunction;

	class CastToArrayTwigExtension extends \Twig_Extension
	{
		const NAME = 'CastToArrayTwigExtension';

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
				new Twig_SimpleFunction( 'castToArray', array($this, 'castToArray') ),
				new Twig_SimpleFunction( 'getFileName', array($this, 'getFileName') )
			);
		}

		/**
		 * @param ClassDescriptor $stdClassObject
		 *
		 * @return array
		 */
		public function castToArray( $stdClassObject )
		{
			$response = array();
			foreach ( $stdClassObject as $key => $value )
			{
				$response[] = array($key, $value);

			}

			var_dump( $stdClassObject->getPath() );

			return $response;
		}

		/**
		 * @param ClassDescriptor $stdClassObject
		 *
		 * @return string
		 */
		public function getFileName( $stdClassObject )
		{

			return get_class( $stdClassObject );
		}
	}
