<?php

/**
 * @license MIT, http://opensource.org/licenses/MIT
 * @copyright Aimeos (aimeos.org), 2015-2016
 * @package laravel
 * @subpackage Base
 */

namespace Aimeos\Slim\Base;

use Psr\Container\ContainerInterface;

/**
 * Service providing the authentication object
 *
 * @package slim
 * @subpackage Base
 */
class Auth
{
	private $container;
	private $cache = [];

	/**
	 * Initializes the object
	 *
	 * @param ContainerInterface $container Dependency container
	 */
	public function __construct( ContainerInterface $container)
	{
		$this->container = $container;
	}

    /**
     * Web login
     *
     * @param \Aimeos\MShop\Context\Item\Iface $context Context object
     * @param string $code User code (email)
     * @param string $password User password
     * @param bool $isAdmin Manager signin or not
     * @return array Signin execution result
     *  - error_code : 0 | -1 | -2 | -3 | -4 | -5
     *  - error_msg : [0] no error | [-1] no user | [-2] unavailable user
     *                [-3] no groups | [-4] wrong password | [-5] unknown error
     */
    public function webLogin(\Aimeos\MShop\Context\Item\Iface $context,
                          string $code, string $password, bool $isAdmin = false):array
    {
        try {

            // user or customer table
            if ($isAdmin) {
                $context->getConfig()->set('mshop/customer/manager/name', 'Laravel');
            } else {
                $context->getConfig()->set('mshop/customer/manager/name', 'Standard');
            }

            // db logic
            $manager = \Aimeos\MShop::create( $context, 'customer' );

            $search = $manager->createSearch();
            $search->setConditions( $search->compare( '==', 'customer.code', $code ) );
            $item = $manager->searchItems($search);
            if ($item->count() === 0) {
                return array('error_code' => -1, 'error_msg' => 'no user');
            }

            $value = $item->values()[0];
            if ($value->isAvailable() === false) {
                return array('error_code' => -2, 'error_msg' => 'unavailable user');
            }
            if (empty($value->getGroups())) {
                return array('error_code' => -3, 'error_msg' => 'no groups');
            }
            if ($value->isVerify($password) === false) {
                return array('error_code' => -4, 'error_msg' => 'wrong password');
            }

            // session & cookie logic
            $token = [
                'sessionId' => $context->getSession()->getId(),
                'userId' => $value->getId(),
                'groupIds' => $value->getGroups(),
                'editor' => $value->getCode()
            ];

            $context->setUserId($token['userId']);
            $context->setGroupIds($token['groupIds']);
            $context->setEditor($token['editor']);

            $context->getCookie()->set(
                'PHPTOKEN',
                json_encode($token),
                \Aimeos\MW\Cookie\Base::$ONE_DAY_TIMEOUT
            );
            $context->getSession()->set('PHPTOKEN', $token);

        } catch (\Exception $e) {
            return array('error_code' => -5, 'error_msg' => 'unknown error');
        }

        return array('error_code' => 0, 'error_msg' => 'login success');
    }

    /**
     * Checking for web login
     *
     * @param \Aimeos\MShop\Context\Item\Iface $context Context object
     * @return bool
     */
    public function webLoginConfirm(\Aimeos\MShop\Context\Item\Iface $context) : bool
    {
        try {
            $loginInfo = $context->getSession()->get('PHPTOKEN');
            $tokenInfo = $context->getCookie()->get('PHPTOKEN');

            if (json_encode($loginInfo) == $tokenInfo) {
                $context->setUserId($loginInfo['userId']);
                $context->setGroupIds($loginInfo['groupIds']);
                $context->setEditor($loginInfo['editor']);
                return true;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Web logout
     *
     * @param \Aimeos\MShop\Context\Item\Iface $context Context object
     */
    public function webLogout(\Aimeos\MShop\Context\Item\Iface $context)
    {
        $context->getCookie()->clear();
        $context->getSession()->clear();
    }

}
