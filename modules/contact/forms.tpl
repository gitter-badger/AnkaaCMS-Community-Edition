{foreach $page as $content}
  {if $content.module == 'Contact'}
    <table>
    {foreach $content.fields as $field}
      <tr>
      {if $field.type == 'text'}
        <td>
          {$field.title}
        </td>
        <td>
          <input type="text" value="" name="{$field.name}" />
        </td>
      {elseif $field.type == 'password'}
      
      {elseif $field.type == 'textarea'}
        <td>
          {$field.title}
        </td>
        <td>
          <textarea name="{$field.name}"></textarea>
        </td>
      {elseif $field.type == 'submit'}
        <td>
        </td>
        <td>
          <input type="submit" value="{$field.title}" name="{$field.name}" />
        </td>
      {/if}
      </tr>
    {/foreach}
    </table>
  {/if}
{/foreach}