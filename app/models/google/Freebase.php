<?php
use \Google\Api\Freebase as FreebaseApi;
class Freebase {

  protected $api_name;
  protected $data; // prepared json
  protected $filter;
  protected $raw; // json response from freebase
  protected $query;

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
    $this->output = array_key_exists('output', $params) ? $params['output'] : NULL;

    try {
      $freebase = new FreebaseApi();
      $this->raw = $freebase->get($api_name, array(
        'query' => $this->query,
        'filter' => $this->filter,
        'output' => $this->output
      ));
      $this->data = json_decode($this->raw, true);
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
        $created_by = "UNKNOWN";
        if(array_key_exists('output', $result[$index]))
        {
          $resultOutput = $result[$index]['output'];
          if(array_key_exists('created_by', $resultOutput))
          {
            if(array_key_exists('/book/written_work/author', $resultOutput['created_by']))
            {
              $created_by = $resultOutput['created_by']['/book/written_work/author'][0]['name'];
            }
          }
        }
        $output[$index] = array(
          'name' => $result[$index]['name'],
          'type' => $result[$index]['notable']['name'],
          'created_by' => $created_by
        );
      }
    }

    $this->data = json_encode($output);
  }

  public function output()
  {
    return $this->data;
  }

  public function __get($property)
  {
    if(property_exists($this, $property)) {
      return $this->property;
    }
  }
}