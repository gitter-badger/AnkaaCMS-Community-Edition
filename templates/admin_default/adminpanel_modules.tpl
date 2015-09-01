<div class="container-fluid">
  <div class="row">
    <div class="col-sm-3 col-md-2 sidebar">
      <ul class="nav nav-sidebar">
        {foreach $modules.leftmenu as $list}
        <li><a href="{$site.settings.site_url}admin/modules/{$list.name}">{$list.title}</a></li>
        {/foreach}
      </ul>
    </div>
    {include $modules.current_template}
  </div>
</div>
