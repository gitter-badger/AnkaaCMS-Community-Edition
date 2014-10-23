{foreach $page as $content}
  {if $content.module == 'Article'}
    <h2>{$content.title}</h2>
    <h3>{$content.subtitle}</h3>
    <div>
        {$content.content}
    </div>
  {/if}
{/foreach}