<?php
/*
Plugin Name: WP Email-to-Facebook plugin
Plugin URI: http://www.xaviermedia.com/wordpress/plugins/wp-email-to-facebook.php
Description: This plugin will send an email to a page (at Facebook) you may have <a href="http://www.facebook.com/pages/create.php" TARGET="_blank">created for your company, brand, band, school or cause/topic</A> every time you post a new post in your blog.
Author: Xavier Media&reg;
Version: 1.1.3
Author URI: http://www.xaviermedia.com/
*/

add_action('publish_post', 'xavierfb_savepost');

class CurlRequest
{
    private $ch;
    /**
     * Init curl session
     *
     * $params = array('url' => '',
     *                    'host' => '',
     *                   'header' => '',
     *                   'method' => '',
     *                   'referer' => '',
     *                   'cookie' => '',
     *                   'post_fields' => '',
     *                    ['login' => '',]
     *                    ['password' => '',]     
     *                   'timeout' => 0
     *                   );
     */               
    public function init($params)
    {
        $this->ch = curl_init();
        $user_agent = 'Mozilla/5.0 (Windows; U;Windows NT 5.1; en-us; rv:1.8.0.9) Gecko/20061206 Firefox/1.5.0.9';
        $header = array(
        "Accept: text/xml,application/xml,application/xhtml+xml,text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5",
        "Accept-Language: en-us;q=0.7,en;q=0.3",
        "Accept-Charset: windows-1251,utf-8;q=0.7,*;q=0.7",
        "Keep-Alive: 300");
        if (isset($params['host']) && $params['host'])      $header[]="Host: ".$host;
        if (isset($params['header']) && $params['header']) $header[]=$params['header'];
       
        @curl_setopt ( $this -> ch , CURLOPT_RETURNTRANSFER , 1 );
        @curl_setopt ( $this -> ch , CURLOPT_VERBOSE , 1 );
        @curl_setopt ( $this -> ch , CURLOPT_HEADER , 1 );
       
        if ($params['method'] == "HEAD") @curl_setopt($this -> ch,CURLOPT_NOBODY,1);
        @curl_setopt ( $this -> ch, CURLOPT_FOLLOWLOCATION, 1);
        @curl_setopt ( $this -> ch , CURLOPT_HTTPHEADER, $header );
        if ($params['referer'])    @curl_setopt ($this -> ch , CURLOPT_REFERER, $params['referer'] );
        @curl_setopt ( $this -> ch , CURLOPT_USERAGENT, $user_agent);
        if ($params['cookie'])    @curl_setopt ($this -> ch , CURLOPT_COOKIE, $params['cookie']);

        if ( $params['method'] == "POST" )
        {
            curl_setopt( $this -> ch, CURLOPT_POST, true );
            curl_setopt( $this -> ch, CURLOPT_POSTFIELDS, $params['post_fields'] );
        }
        @curl_setopt( $this -> ch, CURLOPT_URL, $params['url']);
        @curl_setopt ( $this -> ch , CURLOPT_SSL_VERIFYPEER, 0 );
        @curl_setopt ( $this -> ch , CURLOPT_SSL_VERIFYHOST, 0 );
        if (isset($params['login']) & isset($params['password']))
            @curl_setopt($this -> ch , CURLOPT_USERPWD,$params['login'].':'.$params['password']);
        @curl_setopt ( $this -> ch , CURLOPT_TIMEOUT, $params['timeout']);
    }
   
    /**
     * Make curl request
     *
     * @return array  'header','body','curl_error','http_code','last_url'
     */
    public function exec()
    {
        $response = curl_exec($this->ch);
        $error = curl_error($this->ch);
        $result = array( 'header' => '',
                         'body' => '',
                         'curl_error' => '',
                         'http_code' => '',
                         'last_url' => '');
        if ( $error != "" )
        {
            $result['curl_error'] = $error;
            return $result;
        }
       
        $header_size = curl_getinfo($this->ch,CURLINFO_HEADER_SIZE);
        $result['header'] = substr($response, 0, $header_size);
        $result['body'] = substr( $response, $header_size );
        $result['http_code'] = curl_getinfo($this -> ch,CURLINFO_HTTP_CODE);
        $result['last_url'] = curl_getinfo($this -> ch,CURLINFO_EFFECTIVE_URL);
        return $result;
    }
}


function xavierfb_savepost($post_id)
{
	$xavierfb = get_post_meta($post_id, 'xavierfb', true);
	if (!($xavierfb == 'yes')) {
		query_posts('p=' . $post_id);

		if (have_posts()) 
		{
			$opt  = get_option('xavierfboptions');
			$options = unserialize($opt);

			the_post();

			$link = get_permalink();
	
			if ($options[apitype] == "is.gd" || $options[apitype] == "rt.nu")
			{
				$link = file_get_contents('http://is.gd/api.php?longurl='. urlencode($link));
			}
			else if ($options[apitype] == "wp.me")
			{
				$link = wp_get_shortlink();
			}
			else if ($options[apitype] == "metamark.net")
			{
				$link = file_get_contents('http://metamark.net/api/rest/simple?long_url='. urlencode($link));
			}
			else if ($options[apitype] == "mrte.ch")
			{
				$jsonstring = file_get_contents('http://api.mrte.ch/go.php?action=shorturl&format=json&url='. urlencode($link));
	
				$json = json_decode($jsonstring,true);

				if ($json[statusCode] == "200")
				{
					$link = $json[shorturl];
				}
			}
			else if ($options[apitype] == "tinyurl.com")
			{
				$link = file_get_contents('http://tinyurl.com/api-create.php?url=' . $link);
			}
			else if ($options[apitype] == "2ve.org")
			{
				$jsonstring = file_get_contents('http://api.2ve.org/api.php?action=makeshorter&fileformat=json&longlink='. urlencode($link) .'&api='. $options[apiid] .'&key='. $options[apikey]);
	
				$json = json_decode($jsonstring,true);

				if ($json[responsecode] == "200")
				{
					$link = $json[shortlink];
				}
			}
			else if ($options[apitype] == "bit.ly")
			{
				$jsonstring = file_get_contents('http://api.bit.ly/shorten?version=2.0.1&longUrl='. urlencode($link) .'&login='. $options[apiid] .'&apiKey='. $options[apikey]);

				$json = json_decode($jsonstring,true);

				if ($json[statusCode] == "OK")
				{
					$link = $json[results][$link][shortUrl];
				}
			}

			$title = get_the_title();

			$email_already_sent = array();


			$categories = get_the_category(); 
			foreach($categories as $cat) 
			{
				$catnicename = $cat->category_nicename;

				$fbemail = $options[facebookemails][$catnicename];

				if ($email_already_sent[$fbemail] == 1 || $fbemail == "")
				{

				}
				else
				{
					$email_already_sent[$fbemail] = 1;
					$from = get_option('admin_email');
					if ($options[mailtype] == "wp")
					{
						$fbheaders = array(
							'From: '. $from
							);	
						wp_mail($fbemail,"$title $link"," ",$fbheaders);
					}
					else
					{
						$fbheaders = 'From: '. $from;
						mail($fbemail,"$title $link"," ",$fbheaders);
					}
				}

			}
			add_post_meta($post_id, 'xavierfb', 'yes');

		}
	}
}

function xavierfb_options()
{

      	if ( 'save' == $_REQUEST['action'] ) 
		{

			$opt  = get_option('xavierfboptions');
			$optionsold = unserialize($opt);


			$options = array(
				"apitype" => $_REQUEST[apitype],
				"apiid" => $_REQUEST[apiid],
				"apikey" => $_REQUEST[apikey],
				"pluginlink" => $_REQUEST[pluginlink],
				"mailtype" => $_REQUEST[mailtype],
				"facebookemails" => array()
				);

			$options[facebookemails] = $_REQUEST[facebookemails];

			$opt = serialize($options);
			update_option('xavierfboptions', $opt);
	}
	else
	{
		$opt  = get_option('xavierfboptions');
		$options = unserialize($opt);
	}

	?>

	<STYLE>
	.hiddenfield 
	{
		display:none;
	}
	.nothiddenfield 
	{
	}
	</STYLE>

	<div class="updated fade-ff0000"><p><strong>Need web hosting for your blog?</strong> Get 10 Gb web space and unlimited bandwidth for only $3.40/month at <a href="http://2ve.org/xMY3/" target="_blank">eXavier.com</a>, or get the Ultimate Plan with unlimited space and bandwidth for only $14.99/month.</p></div>


	<form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post" name=pf>
	<input type="hidden" name="action" value="save" />



	<h1>WP Email-to-Facebook Options</h2>
	If you get stuck on any of these options, please have a look at the <a href="http://www.xaviermedia.com/wordpress/plugins/wp-email-to-facebook.php">WP Email-to-Facebook plugin page</a> or visit the <a href="http://www.xavierforum.com/php-&-cgi-scripts-f3.html">support forum</a>.

	<h2>Setup instructions</h3>

	<P>To use this plugin you need to have a page at Facebook for your company, brand, band, cause, topic or whater you want. Pages can be created at <A HREF="http://www.facebook.com/pages/create.php" TARGET="_blank">this page</A>.</P>

	<P>Once you have the page setup you can go to "Edit page" > "Mobile" and there you see your secret email address. It should be something like <B><I>[Random characters]</I>@m.facebook.com</B>.</P>

	<P>You can specify one email address per category. If you would like to post only to one page no matter how many categries you have you just specify the same email address in all fields. In that case only ONE email will be sent. If you have one email for Category A and another email for Category B and the post you publish got both categories, two emails will be sent (one to each page). You can read more about this at <a href="http://www.xaviermedia.com/wordpress/plugins/wp-email-to-facebook.php" TARGET="_blank">the plugin page</a>.</P>

	<h2>Categories and email addresses</h3>

	<table class="widefat post fixed" cellspacing="0">	
	<thead>
		<tr>
			<th id="category" class="manage-column column-title" style="" scope="col">Category</th>
			<th id="email" class="manage-column column-title" style="" scope="col">Facebook page email</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<th id="category" class="manage-column column-title" style="" scope="col">Category</th>
			<th id="email" class="manage-column column-title" style="" scope="col">Facebook page email</th>
		</tr>
	</tfoot>	
	<tbody>	<?
	$allcategories = get_categories('type=post&hide_empty=0');
	$i = 0;
	foreach ($allcategories as $cat) 
	{
		$plugincategory = $cat->category_nicename;
		echo '<tr>
			<th id="category" class="manage-column column-title" style="" scope="col"><INPUT TYPE=hidden NAME=plugincategory['. $i .'] VALUE="'. $plugincategory .'">'. $cat->cat_name .'</th>
			<th id="category" class="manage-column column-title" style="" scope="col"><INPUT TYPE=text NAME=facebookemails['. $plugincategory .'] VALUE="'. $options[facebookemails][$plugincategory] .'" SIZE=60></th>
		</tr>';
		$i++;
	}





?>	</tbody>
	</table>	

	<h2>Link shortener service</h3>


	<p>Select the link shortener you would like to use.</p>
	<p>
	<INPUT TYPE=radio NAME=apitype VALUE="" <?php	if ($options[apitype] == "") { echo ' CHECKED'; } ?> onClick="javascript:document.getElementById('apikeys').className = 'hiddenfield';"> <B>Don't</B> use any service to get short links<BR />

	<INPUT TYPE=radio NAME=apitype VALUE="is.gd" <?php	if ($options[apitype] == "is.gd" || $options[apitype] == "rt.nu") { echo ' CHECKED'; } ?> onClick="javascript:document.getElementById('apikeys').className = 'hiddenfield';"> <A HREF="http://is.gd/" TARGET="_blank">is.gd</A><BR />

	<INPUT TYPE=radio NAME=apitype VALUE="metamark.net" <?php	if ($options[apitype] == "metamark.net") { echo ' CHECKED'; } ?> onClick="javascript:document.getElementById('apikeys').className = 'hiddenfield';"> <A HREF="http://metamark.net/" TARGET="_blank">metamark.net</A><BR />

	<INPUT TYPE=radio NAME=apitype VALUE="mrte.ch" <?php	if ($options[apitype] == "mrte.ch") { echo ' CHECKED'; } ?> onClick="javascript:document.getElementById('apikeys').className = 'hiddenfield';"> <A HREF="http://mrte.ch/" TARGET="_blank">mrte.ch</A><BR />

	<INPUT TYPE=radio NAME=apitype VALUE="tinyurl.com" <?php	if ($options[apitype] == "tinyurl.com") { echo ' CHECKED'; } ?> onClick="javascript:document.getElementById('apikeys').className = 'hiddenfield';"> <A HREF="http://tinyurl.com/" TARGET="_blank">tinyurl.com</A><BR />

	<INPUT TYPE=radio NAME=apitype VALUE="2ve.org" <?php	if ($options[apitype] == "2ve.org") { echo ' CHECKED'; } ?> onClick="javascript:alert('Don\'t forget to fill in the API ID and API key fields below for this link shortener');document.getElementById('apikeys').className = 'nothiddenfield';"> <A HREF="http://2ve.org/" TARGET="_blank">2ve.org</A> <B>*</B><BR />

	<INPUT TYPE=radio NAME=apitype VALUE="bit.ly" <?php	if ($options[apitype] == "bit.ly") { echo ' CHECKED'; } ?> onClick="javascript:alert('Don\'t forget to fill in the API ID and API key fields below for this link shortener');document.getElementById('apikeys').className = 'nothiddenfield';"> <A HREF="http://bit.ly/" TARGET="_blank">bit.ly</A> <B>*</B><BR />

	<BR /><B>*</B> = This link shortener service require an <B>API ID</B> and/or an <B>API Key</B> to work. Please see the documentation at the link shorteners web site.

	<p id=apikeys class=<?php if($options[apitype] == "2ve.org" || $options[apitype] == "bit.ly") { echo 'nothiddenfield'; } else { echo 'hiddenfield'; } ?>>
	<B>Link Shortener API ID and API Key:</B><BR />
	Depending on what you selected above, some link shorteners require that you sign up at their web site to get an API ID (or API login) and/or an API key. For more information on what's required to use the link shortener you've selected, please see the documentation at the web site of the link shortener.<BR />
	API ID: &nbsp; <INPUT TYPE=text NAME=apiid VALUE="<?php echo $options[apiid]; ?>" SIZE=40> (this may sometimes be called "login")<BR />		
	API Key: <INPUT TYPE=text NAME=apikey VALUE="<?php echo $options[apikey]; ?>" SIZE=40> (if just a key is required, leave the ID field blank)<BR />	
	</p>	

	<h2>Email service</h3>
	<p>Select here if you would like to use the PHPs mail function or the builtin Wordpress function. As standard this plugin is using the PHPs mail function and if that's working for you there's no need to change. However if you have problems sending emails with PHPs mail function, then please feel free to try the Wordpress mail funktion instead.
	</p>
	<p>
	<INPUT TYPE=radio NAME=mailtype VALUE="" <?php	if ($options[mailtype] == "") { echo ' CHECKED'; } ?>> <B>PHP's mail function:</B> mail()<BR />

	<INPUT TYPE=radio NAME=mailtype VALUE="wp" <?php	if ($options[mailtype] == "wp") { echo ' CHECKED'; } ?>> <B>Wordpress' mail function:</B> wp_mail()<BR />
	</p>



	<div class="submit"><input type="submit" name="info_update" value="Update Options" class="button-primary"  /></div></form>
	<a target="_blank" href="http://feed.xaviermedia.com/xm-wordpress-stuff/"><img src="http://feeds.feedburner.com/xm-wordpress-stuff.1.gif" alt="XavierMedia.com - Wordpress Stuff" style="border:0"></a><BR/>

	<h2>Wordpress plugins from Xavier Media&reg;</h2>
	<UL>
	<li><a href="http://wordpress.org/extend/plugins/wp-statusnet/" TARGET="_blank">WP-Status.net</a> - Posts your blog posts to one or multiple Status.net servers and even to Twitter 
	<li><a href="http://wordpress.org/extend/plugins/wp-email-to-facebook/" TARGET="_blank">WP Email-to-Facebook</a> - Posts your blog posts to one or multiple Facebook pages from your WordPress blog 
	<li><a href="http://wordpress.org/extend/plugins/wp-check-spammers/" TARGET="_blank">WP-Check Spammers</a> - Check comment against the SpamBot Search Tool using the IP address, the email and the name of the poster as search criteria 
	<li><a href="http://wordpress.org/extend/plugins/xm-backup/" TARGET="_blank">XM Backup</a> - Do backups of your Wordpress database and files in the uploads folder. Backups can be saved to Dropbox, FTP accounts or emailed
	</UL>
	



	<?php

}


function xavierfb_addoption()
{
	if (function_exists('add_options_page')) 
	{
		add_options_page('WP Email-to-Facebook', 'WP Email-to-Facebook', 8, basename(__FILE__), 'xavierfb_options');
    	}	
}



add_action('admin_menu', 'xavierfb_addoption');

?>