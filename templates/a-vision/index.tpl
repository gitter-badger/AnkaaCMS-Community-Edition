<html>
<head>
	<title>{$site.settings.site_name}</title>
	<base href="{$site.settings.site_url}" />
</head>
<body>
	<div id="container">
		<div id="header">
			<img src="{$site.settings.site_logo}" />
		</div>
		<div id="topmenu">
			<ul>
			{foreach $menu.top as $item}                                                                                         
                <li><a href="{$item.href}" title="{$item.title}" class="{$item.class}">{$item.name}</a></li>
            {/foreach}
            </ul>
		</div>
		<div id="page">
		{foreach $page as $block}
            {include $block.content.template}
        {/foreach}
		</div>
		<div id="footer">
			{$site.settings.site_footer}
		</div>
	</div>
</body>
</html>