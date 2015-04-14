<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>{$site.settings.site_name}</title>
</head>
<body>
	{foreach $page as $block}
		{assign "article" $block.content.article}
		{include $block.content.template}
	{/foreach}
</body>
</html>
