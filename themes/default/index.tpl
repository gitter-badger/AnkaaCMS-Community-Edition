<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" >


<head>

  <base href="{$http_connection}://{$site_domain}/" />
  
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  
<title>{$site_title} - {$site_slogan}</title>
  
<link rel="stylesheet" href="themes/default/style.css" type="text/css" />

</head>

<body>

  
<div id="page">
    
<div id="header">
      <div id="headerimg">
        <h1>
          <a href="{$http_connection}://{$site_domain}/">
            {$site_title}
          </a>
        </h1>

        <div class="description">
          {$site_slogan}
        </div>
      </div>

    </div>
    
<hr />

    <div id="content" class="narrowcolumn">
    {foreach $page as $items}
      {include $items.template}

    {/foreach}
      <div class="navigation">
        <div class="alignleft">
        </div>

        <div class="alignright">
        </div>

      </div>
    </div>
    <div id="sidebar">
      <div class="pagenav">
        <h2>{$pages}</h2>
        <ul>
        {foreach $menu as $items}

          <li class="page_item">
            <a href="/{$lang}/{$items.slug}" title="{$items.slug}">
              {$items.slug}
            </a>
          </li>
        {/foreach}
        </ul>

      </div>
    </div>

    <hr />
  
  <div id="footer">
      <p>
        {$site_footer}
      </p>

    </div>
  </div>

</body>

</html>