<center><a href="{$lang}/Admin/" >{$back}</a></center>
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
{foreach $page as $content}
    <table id="article-list" style="border-collapse: collapse; width: 100%;">
        <tr style="border-bottom: 1px solid #000;">
            <th>{$content.title}</th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
        </tr>
        {foreach $content.page as $pages}
            <tr style="border-bottom: 1px solid #000;">
                <td>
                    {$pages.slug}<br />&nbsp;
                </td>
                <td>
                    <a href="Admin/page/edit/{$pages.id}" class="fa fa-pencil-square-o" style="font-size: 16pt;" title="{$edit}"></a>
                </td>
                <td>
                    <a href="Admin/page/#remove&id={$pages.id}" class="fa fa-times" style="font-size: 16pt;" title="{$remove}"></a>
                </td>
                <td>
                    <a href="Admin/page/#options&id={$pages.id}" class="fa fa-angle-down" style="font-size: 16pt;" title="{$options}"></a>
                </td>
            </tr>
        {/foreach}
    </table>
{/foreach}