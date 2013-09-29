<?php
class BaseController extends Controller {

  protected $user;
  protected $errors = array();

  private $gcdn_base = "https://googledrive.com/host/0B_9a_WMIXbTtNVhHd1J0WDZHd28/";

  public $debug = array(); // temp @todo remove
  public $hybridauth;
  public $user_from_session = false;

  public function __construct(){

    # set user
    $this->user = (Auth::check()) ? Auth::user() : Auth::loginUsingId(0);

    // if (!$this->user instanceof User):
    //   $hybridAuthCheck = self::hybridAuthCheck();
    // endif;

    // if(is_null($this->user) || $this->user == "Anonymous"):
    //   $this->errors[] = $hybridAuthCheck;
    //   # Anonymous
    //   $user = array(
    //     'name' =>"Anonymous",
    //     'id' => 0,
    //     'role' => 0
    //   );
    //   $this->user = (object) $user;
    // else:

    // $this->user->info = User::find($this->user->id)->info()->where('sortOrder', '=', '1')->first();
    // if(is_null($this->user->email) || empty($this->user->email) || !isset($this->user->email)):
    //   return Redirect::to('user/connected/missing-required');
    // endif;
  }

  public function setupLayout()
  {

    $footer_copy_path = base_path().'/app/assets/markdown/footer.md';
    if(file_exists($footer_copy_path)) {
      $site_footer_copy = Michelf\Markdown::defaultTransform(file_get_contents($footer_copy_path));
    }
    $sharedViewData = array(
      'logo' => file_get_contents($this->gcdn_base.'img/shared/logo.svg'),
      'triangle' => file_get_contents($this->gcdn_base.'img/shared/triangle.svg'),
      'routeName' => Request::path(),
      'site_footer_copy' => $site_footer_copy,
      'user' => $this->user
    );
    foreach($sharedViewData as $label => $value)
    {
      View::share($label, $value);
    }

    if ( ! is_null($this->layout))
    {
      $this->layout = View::make($this->layout);
    }
  }
}