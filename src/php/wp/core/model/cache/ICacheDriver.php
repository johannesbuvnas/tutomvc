<?php
	namespace tutomvc\wp\core\model\cache;

	use tutomvc\wp\core\facade\Facade;

	interface ICacheDriver
	{
		/**
		 * @param string|int $key
		 * @param mixed $data
		 * @param string $group
		 * @param int $expire
		 *
		 * @return boolean
		 */
		public function set( $key, $data, $group = '', $expire = 0 );

		/**
		 * @param string|int $key
		 * @param string $group
		 *
		 * @return mixed
		 */
		public function get( $key, $group = '' );

		/**
		 * @param string $group
		 *
		 * @return mixed
		 */
		public function getGroup( $group = '' );

		/**
		 * @param string|int $key
		 * @param string $group
		 *
		 * @return boolean
		 */
		public function delete( $key, $group = '' );

		/**
		 * @param string $group
		 *
		 * @return boolean
		 */
		public function deleteGroup( $group = '' );

		/**
		 * @param string $facadeKey
		 *
		 */
		public function setFacadeKey( $facadeKey );

		/**
		 * @return string
		 * @internal param string $facadeKey
		 *
		 */
		public function getFacadeKey();

		/**
		 * @return Facade
		 */
		public function getFacade();
	}