<div class="container-fluid">
  <div class="row">
    <div class="col-sm-3 col-md-2 sidebar">
      <ul class="nav nav-sidebar">
        {foreach $page.leftmenu as $list}
        <li><a href="{$site.settings.site_url}admin/page/{$list.function}">{$list.name}</a></li>
        {/foreach}
      </ul>
    </div>
    {include $page.current_template}
  </div>
</div>