<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016
 * @package Slim
 * @subpackage Command
 */

namespace Aimeos\Slim\Command;


/**
 * Aimeos base command class
 *
 * @package Slim
 * @subpackage Command
 */
class Base
{
	/**
	 * Returns the command options given by the user
	 *
	 * @param array &$params List of parameters
	 * @return array Associative list of option name and value(s)
	 */
	protected static function getOptions( array &$params ) : array
	{
		$options = array();

		foreach( $params as $key => $option )
		{
			if( $option === '--help' ) {
				throw new Exception();
			}

			if( strncmp( $option, '--', 2 ) === 0 && ( $pos = strpos( $option, '=', 2 ) ) !== false )
			{
				$name = substr( $option, 2, $pos - 2 );

				if( isset( $options[$name] ) )
				{
					$options[$name] = (array) $options[$name];
					$options[$name][] = substr( $option, $pos + 1 );
				}
				else
				{
					$options[$name] = substr( $option, $pos + 1 );
				}

				unset( $params[$key] );
			}
		}

		return $options;
	}


	/**
	 * Returns the locale site items for the given site code string
	 *
	 * @param \Aimeos\MShop\Context\Item\Iface $ctx Context object
	 * @param string|null $sites List of site codes separated by a space
	 */
	protected static function getSiteItems( \Aimeos\MShop\Context\Item\Iface $ctx, string $sites = null ) : array
	{
		$manager = \Aimeos\MShop::create( $ctx, 'locale/site' );
		$search = $manager->createSearch();

		if( is_scalar( $sites ) && $sites != '' ) {
			$sites = explode( ' ', $sites );
		}

		if( !empty( $sites ) ) {
			$search->setConditions( $search->compare( '==', 'locale.site.code', $sites ) );
		}

		return $manager->searchItems( $search );
	}
}