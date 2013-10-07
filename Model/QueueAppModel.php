<?php
App::uses('AppModel', 'Model');
App::uses('QueueUtil','Queue.Lib');
class QueueAppModel extends AppModel {
	/**
	 * Always use Containable
	 *
	 * var array
	 */
	public $actsAs = array('Containable');

	/**
	 * Always set recursive = 0
	 * (we'd rather use containable for more control)
	 *
	 * var int
	 */
	public $recursive = 0;
	/**
	 * Filter fields
	 *
	 * @var array
	 */
	public $searchFields = array();
	/**
	* Status key to human readable
	* @var array
	* @access protected
	*/
	protected $_statuses = array(
		1 => 'queued',
		2 => 'in progress',
		3 => 'finished',
		4 => 'paused',
	);
	/**
	* type key to human readable
	* @var array
	* @access protected
	*/
	protected $_types = array(
		1 => 'model',
		2 => 'shell',
		3 => 'url',
		4 => 'php_cmd',
		5 => 'shell_cmd',
	);
	/**
	* afterFind will add status_human and type_human to the result
	* human readable and understandable type and status.
	* @param array of results
	* @param boolean primary
	* @return array of altered results
	*/
	public function afterFind($results = array(), $primary = false){
		foreach ($results as $key => $val) {
			if (isset($val[$this->alias]['type'])) {
				$results[$key][$this->alias]['type_human'] = $this->_types[$val[$this->alias]['type']];
			}
			if (isset($val[$this->alias]['status'])) {
				$results[$key][$this->alias]['status_human'] = $this->_statuses[$val[$this->alias]['status']];
			}
		}
		return $results;
	}
	/**
	 * return conditions based on searchable fields and filter
	 *
	 * @param string filter
	 * @return conditions array
	 */
	public function generateFilterConditions($filter = NULL, $pre = '') {
		$retval = array();
		if ($filter) {
			foreach ($this->searchFields as $field) {
				$retval['OR']["$field LIKE"] =  '%' . $filter . '%';
			}
		}
		return $retval;
	}
	/**
  * This is what I want create to do, but without setting defaults.
  */
  public function clear() {
  	$this->id = false;
		$this->data = array();
		$this->validationErrors = array();
		return $this->data;
  }
  /**
  * String to datetime stamp
  * @param string that is parsable by str2time
  * @return date time string for MYSQL
  */
  function str2datetime($str = 'now') {
  	if (is_array($str) && isset($str['month']) && isset($str['day']) && isset($str['year'])) {
  		$str = "{$str['month']}/{$str['day']}/{$str['year']}";
  	}
  	return date("Y-m-d H:i:s", strtotime($str));
  }
 
  /**
  * Returns if the variable is an int or string that matches an int
  * @param mixed var
  * @return boolean if is digit.
  */
  public function isDigit($var = null) {
  	return (is_int($var) || (is_string($var) && preg_match('/\d+$/', $var)));
  }
  /**
	* String representation of task
	* @param uuid string
	* @return string of task.
	*/
	public function niceString($id = null) {
		if ($id) {
			$this->id = $id;
		}
		if (!$this->exists()) {
			return $this->__errorAndExit("QueueTask {$this->id} not found.");
		}
		$data = $this->read();
		$retval = $data[$this->alias]['id'] . ' ' . $data[$this->alias]['status_human'] . ' ' . $data[$this->alias]['type_human'];
		$retval .= "\n\tCommand: " . $data[$this->alias]['command'];
		if ($data[$this->alias]['is_restricted']) {
			$retval .= "\n\tRestricted By:";
			if ($data[$this->alias]['hour'] !== null) {
				$retval .= " Hour:{$data[$this->alias]['hour']}";
			}
			if ($data[$this->alias]['day'] !== null) {
				$retval .= " Day:{$data[$this->alias]['day']}";
			}
			if ($data[$this->alias]['cpu_limit'] !== null) {
				$retval .= " CPU<={$data[$this->alias]['cpu_limit']}%";
			}
		}
		if ($data[$this->alias]['status'] == 3 && !empty($data[$this->alias]['executed'])) { //Finished
			$retval .= "\n\tExecuted on " . date('l jS \of F Y h:i:s A', strtotime($data[$this->alias]['executed'])) . '. And took ' . $data[$this->alias]['execution_time'] . ' ms.';
			$retval .= "\n\tResult: " . $data[$this->alias]['result'];
		}
		return $retval;
	}
}
