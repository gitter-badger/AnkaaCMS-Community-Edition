{foreach $page as $content}
    <table>
        <tr><td><a href="{$lang}/Admin/Article/new/" class="fa fa-plus" style="font-size: 16pt;" title="{$new}"></a></td></tr>
    </table>
    <table id="article-list" style="border-collapse: collapse; width: 100%;">
        <tr style="border-bottom: 1px solid #000;">
            <th>{$title}</th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
        </tr>
        {foreach $content.Article as $article}
            <tr style="border-bottom: 1px solid #000;">
                <td>
                    <strong>{$article.title}</strong><br />
                    <i>{$article.subtitle}</i>
                </td>
                <td>
                    <a href="{$lang}/Admin/Article/edit/{$article.id}" class="fa fa-pencil-square-o" style="font-size: 16pt;" title="{$edit}"></a>
                </td>
                <td>
                    <a href="{$lang}/Admin/Article/remove/{$article.id}" class="fa fa-times" style="font-size: 16pt;" title="{$remove}"></a>
                </td>
                <td>
                    <a href="{$lang}/Admin/Article/edit/{$article.id}" class="fa fa-angle-down" style="font-size: 16pt;" title="{$options}"></a>
                </td>
            </tr>
        {/foreach}
    </table>
{/foreach}