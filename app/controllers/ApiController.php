<?php
class ApiController extends BaseController {
  public function getConnect()
  {
    return json_encode(
      array(
        "result" => "success"
      )
    );
  }

  public function getSession()
  {
    switch($_GET['method']) {
      case "put":
        foreach($_GET['values'] as $key => $value):
          Session::put($key, $value);
        endforeach;
        break;
      case "forget":
        foreach($_GET['keys'] as $key):
          Session::forget($key);
        endforeach;
        break;
      default:
        return false;
    }
  }

  public function postConnect()
  {
    return json_encode(
      array(
        "result" => "success"
      )
    );
  }

  public function postSendMessage()
  {
    return json_encode(
      array(
        "result" => "success"
      )
    );
  }

  public function getHelloJiko()
  {
    return "updates?";
  }

  /**
   * @return html
   **/
  public function getUi()
  {
    $params = $_GET;
    if(!$_GET['name'] || !array_key_exists('name', $params) )
    {
      throw new Exception('nothing to do');
    }

    $name = $params['name'];
    return View::make($params['name']);
  }

  public function getFreebase()
  {
    if(!$_GET['query']){
      throw new Exception('no search query set.');
    }
    $params = $_GET;
    $api_name = array_key_exists('api_name', $params) ? $params['api_name'] : 'search';
    $filter = array_key_exists('filter', $params) ? $params['filter'] : '(all)';
    $output = array_key_exists('output', $params) ? $params['output'] : '(created_by)';
    $freebase = new Freebase($api_name, array(
      'query' => $params['query'],
      'filter' => $filter,
      'output' => $output
    ));
    $resp = Response::make($freebase->output(), "200");
    $resp->header('Content-Type', 'application/json');
    return $resp;
  }

  public function getMusic()
  {
    $params = $_GET;
    $lastfm = new Lastfm(array(
      'user' => $params['user'],
      'page' => $params['page'],
      'method' => $params['method']
    ));
    $response = Response::make($lastfm->output(), "200");
    $response->header('Content-Type', 'application/json');
    return $response;
  }

  public function getSteam()
  {
    $params = $_GET;
    if(array_key_exists('module', $_GET)){
      switch($_GET['module']) {
        case "friends":
          $steam = new Steam(array(
            'module' => 'friends'
          ));
          break;
        default:
          throw new Exception("Unknown module");
          return false;
      }
    }
    else {
      $steam = new Steam(array());
    }

    $response = Response::make($steam->output(), "200");
    $response->header('Content-Type', 'application/json');
    return $response;
  }

  public function getQuestions()
  {
    $params = $_GET;
    $limit = (isset($params['limit']) && is_numeric($params['limit'])) ? $params['limit'] : 3;
    return Response::json(Question::orderBy('response_created_at', 'desc')->take($limit)->get());
  }

  public function missingMethod($method, $parameters=[])
  {
    // missing
    header("Content-type: application/json");
    echo "Missing";
  }

}