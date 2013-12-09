<?php namespace Jiko\Repo\Status;

use Jiko\Repo\RepoAbstract;
use Illuminate\Database\Eloquent\Model;

class EloquentStatus extends RepoAbstract implements StatusInterface {
  protected $status;

  public function __construct(Model $status)
  {
    $this->status = $status;
  }

  public function all()
  {
    return $this->status->all();
  }

  public function byId($id)
  {
    return $this->status->find($id);
  }

  public function byStatus($status)
  {
    return $this->status->where('slug', $status);
  }
}