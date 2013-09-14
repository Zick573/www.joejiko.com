<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script>
{literal}
$(document).ready(function(){
// First, parse the query string
{/literal}
var twitter = '{$twitter}';
{literal}
var params = {}, queryString = location.hash.substring(1),
    regex = /([^&=]+)=([^&]*)/g, m;
while (m = regex.exec(queryString)) {
  params[decodeURIComponent(m[1])] = decodeURIComponent(m[2]);
}

window.opener.postMessage(twitter,"http://joejiko.com");
close();
});
{/literal}
</script>