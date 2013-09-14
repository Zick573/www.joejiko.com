@extends('layouts.master');
@section('page.title')
  Subscribe to stuff
@stop
@section('content')
<h1>Subscribe</h1>
<h2>(beta* **)</h2>
<p>I've created a few categories for you to subscribe to.<br>
Put your email and then select whatever categories you'd like to receive notifications from.<br>
If you have any <a href="/questions/ask">questions</a>, visit the <a href="/contact">contact form</a></p>
<form>
  <p>
    <input type="email" placeholder="email here" />
  </p>
  <p>
    <label><input type="checkbox" /> photos</label>
    <label><input type="checkbox" /> instagram</label>
    <label><input type="checkbox" /> blog posts</label>
    <label><input type="checkbox" /> links</label>
    <label><input type="checkbox" /> microblogging (status updates)</label>
  </p>
  <button type="submit">subscribe</button>
</form>
<aside>
  <p>*this page may not work as expressed or intended.</p>
  <p>**it's not a bug, it's a feature!</p>
</aside>
@stop