<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'CtDisplay' => 'CtDisplay\Controller\CtDisplayController',
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'CtDisplay' => __DIR__ . '/../view',
        ),
    ),
    'navigation' => array(
    	'default' => array(
			'overview' => array(
				'pages' => array(
				    array(
				    		'label' => 'HipTrace',
				    		'controller' => 'CtDisplay',
				    		'route' => 'default'
				    ),
			    )
		    )
	    )
    )
);
