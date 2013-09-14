							<footer></footer>
          	</div><!-- /#content .grids -->
          </div><!-- /.col.main -->
        </div><!-- /.grid -->
      </div><!-- /.fluid -->
    </div><!-- /#container -->
    {include file='./msidebar.tpl'}
    <footer class="grids site-footer ">
      <div class="grid-8 outro">
        <p>
          <em>"Cute is a word for things that can't kill you."</em><br>
          Unless otherwise noted, <strong>All content on this site copyright &copy;2009&ndash;2013 <a href="http://jiko.us/12UviYW">Joe Jiko</a></strong>. All rights reserved and such. "Joe Jiko" is a web developer, graphic designer, IT professional/technology geek located in St. Petersburg near Tampa, Florida. This website was designed, written, and developed by Joe Jiko (me) for the latest version of <a href="http://jiko.us/181Eror" target="_blank">Mozilla Firefox</a> (not tested in other browsers but it will probably work fine in <a href="http://jiko.us/13GLaNA" target="_blank">Google Chrome</a> too). If you're using an inferior web browser, you can go view a screenshot of what you should/could be seeing. If you don't know what a <a href="http://jiko.us/10kcGTT" target="_blank">web browser</a> is, please get off the Internet until you find out or <a href="http://jiko.us/167407z" target="_blank">consult Google</a>. DO NOT call your "IT guy" about it. If you've got something to say, <a href="http://twitter.com/joejiko" target="_blank">send me a tweet</a>.

          What you see here.. by Joe Jiko.<br>
          Thanks for visiting!<br>
          <small>(version 1.02 updated 5/22)</small>
        </p>
      </div>
    	<div class="zeah-accent grid-4" itemscope itemtype="http://schema.org/ImageObject">
        <div class="zeah-accent-bubble">
          <span itemprop="name">Scroll to top, human?</span>
          <div class="zeah-accent-bubble-corner"></div>
        </div>
        <img src="/images/home/zeah-standing.png" alt="zeah the border collie"  itemprop="contentURL">
      </div>
    </footer>
    <script>
  <!-- asynchronous google analytics -->
    var _gaq = _gaq || [];
	var pluginUrl =
	 '//www.google-analytics.com/plugins/ga/inpage_linkid.js';
	_gaq.push(['_require', 'inpage_linkid', pluginUrl]);
    _gaq.push(['_setAccount', 'UA-4976857-2']);
    _gaq.push(['_trackPageview']);

    (function() {
      var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
      ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
      var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
    })();
		</script>
  {if isset($scripts.footer)}
    {foreach $scripts.footer as $uri => $valid}
      {if $valid}
        <script src="{$uri}"></script>
      {/if}
    {/foreach}
  {/if}
  {*include file='vendor/meebo/mbar-footer.tpl'*}
  </body>
</html>