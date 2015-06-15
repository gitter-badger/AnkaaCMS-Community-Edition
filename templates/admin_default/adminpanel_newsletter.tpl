<div class="container-fluid">
  <div class="row">
    <div class="col-sm-3 col-md-2 sidebar">
      <ul class="nav nav-sidebar">
        {foreach $newsletter.leftmenu as $list}
        <li><a href="{$site.settings.site_url}admin/newsletter/{$list.function}">{$list.name}</a></li>
        {/foreach}
      </ul>
    </div>
    {include $newsletter.current_template}
  </div>
</div>