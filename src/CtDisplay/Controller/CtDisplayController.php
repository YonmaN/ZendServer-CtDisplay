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
use Zend\Uri\UriFactory;

class CtDisplayController extends AbstractActionController implements ConfigAwareInterface
{
    /**
     * @var Config
     */
    private $config;
    
    public function indexAction()
    {
        $file = FS::getFileObject(FS::createPath($this->config['filepath'], 'hhvm.trace'), 'r');
        $state = false;
        $metadata = array();
        $frames = array();
        $calls = array();
        $callIndex = 0;
        while (! $file->eof()) {
            $line = trim($file->current());
            $file->next();
            
            if (substr($line, 0, 2) == '>>') {
                $state = substr($line, 2);
                continue;
            } elseif (substr($line, 0, 2) == '<<') {
                $state = false;
                continue;
            }
            
            $info = explode('|', $line);
            switch ($state) {
            	case 'GLOBAL':
            	    $metadata[] = $info[1];
            	    break;
            	case 'CALLSTACK':
            	    $indent = str_repeat(' ', $info[0]);
            	    $frames[] = "{$indent}{$info[1]} on {$info[2]}:{$info[3]}</font>";
                    break;
                case 'CALLS':
                    $callIndex ++;
                    $calls[] = array('id' => $callIndex, 'function' => $info[0], 'count' => $info[1]);
                	break;
            	case false:
                default:
            }
        }
        
        $metadata = UriFactory::factory(implode(array_reverse($metadata)));
        
        return array('frames' => $frames, 'metadata' => $metadata, 'calls' => $calls);
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
