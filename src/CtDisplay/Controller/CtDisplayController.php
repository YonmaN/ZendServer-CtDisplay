<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/CtDisplay for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace CtDisplay\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Application\ConfigAwareInterface;
use Zend\Config\Config;
use ZendServer\Set;
use ZendServer\FS\FS;

class CtDisplayController extends AbstractActionController implements ConfigAwareInterface
{
    /**
     * @var Config
     */
    private $config;
    
    public function indexAction()
    {
        $file = FS::getFileObject(FS::createPath($this->config['filepath'], 'hhvm.trace'), 'r');
        
        $metadata = $file->current();
        $file->next();
        return array('frames' => $file, 'metadata' => $metadata);
    }
    
	/* (non-PHPdoc)
	 * @see \Application\ConfigAwareInterface::getAwareNamespace()
	 */
	public function getAwareNamespace() {
		return array('ctdisplay');
	}

	/* (non-PHPdoc)
	 * @see \Application\ConfigAwareInterface::setConfig()
	 */
	public function setConfig($config) {
		$this->config = $config;
	}

}
