<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016
 * @package Slim
 * @subpackage Command
 */

namespace Aimeos\Slim\Command;


/**
 * Common interface for all commands
 *
 * @package Slim
 * @subpackage Command
 */
interface Iface
{
	/**
	 * Executes the command
	 *
	 * @param array $argv Associative array from $_SERVER['argv']
	 */
	public static function run( array $argv );

	/**
	 * Returns the command usage and options
	 *
	 * @return string Command usage and options
	 */
	public static function usage();
}