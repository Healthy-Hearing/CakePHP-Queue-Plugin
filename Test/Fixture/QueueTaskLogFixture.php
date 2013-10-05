<?php
/**
 * QueueTaskLogFixture
 *
 */
class QueueTaskLogFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'user_id' => array('type' => 'biginteger', 'null' => true, 'default' => null, 'length' => 22, 'comment' => 'user_id of who created/modified this queue. optional'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null, 'key' => 'index'),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'executed' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => 'datetime when executed.'),
		'execute' => array('type' => 'datetime', 'null' => true, 'default' => null, 'key' => 'index', 'comment' => 'datetime when to execute, if null do it as soon as possible'),
		'priority' => array('type' => 'integer', 'null' => false, 'default' => '100', 'length' => 4, 'key' => 'index', 'comment' => 'priorty, lower the number, the higher on the list it will run.'),
		'status' => array('type' => 'integer', 'null' => false, 'default' => '1', 'length' => 2, 'key' => 'index', 'comment' => '1:queued,2:inprogress,3:finished,4:paused'),
		'type' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 2, 'key' => 'index', 'comment' => '1:model,2:shell,3:url,4:php_cmd,5:shell_cmd'),
		'command' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'result' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'status' => array('column' => 'status', 'unique' => 0),
			'type' => array('column' => 'type', 'unique' => 0),
			'execute' => array('column' => 'execute', 'unique' => 0),
			'created' => array('column' => 'created', 'unique' => 0),
			'priority' => array('column' => 'priority', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
	);

}