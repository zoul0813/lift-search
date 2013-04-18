<?php

class Quick_Filter_Maker {
	
	public function init() {
		foreach ( array( 'date', 'post_type', 'post_categories', 'post_tags', 'orderby' ) as $field) {
			add_filter('lift_form_field_'.$field, array($this, $field), 10, 3);
		}
	}
	public function __call( $field, $arguments ) {
		return $arguments[2]["before_field"] . "<label>NOT YET IMPLEMENTED {$field}</label><br />" . $arguments[2]["after_field"];
	}
	
	/**
   * 
	 * @param string $field_html
	 * @param Lift_Search_Form $lift_search_form
	 * @return string
	 */
	public function date($field_html, $lift_search_form, $args = array()) {
		$base_url = $lift_search_form->getSearchBaseURL() . '?' . http_build_query($lift_search_form->getStateVars());
		$field = '<ul>
				<li class="selected"></li>
				<li><a href="'.add_query_arg('foo', 'bar', esc_url($base_url)).'">Foobar</a></li>
				<li></li>
				<li></li>
				<li></li>
				<li></li>
			<ul>';
		return $args['before_field']. $field . $args['after_field'];
	}
}
add_action('wp_loaded', array(new Quick_Filter_Maker(), 'init'));

class GenericControl {

	/**
	 * Test
	 * @var string 
	 */
	protected $id;

	public function getID() {
		return $this->id;
	}

	/**
	 *
	 * @var string
	 */
	protected $name;

	public function getName() {
		return $this->name;
	}

	/**
	 *
	 * @var array
	 */
	protected $classes;

	private function getClasses() {
		return $this->classes;
	}

	/**
	 *
	 * @var GenericControlValueCollection
	 */
	public $values;

	public function __construct( $id, $name, $values = array( ), $classes = array( ) ) {
		$this->id = $id;
		$this->name = $name;
		$this->values = new GenericControlValueCollection( $values );
		$this->classes = $classes;
	}

}

class GenericControlValueCollection implements Iterator, ArrayAccess {

	private $values;

	public function __construct() {
		$this->values = array( );
	}

	/**
	 * 
	 * @param  GenericControlValueCollection $value
	 * @return GenericControlValueCollection
	 */
	public function add( GenericControlValue $value ) {
		$this->values[] = $value;
		return $this;
	}

	/**
	 * 
	 * @param mixed $offset
	 * @return bool
	 */
	public function offsetExists( $offset ) {
		return isset( $this->values[$offset] );
	}

	/**
	 * 
	 * @param mixed $offset
	 * @return GenericControlValue
	 */
	public function offsetGet( $offset ) {
		return isset( $this->values[$offset] ) ? $this->values[$offset] : null;
	}

	/**
	 * 
	 * @param type $offset
	 * @param type $value
	 */
	public function offsetSet( $offset, $value ) {
		if ( is_null( $offset ) ) {
			$this->values[] = $value;
		} else {
			$this->values[$offset] = $value;
		}
	}

	/**
	 * 
	 * @param mixed $offset
	 */
	public function offsetUnset( $offset ) {
		unset( $this->values[$offset] );
	}

	/**
	 * 
	 * @return GenericControlValue
	 */
	public function current() {
		return current( $this->values );
	}

	/**
	 * 
	 * @return GenericControlValue
	 */
	public function key() {
		return key( $this->values );
	}

	/**
	 * 
	 * @return GenericControlValue
	 */
	public function next() {
		return next( $this->values );
	}

	/**
	 * 
	 * @return bool
	 */
	public function rewind() {
		return rewind( $this->values );
	}

	/**
	 * 
	 * @return bool
	 */
	public function valid() {
		return valid( $this->values );
	}

}

class GenericControlValue {

	/**
	 * @var bool
	 */
	protected $selected = false;

	public function setSelected( $value ) {
		$this->selected = ( bool ) $value;
		return this;
	}

	public function getSelected() {
		return $this->selected;
	}

	/**
	 * @var string
	 */
	protected $value = false;

	public function setValue( $value ) {
		$this->value = $value;
		return this;
	}

	public function getValue() {
		return $this->value;
	}

	/**
	 * @var string
	 */
	protected $label = false;

	public function setLabel( $label ) {
		$this->label = $label;
		return this;
	}

	public function getLabel() {
		return $this->label;
	}

}