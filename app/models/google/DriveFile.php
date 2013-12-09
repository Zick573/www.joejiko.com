<?php namespace App\Models\Google;
class DriveFile
{
  protected $client;
  protected $service;

  public function __construct(\GoogleApi\Client $client) {
    //Insert a file
    $file = new Google_DriveFile();
    $file->setTitle('My document');
    $file->setDescription('A test document');
    $file->setMimeType('text/plain');

    $data = file_get_contents('document.txt');

    $createdFile = $service->files->insert($file, array(
          'data' => $data,
          'mimeType' => 'text/plain',
        ));

    print_r($createdFile);
  }

  public function authorize()
  {
    if($code = $app->request()->get('code')) {
      $this->client->authenticate();
      $tokens = $client->getAccessToken();
      set_user($tokens);
    }

    if($user = get_user()) {
      $client->setAccessToken($user->tokens);
    }
  }
}