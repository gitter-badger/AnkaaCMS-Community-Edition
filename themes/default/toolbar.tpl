{if $toolbar == true}
<link rel="stylesheet" href="themes/default/font-awesome.min.css" type="text/css" />
<link rel="stylesheet" href="themes/default/toolbar.css" media="screen"/>
<!--<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js" ></script>-->
<div id="toolbar">
    <ul>
        <li>
            Pages
            <ul>
            {foreach $subMenuPages as $page}
                <li>
                    {$page.slug}
                    <ul class="suba">
                    {foreach $page.modules as $modules}
                        <li>{$modules.name}
                        <ul class="subb">
                            <li><a href={$lang}/Admin/edit/{$page.slug}/{$modules.id}>{$edit}</a></li>
                            <li>Delete</li>
                        </ul>
                        </li>
                    {/foreach}
                        <li>+ Add Module</li>
                        <li>/ Edit Page</li>
                        <li>- Delete Page</li>
                    </ul>
                </li>
                
            {/foreach}
                <li>+ Add Page</li>
            </ul>
        </li>
        <li>
            Users
        </li>
        <li>
            Languages
        </li>
    </ul>
    <a href="?logout" title="Logout" id="logout" ><div class="icon-signout toolbar-button"></div></a>
</div>
{/if}