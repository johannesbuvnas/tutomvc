<?php

	namespace TutoMVC\Utils;

	class ArrayUtil
	{
		public static function usortByDirectoryDepth( $a, $b )
		{
			$explodeA = explode( DIRECTORY_SEPARATOR, $a );
			$explodeB = explode( DIRECTORY_SEPARATOR, $b );

			return $explodeA > $explodeB ? TRUE : FALSE;
		}
	}
