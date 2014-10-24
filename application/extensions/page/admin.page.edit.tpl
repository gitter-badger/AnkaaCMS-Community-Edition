<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
<br />
<table style="width: 100%; border-collapse: collapse;">
{foreach $page as $content}
	<tr style="border-bottom: 1px solid #000;border-top: 1px solid #000;">
		<td>
			<center><a href="" class="fa fa-plus" title="{$module.add}"></a></center>
		</td>
	</tr>
	{foreach $content.page as $pages}
	<tr style="border-bottom: 1px solid #000;">
		<td>
            {include file=$pages.edit}
            <br />
        </td>
    </tr>
    <tr style="border-bottom: 1px solid #000;">
		<td>
			<center><a href="" class="fa fa-plus" title="{$module.add}"></a></center>
		</td>
	</tr>
    {/foreach}
{/foreach}
</table>