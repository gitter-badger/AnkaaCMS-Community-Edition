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
        
        
         
        <div class="home_section_left">
            <img src="templates/acalia/images/icon1.gif" alt="" title="" class="home_section_icon" border="0">
                            
                <h2 class="home_title">What we do</h2>
                <div class="home_subtitle">Consectetur adipisicing elit</div>
    
                <div class="home_section_thumb">
                <img src="templates/acalia/images/home_section_thumb1.jpg" alt="" title="" border="0">
                </div>
                <p><span>Lorem ipsum dolor sit ame</span><br>
                Consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore. 
                <br> <br>
                <span>Lorem ipsum dolor sit ame</span><br>
                Consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore. 
                </p>
                <a href="" class="more"><img src="templates/acalia/images/more.gif" alt="" title="" border="0"></a>
        <div class="clear"></div>
        </div>
        
        
        <div class="home_section_left">
            <img src="templates/acalia/images/icon2.gif" alt="" title="" class="home_section_icon" border="0">
                            
                <h2 class="home_title">Who we are</h2>
                <div class="home_subtitle">Tempor incididunt ut labore</div>
    
                <div class="home_section_thumb">
                <img src="templates/acalia/images/home_section_thumb2.jpg" alt="" title="" border="0">
                </div>
                <p><span>Lorem ipsum dolor sit ame</span><br>
                Consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore. 
                <br> <br>
                <span>Lorem ipsum dolor sit ame</span><br>
                Consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore. 
                </p>
                <a href="" class="more"><img src="templates/acalia/images/more.gif" alt="" title="" border="0"></a>
        <div class="clear"></div>
        </div>
        
        <div class="home_section_left">
            <img src="templates/acalia/images/icon3.gif" alt="" title="" class="home_section_icon" border="0">
                            
                <h2 class="home_title">Special services</h2>
                <div class="home_subtitle">Sed do eiusmod tempor</div>
    
                <div class="home_section_thumb">
                <img src="templates/acalia/images/home_section_thumb3.jpg" alt="" title="" border="0">
                </div>
                <p><span>Lorem ipsum dolor sit ame</span><br>
                Consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore. 
                <br> <br>
                <span>Lorem ipsum dolor sit ame</span><br>
                Consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore. 
                </p>
                <a href="" class="more"><img src="templates/acalia/images/more.gif" alt="" title="" border="0"></a>
        <div class="clear"></div>
        </div>
        
            
            <div class="left_block_wide">
                <h2>Latest Projects</h2>
                
                <a href="#"><img src="templates/acalia/images/p1.jpg" alt="" title="" border="0" class="projects" /></a>
                <a href="#"><img src="templates/acalia/images/p2.jpg" alt="" title="" border="0" class="projects" /></a>
                <a href="#"><img src="templates/acalia/images/p3.jpg" alt="" title="" border="0" class="projects" /></a>
            
            
            </div>
            
            <div class="right_block">
            	<h2>Newsletter Sign up</h2>
                <p>
                Lorem ipsum dolor sit amet, consectetur adipisicing elit consequat.
                </p>
                <form id="newsletter">
                <input type="text" name="" class="newsletter_input" />
                <input type="submit" name="" class="newsletter_submit" value="Sign up" />
                </form>
            
            </div>
        
        
        
   
        
        <div class="clear"></div>
        </div>
        
        <div class="footer">
        	<div class="copyright"><a href="http://csstemplatesmarket.com/" target="_blank">Free CSS Templates</a> | <a href="http://csstemplatesmarket.com/" target="_blank">by CssTemplatesMarket</a></div>
        
        	<div class="footer_links">
                <a class="current" href="index.html" title="">Home</a>
                <a href="#" title="">About Company</a>
                <a href="#" title="">Projects</a>
                <a href="#" title="">Clients</a>
                <a href="#" title="">Testimonials</a>
                <a href="contact.html" title="">Contact</a>            
            </div>
        </div>
      
      
    
    </div>
</div>
</body>
</html>
