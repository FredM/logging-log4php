<?php
 

/**
 * 
 * @group configurators
 *
 */
 class LoggerConfiguratorTest extends PHPUnit_Framework_TestCase
 {
 	/** Reset configuration after each test. */
 	public function setUp() {
 		Logger::resetConfiguration();
 	}
 	/** Reset configuration after each test. */
 	public function tearDown() {
 		Logger::resetConfiguration();
 	}
 	
 	/** Check default setup. */
 	public function testDefaultConfig() {
 		Logger::configure();
 		
 		$actual = Logger::getCurrentLoggers();
 		$expected = array();
		$this->assertSame($expected, $actual);

 		$appenders = Logger::getRootLogger()->getAllAppenders();
 		$this->assertInternalType('array', $appenders);
 		$this->assertEquals(count($appenders), 1);
 		$this->assertSame('default', $appenders[0]->getName());
 		
 		$appender = $appenders[0];
 		$this->assertInstanceOf('LoggerAppenderEcho', $appender);
 		
 		$layout = $appender->getLayout();
 		$this->assertInstanceOf('LoggerLayoutTTCC', $layout);
 		
 		$root = Logger::getRootLogger();
 		$appenders = $root->getAllAppenders();
 		$this->assertInternalType('array', $appenders);
 		$this->assertEquals(count($appenders), 1);
		
 		$actual = $root->getLevel();
 		$expected = LoggerLevel::getLevelDebug();
 		$this->assertEquals($expected, $actual);
 	}
 	
 	/**
 	 * @expectedException PHPUnit_Framework_Error
 	 * @expectedExceptionMessage Invalid configuration param given. Reverting to default configuration.
 	 */
 	public function testInputIsInteger() {
 		Logger::configure(12345);
 	}
 	
 	/**
 	 * @expectedException PHPUnit_Framework_Error
 	 * @expectedExceptionMessage log4php: Configuration failed. Unsupported configuration file extension: yml
 	 */ 	
 	public function testYAMLFile() {
		Logger::configure(PHPUNIT_CONFIG_DIR . '/config.yml');
 	}

 	/**
 	 * @expectedException PHPUnit_Framework_Error
 	 * @expectedExceptionMessage Invalid configuration provided for appender
 	 */
 	public function testAppenderConfigNotArray() {
 		$hierachyMock = $this->getMock('LoggerHierarchy', array(), array(), '', false);
 		
 		$config = array(
	 		'appenders' => array(
	            'default',
	        ),
        );

        $configurator = new LoggerConfigurator();
        $configurator->configure($hierachyMock, $config);
 	}
 	
  	/**
 	 * @expectedException PHPUnit_Framework_Error
 	 * @expectedExceptionMessage No class given for appender
 	 */
 	public function testNoAppenderClassSet() {
 		Logger::configure(PHPUNIT_CONFIG_DIR . '/appenders/config_no_class.xml');
 	} 	
 	
  	/**
 	 * @expectedException PHPUnit_Framework_Error
 	 * @expectedExceptionMessage Invalid class [unknownClass] given for appender [foo]. Class does not exist. Skipping appender definition.
 	 */
 	public function testNotExistingAppenderClassSet() {
 		Logger::configure(PHPUNIT_CONFIG_DIR . '/appenders/config_not_existing_class.xml');
 	} 

   	/**
 	 * @expectedException PHPUnit_Framework_Error
 	 * @expectedExceptionMessage Invalid class [stdClass] given for appender [foo]. Not a valid LoggerAppender class. Skipping appender definition.
 	 */
 	public function testInvalidAppenderClassSet() {
 		Logger::configure(PHPUNIT_CONFIG_DIR . '/appenders/config_invalid_appender_class.xml');
 	} 	
 	
    /**
 	 * @expectedException PHPUnit_Framework_Error
 	 * @expectedExceptionMessage Nonexistant filter class [Foo] specified on appender [foo]. Skipping filter definition.
 	 */
 	public function testNotExistingAppenderFilterClassSet() {
 		Logger::configure(PHPUNIT_CONFIG_DIR . '/appenders/config_not_existing_filter_class.xml');
 	}

    /**
 	 * @expectedException PHPUnit_Framework_Error
 	 * @expectedExceptionMessage Nonexistant option [fooParameter] specified on [LoggerFilterStringMatch]. Skipping.
 	 */
 	public function testInvalidAppenderFilterParamter() {
 		Logger::configure(PHPUNIT_CONFIG_DIR . '/appenders/config_invalid_filter_parameters.xml');
 	} 	
 	
    /**
 	 * @expectedException PHPUnit_Framework_Error
 	 * @expectedExceptionMessage Invalid filter class [stdClass] sepcified on appender [foo]. Skipping filter definition.
 	 */
 	public function testInvalidAppenderFilterClassSet() {
 		Logger::configure(PHPUNIT_CONFIG_DIR . '/appenders/config_invalid_filter_class.xml');
 	} 	
 	
    /**
 	 * @expectedException PHPUnit_Framework_Error
 	 * @expectedExceptionMessage Nonexistant layout class [Foo] specified for appender [foo]. Reverting to default layout.
 	 */
 	public function testNotExistingAppenderLayoutClassSet() {
 		Logger::configure(PHPUNIT_CONFIG_DIR . '/appenders/config_not_existing_layout_class.xml');
 	}
 	
    /**
 	 * @expectedException PHPUnit_Framework_Error
 	 * @expectedExceptionMessage Invalid layout class [stdClass] sepcified for appender [foo]. Reverting to default layout.
 	 */
 	public function testInvalidAppenderLayoutClassSet() {
 		Logger::configure(PHPUNIT_CONFIG_DIR . '/appenders/config_invalid_layout_class.xml');
 	} 

    /**
 	 * @expectedException PHPUnit_Framework_Error
 	 * @expectedExceptionMessage Layout class not specified for appender [foo]. Reverting to default layout.
 	 */
 	public function testNoAppenderLayoutClassSet() {
 		Logger::configure(PHPUNIT_CONFIG_DIR . '/appenders/config_no_layout_class.xml');
 	}   	

    /**
 	 * @expectedException PHPUnit_Framework_Error
 	 * @expectedExceptionMessage Invalid class [stdClass] given. Not a valid LoggerRenderer class. Skipping renderers definition.
 	 */
 	public function testInvalidRenderingClassSet() {
 		Logger::configure(PHPUNIT_CONFIG_DIR . '/renderers/config_invalid_rendering_class.xml');
 	} 	
 	
    /**
 	 * @expectedException PHPUnit_Framework_Error
 	 * @expectedExceptionMessage Rendering class not specified. Skipping renderers definition.
 	 */
 	public function testNoRenderingClassSet() {
 		Logger::configure(PHPUNIT_CONFIG_DIR . '/renderers/config_no_rendering_class.xml');
 	} 	

    /**
 	 * @expectedException PHPUnit_Framework_Error
 	 * @expectedExceptionMessage Rendered class not specified for rendering Class [LoggerRendererDefault]. Skipping renderers definition.
 	 */
 	public function testNoRenderedClassSet() {
 		Logger::configure(PHPUNIT_CONFIG_DIR . '/renderers/config_no_rendered_class.xml');
 	} 	
 	
     /**
 	 * @expectedException PHPUnit_Framework_Error
 	 * @expectedExceptionMessage Nonexistant rendered class [RenderFooClass] specified for renderer [LoggerRendererDefault]. Skipping renderers definition.
 	 */
 	public function testNotExistingRenderedClassSet() {
 		Logger::configure(PHPUNIT_CONFIG_DIR . '/renderers/config_not_existing_rendered_class.xml');
 	} 	
 	
 	/**
 	 * @expectedException PHPUnit_Framework_Error
 	 * @expectedExceptionMessage Nonexistant rendering class [FooRenderer] specified. Skipping renderers definition.
 	 */
 	public function testNotExistingRenderingClassSet() {
 		Logger::configure(PHPUNIT_CONFIG_DIR . '/renderers/config_not_existing_rendering_class.xml');
 	} 	
 	
 	/**
 	 * @expectedException PHPUnit_Framework_Error
 	 * @expectedExceptionMessage Invalid additivity value [4711] specified for logger [myLogger].
 	 */
 	public function testInvalidLoggerAddivity() {
 		Logger::configure(PHPUNIT_CONFIG_DIR . '/loggers/config_invalid_additivity.xml');
 	} 

 	/**
 	 * @expectedException PHPUnit_Framework_Error
 	 * @expectedExceptionMessage Nonexistnant appender [unknownAppender] linked to logger [myLogger].
 	 */
 	public function testNotExistingLoggerAppendersClass() {
 		Logger::configure(PHPUNIT_CONFIG_DIR . '/loggers/config_not_existing_appenders.xml');
 	}  	
 	
 	/**
 	 * Test that an error is reported when config file is not found. 
 	 * @expectedException PHPUnit_Framework_Error
 	 * @expectedExceptionMessage log4php: Configuration failed. File not found
 	 */
 	public function testNonexistantFile() {
 		Logger::configure('hopefully/this/path/doesnt/exist/config.xml');
 		
 	}
 	
 	/** Test correct fallback to the default configuration. */
 	public function testNonexistantFileFallback() {
 		@Logger::configure('hopefully/this/path/doesnt/exist/config.xml');
 		$this->testDefaultConfig();
 	}
 	
 	public function testAppendersWithLayout() {
 		$config = Logger::configure(array(
 			'rootLogger' => array(
 				'appenders' => array('app1', 'app2')
 			),
 			'loggers' => array(
 				'myLogger' => array(
 					'appenders' => array('app1'),
 					'additivity'=> true
 				)
 			),
 			'renderers' => array(
 				array('renderedClass' => 'stdClass', 'renderingClass' => 'LoggerRendererDefault')
 			),
 			'appenders' => array(
 				'app1' => array(
 					'class' => 'LoggerAppenderEcho',
 					'layout' => array(
 						'class' => 'LoggerLayoutSimple'
 					),
 					'params' => array(
 						'htmlLineBreaks' => false
 					)
 				),
		 		'app2' => array(
		 		 	'class' => 'LoggerAppenderEcho',
		 		 	'layout' => array(
		 		 		'class' => 'LoggerLayoutPattern',
		 		 		'params' => array(
		 		 			'conversionPattern' => 'message: %m%n'
		 		 		)
		 			),
		 			'filters' => array(
		 				array(
		 					'class'	=> 'LoggerFilterStringMatch',
		 					'params'=> array(
		 						'stringToMatch'	=> 'foo',
		 						'acceptOnMatch'	=> false
		 					)
		 				)
		 			)
		 		),
 			) 
 		));
 		
 		ob_start();
 		Logger::getRootLogger()->info('info');
 		$actual = ob_get_contents();
 		ob_end_clean();
 		
 		$expected = "INFO - info" . PHP_EOL . "message: info" . PHP_EOL;
  		$this->assertSame($expected, $actual);
 	}
 }