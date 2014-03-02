<?php namespace Jiko\Repo\User;

use Jiko\Repo\RepoAbstract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class EloquentUser extends RepoAbstract implements UserInterface, RemindableInterface {

  protected $user;

  public function __construct(Model $user)
  {
    $this->user = $user;
  }

  public function connections()
  {
    return $this->hasOne('EloquentUserConnect');
  }

  public function profile()
  {
    return $this->hasOne('EloquentUserProfile');
  }

  public function create(array $data)
  {

    $user = $this->user->create($data);
    return $user;
  }

  public function update(array $data)
  {
    $user = $this->user->find($data['id']);
    $user->save();
    return true;
  }

  public function totalUsers($all = false)
  {
    return $this->user->count();
  }

  /**
   * Auth\UserInterface
   */
  public function getAuthIdentifier()
  {
    return $this->user->id;
  }

  public function getAuthPassword()
  {
    return $this->user->password;
  }

  /**
   * Auth\Reminders\RemindableInterface
   */
  public function getReminderEmail()
  {
    return $this->user->email;
  }
}