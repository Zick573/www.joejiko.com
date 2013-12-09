<?php
// use Shared\Controller as Controller;
// use Framework\View as View;
// use Framework\ArrayMethods as ArrayMethods;
// use Framework\RequestMethods as RequestMethods;
// use Apps\LoveCalculator as LoveCalculator;
// use Apps\TwitterManager as TwitterManager;
class AppController extends BaseController {

  public function getLoveCalculator()
  {
    // $this->willRenderLayoutView = false;
    // $this->willRenderActionView = false;
    // $this->defaultAssets = false;
    // $this->smarty->assign(array(
    //   'layoutTpl' => 'layouts/apps/love-calculator'
    // ));

    // $request = $this->_parameters["request"];
    // LoveCalculator::index($request);
    return View::make('apps.lovecalc.index');
  }

  public function getTwitDash()
  {
    // $this->willRenderLayoutView = false;
    // $this->willRenderActionView = false;
    // $this->defaultAssets = false;
    // $this->smarty->assign(array(
    //   'layoutTpl' => 'layouts/apps/twitter-manager'
    // ));

    // $request = $this->_parameters["request"];
    // TwitterManager::index($request);
  }

  public function missingMethod($parameters)
  {
    return Redirect::to('home');
  }
}