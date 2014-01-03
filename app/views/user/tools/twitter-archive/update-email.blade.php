<div class="tweet">
<h1>Nice! Your account is ready.</h1>
<h2>One more thing though..</h2>

<p>Twitter doesn't provide me with your email. Please enter it below:</p>

{{ Form::open() }}
{{ Form::text('email', '', ['type' => 'email']) }}
{{ Form::button('Save', ['type' => 'submit']) }}
{{ Form::close() }}

<p>Note: You can <a href="/user/tools/twitter-archive">skip this step</a> but <em>some features of the site will be unavailable</em> to you.</p>

</div>