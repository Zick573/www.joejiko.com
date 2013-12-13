<?php namespace Jiko\Repo;
use Post;
use Status;
use Tag;
use Jiko\Repo\Tag\EloquentTag;
use Jiko\Service\Cache\LaravelCache;
use Jiko\Repo\Status\EloquentStatus;
use Jiko\Repo\Post\CacheDecorator;
use Jiko\Repo\Post\EloquentPost;
use Illuminate\Support\ServiceProvider;

class RepoServiceProvider extends ServiceProvider {
  public function register()
  {
    $app = $this->app;

    $app->bind('Jiko\Repo\File\CloudFileInterface', function($app)
    {
      $file = new GoogleDriveFile(
        new File
      );
    });

    $app->bind('Jiko\Repo\Post\PostInterface', function($app)
    {
      $post = new EloquentPost(
        new Post,
        $app->make('Jiko\Repo\Tag\TagInterface')
      );

      if( $app['config']->get('is_admin', false) == false )
      {
        $post = new CacheDecorator(
          $post,
          new LaravelCache($app['cache'], 'posts', 10)
        );
      }
    });

    $app->bind('Jiko\Repo\Tag\TagInterface', function($app)
    {
      return new EloquentTag(
        new Tag,
        new LaravelCache($app['cache'], 'tags', 10)
      );
    });

    $app->bind('Jiko\Repo\Status\StatusInterface', function($app)
    {
      return new EloquentStatus(
        new Status
      );
    });

    $app->bind('Jiko\Repo\Amazon\WishlistInterface', function($app)
    {
      return new ScraperWishlist(
        new Wishlist
      );
    });
  }
}