<div class="container-fluid">
  <div class="row">
    <div class="col-sm-3 col-md-2 sidebar">
      <ul class="nav nav-sidebar">
        {foreach $site.admin.leftmenu as $list}
        {if $list.status == 1}
        {$class = 'success'}
        {elseif $list.status == 0}
        {$class = 'danger'}
        {else}
        {$class = 'warning'}
        {/if}
        <li>
            <a class='alert-{$class}' href="{$site.settings.site_url}admin/site/view/{$list.id}">{$list.domain}</a>
        </li>
        {/foreach}
      </ul>
    </div>
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        <h1>{$adminpanel.current} - {$site.admin.current_function}</h1>
        <p class="lead">
          <label class="toggle">
            {if $site.status == 1}
              <input type="checkbox" checked>
            {else}
              <input type="checkbox">
            {/if}
    			  <span class="handle"></span>
    			</label>
        	<table class="table table-striped">
				<tbody>
					<tr>
						<th>Setting</th>
						<th>Value</th>
					</tr>
					{foreach $site.settings_full as $name=>$setting}
					<tr>
						<td>{$setting.name}</td>
						<td contentEditable="true" name="site_values" id="{$setting.id}">{$setting.value}</td>
					</tr>{/foreach}
          <tr>
						<td contentEditable="true" id="new_setting" title="Settings name"></td>
						<td contentEditable="true" name="site_values" id="new_value"></td>
					</tr>
                                        <tr>
                                            <td colspan="2">
                                                <button id="respons" class="btn btn-default btn btn-block" onClick="save_sitesettings();">
                                                    <span class="glyphicon glyphicon-save" aria-hidden="true"></span>
                                                        Save
                                                </button>
                                            </td>
                                        </tr>
				</tbody>
                </table>
        </p>
    </div>
  </div>
</div>
<script>
    var siteurl = window.location.href.split('/');
    function save_sitesettings(){
        var elements = document.getElementsByName('site_values');
        var length = elements.length;
        var settings = {};
        for(var i=0;i<length;i++){
            var setting = elements[i].id;
            if(setting == 'new_value'){
              setting = document.getElementById('new_setting').innerHTML;
            }
            var value = elements[i].innerHTML;
            settings[setting] = value;
            console.log(elements[i].id + ' = ' + elements[i].innerHTML + "\r\n");
        }
        var siteid = siteurl[siteurl.length-1];
        if(siteid == 'site' || siteid == 'undefined'){
          var req = $.post('/admin/site/setSettings/saveall', settings, function( ){
          document.getElementById('respons').innerHTML = '<span class="glyphicon glyphicon-saved" id="respons-span" aria-hidden="true"></span> ' + req.statusText ;
          setTimeout(function(){ document.getElementById('respons').innerHTML = '<span class="glyphicon glyphicon-save" id="respons-span" aria-hidden="true"></span> Save' ; }, 3000);
          })
        } else {
          var req = $.post('/admin/site/setSettings/saveall/'+siteid, settings, function( ){
          document.getElementById('respons').innerHTML = '<span class="glyphicon glyphicon-saved" id="respons-span" aria-hidden="true"></span> ' + req.statusText ;
          setTimeout(function(){ document.getElementById('respons').innerHTML = '<span class="glyphicon glyphicon-save" id="respons-span" aria-hidden="true"></span> Save' ; }, 3000);
          })
        }

    }
</script>
