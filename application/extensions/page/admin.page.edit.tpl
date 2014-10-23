<center><a href="{$lang}/Admin/page" >Terug</a></center>
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
{foreach $page as $content}
        {foreach $content.page as $pages}
            {$pages.name}<br />
        {/foreach}
{/foreach}