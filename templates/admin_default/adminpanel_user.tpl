<div class="container-fluid">
  <div class="row">
    <div class="col-sm-3 col-md-2 sidebar">
      <ul class="nav nav-sidebar">
        {foreach $user.admin.leftmenu as $list}
        <li><a href="{$site.settings.site_url}admin/user/{$list.function}">{$list.name}</a></li>
        {/foreach}
      </ul>
    </div>
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        <h1>{$adminpanel.current} - {$user.admin.current_function}</h1>
        <p class="lead">
          {include $user.admin.current_template}
        </p>
    </div>
  </div>
</div>