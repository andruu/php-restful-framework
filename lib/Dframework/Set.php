<?php
use mjohnson\utility\TypeConverter;
use Symfony\Component\Yaml\Yaml;

class Set extends ArrayIterator {

  public function to_json () {
    $results = [];
    foreach ($this as $result) {
      $results[strtolower(Inflector::pluralize(get_class($this[0])))][] = $result->to_array();
    }
    return TypeConverter::toJson($results);
  }

  public function to_xml () {
    $results = [];
    foreach ($this as $result) {
      $results[strtolower(Inflector::singularize(get_class($this[0])))][] = $result->to_array();
    }
    return TypeConverter::toXml($results);
  }

  public function to_yaml () {
    $results = [];
    foreach ($this as $result) {
      $results[] = $result->to_array();
    }
    return Yaml::dump($results);
  }

}