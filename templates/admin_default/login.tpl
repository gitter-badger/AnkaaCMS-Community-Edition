
<div class="container">
  <div class="page">
    {$user.message}
    <form class="{$user.login.form.class}" method="{$user.login.form.method}" name="{$user.login.form.name}" action="{$user.login.form.action}">
      {foreach $user.login.form.fields as $field}
        {if $field.type == 'checkbox'}
        <div class="checkbox">
          <label>
            <{$field.tag} type="{$field.type}" id="{$field.id}" class="{$field.class}" name="{$field.name}" value="{$field.value}" {if $field.required == TRUE} required {/if} {if $field.autofocus == TRUE} autofocus {/if}> {$field.label}
          </label>
        </div>
        {elseif $field.type == 'submit'}
          <br />
          <{$field.tag} class="{$field.class}" type="{$field.type}" value="{$field.value}"</{$field.tag}>
        {elseif $field.tag == 'h2'}
          <{$field.tag} class="{$field.class}">{$field.value}</{$field.tag}>
        {else}
          <label for="{$field.id}" class="{$field.labelclass}">{$field.label}</label>
          <{$field.tag} name="{$field.name}" type="{$field.type}" id="{$field.id}" class="{$field.class}" placeholder="{$field.placeholder}" value="{$field.value}" {if $field.required == TRUE} required {/if} {if $field.autofocus == TRUE} autofocus {/if}>
        {/if}
      {/foreach}
    </form>
  </div>
</div>


