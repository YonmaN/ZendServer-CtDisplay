<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/CtDisplay for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace CtDisplay;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\EventManager\EventInterface;

class Module implements AutoloaderProviderInterface, BootstrapListenerInterface
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . str_replace('\\', '/' , __NAMESPACE__),
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
    
    public function onBootstrap(EventInterface $e) {
        /// add ACL entries
        $serviceManager = $e->getApplication()->getServiceManager();
        $identityAcl = $serviceManager->get('ZendServerIdentityAcl');
        $identityAcl->addResource('route:CtDisplay');
        $identityAcl->allow('administrator', 'route:CtDisplay');
        $licenseAcl = $serviceManager->get('ZendServerLicenseAcl');
        $licenseAcl->addResource('route:CtDisplay');
        $licenseAcl->allow(null, 'route:CtDisplay');
    }
}
