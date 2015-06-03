<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$site.settings.site_name}</title>
<base href="{$site.settings.site_url}" />
<link rel="stylesheet" type="text/css" href="/templates/acalia/style.css" media="screen" />
<script type="text/javascript" src="templates/acalia/js/jquery.core.js"></script>
<script type="text/javascript" src="templates/acalia/js/jquery.superfish.js"></script>
<script type="text/javascript" src="templates/acalia/js/jquery.jcarousel.pack.js"></script>
<script type="text/javascript" src="templates/acalia/js/jquery.easing.js"></script>
<script type="text/javascript" src="templates/acalia/js/jquery.scripts.js"></script>
</head>

<body>
<div id="wrap">
    <div class="top_corner"></div>
    <div id="main_container">
    
        <div id="header">
            <div id="logo"><a href="/"><img src="{$site.settings.site_logo}" alt="" title="" border="0" /></a></div>      
            <div id="menu">
              {debug}
                <ul>   
                {foreach $menu.top as $item}                                                                                         
                    <li><a href="{$item.href}" title="{$item.title}" class="{$item.class}">{$item.name}</a></li>
                {/foreach}
                </ul>
            </div>
            
        
        </div>
        
        
        <div class="middle_banner">               
          {include $header_imageSlider.template}
        </div><!---------------------------------end of middle banner-------------------------------->
        
        
        <div class="center_content">
        
        {foreach $page as $block}
            {include $block.content.template}
        {/foreach}

        <div class="clear"></div>
        </div>
        
        <div class="footer">
            {$site.settings.site_footer}
            <div class="footer_links">
            {foreach $menu.footer as $item}     
                <a class="{$item.class}" href="{$item.href}" title="{$item.title}">{$item.name}</a>
            {/foreach}
        	</div>
        </div>

    </div>
</div>
</body>
</html>
