<!DOCTYPE html>
<html>
<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{$site.settings.site_name}</title>
    <base href="{$site.settings.site_url}admin/">
    <!-- Bootstrap -->
    <link href="../libraries/twbs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
  	padding-top: 50px;
	}
	.page {
	  padding: 40px 15px;
	}
  .form-signin{
    margin: 0 auto;
    max-width: 400px;
  }
    </style>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

<body>
	{debug}

  {if $user.loggedin === TRUE}
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">{$site.settings.site_name}</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">{$site.settings.site_name}</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            {foreach $adminpanel.menu.top as $item}
              <li class="{if $item.current == TRUE}active{/if}"><a href="{$site.settings.site_url}admin/{$item.class}">{$item.name}</a></li>
            {/foreach}
          </ul>
          <ul id="navbar" class="nav navbar-nav navbar-right">
            <li><a href="{$site.settings.site_url}user/logout">Log out</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
    {foreach $messages as $message}
      {if isset($message.text)}
        {if $message.type = 'error'}
          <div class="alert alert-danger fade in">
            <a onClick="$().alert('close')" class="close" data-dismiss="alert">&times;</a>
            <strong>Error!</strong>
          {elseif $message.type = 'warning'}
          <div class="alert alert-warning fade in">
            <a href="{$site.settings.site_url}{$request}" class="close" data-dismiss="alert">&times;</a>
            <strong>Warning!</strong>
          {elseif $message.type = 'success'}
          <div class="alert alert-success fade in">
            <a href="{$site.settings.site_url}{$request}" class="close" data-dismiss="alert">&times;</a>
            <strong>Success!</strong>
          {elseif $message.type = 'note'}
          <div class="alert alert-note fade in">
            <a href="{$site.settings.site_url}{$request}" class="close" data-dismiss="alert">&times;</a>
            <strong>Note!</strong>
        {/if}
            {$message.text}
          </div>
      {/if}
    {/foreach}
    {include $adminpanel.current_template}
  {else}
    {include './login.tpl'}
  {/if}



    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
</body>
</html>