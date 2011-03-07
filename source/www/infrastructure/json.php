<?

class JSON {
  
  public static function result($result) {
    return self::create(array("result" => $result));
  }
  
  public static function create($object) {
    $type = self::find_type($object);
    switch ($type) {
      case "array":   return self::create_array($object);
      case "map":     return self::create_map($object);
      case "string":  return self::create_string($object);
      case "boolean": return $object ? "true" : "false";
      case "native":  return $object;
    }
  }
  
  private static function find_type($object) {
    if (is_string($object)) return "string";
    if (is_bool($object)) return "boolean";
    if (!is_array($object)) {
      return "native";
    }
    for ($i = 0; $i < sizeof($object); $i++) {
      if (!isset($object[$i])) return "map";
    }
    return "array";
  }
  
  private static function create_array($array) {
    $json = "[";
    foreach ($array as $element) {
      if (strlen($json) > 1) $json .= ", ";
      $json .= self::create($element);
    }
    return $json."]";
  }
  
  private static function create_map($map) {
    $json = "{";
    foreach ($map as $key => $value) {
      if (strlen($json) > 1) $json .= ", ";
      $json .= '"'.$key.'": '.self::create($value);
    }
    return $json."}";
  }
  
  private static function create_string($string) {
    return '"'.str_replace('"', '\\"', str_replace('\\', '\\\\', $string)).'"';
  }
  
  
}

?>