{foreach $page as $content}
  {if $content.module == 'Auth'}
    {if isset($content.dashboard)}
	<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
	<h2>{$administration}</h2>
	<p>
	<table style="border-collapse: collapse; width: 100%;">
		{foreach $content.categories as $categories}
            <tr style="border-top: 1px solid #000;">
            {foreach $categories.buttons as $buttons}
    			<td>
    				<i class="fa {$buttons.icon}" style="font-size: 26pt;" title="{$buttons.description}">
    					<br /><a href="{$buttons.link}" style="font-size: 9pt;">{$buttons.name}</a>
    				</i>
    			</td>
            {/foreach}
            </tr>
		{/foreach}
	</table>
	</p>
    {/if}
  {/if}
{/foreach}