<?php
/**
 * Class SettingsContainerAbstract
 *
 * @filesource   SettingsContainerAbstract.php
 * @created      28.08.2018
 * @package      chillerlan\Settings
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2018 Smiley
 * @license      MIT
 */

namespace chillerlan\Settings;

use ReflectionClass, ReflectionProperty;

abstract class SettingsContainerAbstract implements SettingsContainerInterface{

	/**
	 * SettingsContainerAbstract constructor.
	 *
	 * @param iterable|null $properties
	 */
	public function __construct(iterable $properties = null){

		if(!empty($properties)){
			$this->fromIterable($properties);
		}

		$this->construct();
	}

	/**
	 * calls a method with trait name as replacement constructor for each used trait
	 * (remember pre-php5 classname constructors? yeah, basically this.)
	 *
	 * @return void
	 */
	protected function construct():void{
		$traits = (new ReflectionClass($this))->getTraits();

		foreach($traits as $trait){
			$method = $trait->getShortName();

			if(method_exists($this, $method)){
				call_user_func([$this, $method]);
			}
		}

	}

	/**
	 * @inheritdoc
	 */
	public function __get(string $property){

		if($this->__isset($property)){
			return $this->{$property};
		}

		return null;
	}

	/**
	 * @inheritdoc
	 */
	public function __set(string $property, $value):void{

		if(!property_exists($this, $property) || $this->isPrivate($property)){
			return;
		}

		$this->{$property} = $value;
	}

	/**
	 * @inheritdoc
	 */
	public function __isset(string $property):bool{
		return isset($this->{$property}) && !$this->isPrivate($property);
	}

	/**
	 * @internal Checks if a property is private
	 *
	 * @param string $property
	 *
	 * @return bool
	 */
	protected function isPrivate(string $property):bool{
		return (new ReflectionProperty($this, $property))->isPrivate();
	}

	/**
	 * @inheritdoc
	 */
	public function __unset(string $property):void{

		if($this->__isset($property)){
			unset($this->{$property});
		}

	}

	/**
	 * @inheritdoc
	 */
	public function __toString():string{
		return $this->toJSON();
	}

	/**
	 * @inheritdoc
	 */
	public function toArray():array{
		return get_object_vars($this);
	}

	/**
	 * @inheritdoc
	 */
	public function fromIterable(iterable $properties):SettingsContainerInterface{

		foreach($properties as $key => $value){
			$this->__set($key, $value);
		}

		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function toJSON(int $jsonOptions = null):string{
		return json_encode($this->toArray(), $jsonOptions ?? 0);
	}

	/**
	 * @inheritdoc
	 */
	public function fromJSON(string $json):SettingsContainerInterface{
		return $this->fromIterable(json_decode($json, true));
	}

}
