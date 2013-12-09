<?php
class AdminController extends BaseController {
  public function getIndex()
  {
    return View::make('admin.index')->with(array('user' => $this->user));

  }

  public function getStatic()
  {
    $result = array();
    $pageToken = NULL;
    $client = new \GoogleApi\Client();
    $service = new \GoogleApi\Contrib\apiDriveService($client);

    do {
      try {
        $parameters = array();
        if($pageToken) {
          $parameters['pageToken'] = $pageToken;
        }

        $files = $service->files->listFiles($parameters);

        $result = array_merge($result, $files->getItems());
        $pageToken = $files->getNextPageToken();
      } catch (Exception $e) {
        print "An error occurred: " . $e->getMessage();
        $pageToken = NULL;
      }
    } while ($pageToken);
    var_dump( $result );
  }

  public function getQuestions()
  {
    $questions = Question::all();
    return View::make('admin.pages.questions.index')->with(array('questions' => $questions));
  }

  public function missingMethod($parameters)
  {
    return Redirect::to('home');
  }
}