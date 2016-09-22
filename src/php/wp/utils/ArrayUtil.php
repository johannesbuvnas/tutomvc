<?php
	namespace tutomvc\wp\utils;

	class ArrayUtil
	{
		/**
		 *    Sort elements by string length.
		 */
		public static function usortByStringLength( $a, $b )
		{
			return strlen( $b ) - strlen( $a );
		}

		/**
		 *    Sort elements by string folder structure, the deepest last.
		 */
		public static function usortByFolderStructure( $a, $b )
		{
			$explodeA = explode( "/", $a );
			$explodeB = explode( "/", $b );

			return $explodeA > $explodeB ? TRUE : FALSE;
		}

		/**
		 *    Returns array without the forbidden elements.
		 */
		public static function removeForbiddenElements( $array, $forbiddenElements )
		{
			$i = 0;

			if ( !empty($array) )
			{
				foreach ( $array as $element )
				{
					$indexOf = array_search( $element, $forbiddenElements );

					if ( is_numeric( $indexOf ) )
					{
						unset($array[ $i ]);
					}

					$i ++;
				}
			}

			return $array;
		}
	}