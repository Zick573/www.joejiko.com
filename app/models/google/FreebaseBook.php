<?php
class FreebaseBook extends \Google\Freebase {

  protected $raw;

  public function __construct($api_name, $params=array())
  {
    $this->apiname = $api_name;

    if(!array_key_exists('query', $params))
    {
      throw new Exception("No query.");
    }
    $this->query = $params['query'];

    // optional
    $this->filter = array_key_exists('filter', $params) ? $params['filter'] : NULL;

    try {
      $freebase = new FreebaseApi();
      $this->raw = $freebase->get($api_name, array(
        'query' => $this->query,
        'filter' => $this->filter
      ));
      $this->data = json_decode($this->raw);
      $this->prepForOutput();
    } catch (Exception $e) {
      $this->data = $e->getMessage();
    }
  }

  protected function prepForOutput()
  {
    $data = $this->data; // array of freebase JSON
    if(array_key_exists('result', $data) && count($data) > 0)
    {
      $result = $data['result'];
      $output = array();
      // proceed expecting data
      foreach($result as $index => $value)
      {
        $output[$index] = array(
          'name' => $result[$index]['name'],
          'type' => $result[$index]['notable']['name']
        )
      }
    }

    $this->data = json_encode($output);
  }

  public function output()
  {
    return $this->data;
  }
}