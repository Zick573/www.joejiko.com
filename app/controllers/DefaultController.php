<?php
class DefaultController extends BaseController
{
  protected $user;
  protected $errors = array();
  private $gcdn_base = "https://googledrive.com/host/0B_9a_WMIXbTtNVhHd1J0WDZHd28/";
  protected $layout = 'layouts.master-2';
  public function __construct(){
    # set user
    $this->user = (Auth::check()) ? Auth::user() : NULL;
  }

  public function SetupLayout() {
    $footer_copy_path = base_path().'/app/assets/markdown/footer.md';
    $site_footer_copy = file_get_contents($footer_copy_path);
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

    parent::SetupLayout();
  }

}