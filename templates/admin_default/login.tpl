
<div class="container">
  <div class="page">
    <form class="form-signin">
      <h2 class="form-signin-heading">{$user.login.form.head}</h2>
      {foreach $user.login.form.fields as $field}
        {if $field.type == 'checkbox'}
        <div class="checkbox">
          <label>
            <{$field.tag} type="{$field.type}" id="{$field.id}" class="{$field.class}" value="{$field.value}" {if $field.required == TRUE} required {/if} {if $field.autofocus == TRUE} autofocus {/if}> {$field.label}
          </label>
        </div>
        {elseif $field.tag == 'button'}
          <{$field.type} class="{$field.class}" type="{$field.type}">{$field.label}</{$field.type}>
        {else}
          <label for="{$field.id}" class="{$field.labelclass}">{$field.label}</label>
          <{$field.tag} name="{$field.name}" type="{$field.type}" id="{$field.id}" class="{$field.class}" placeholder="{$field.placeholder}" value="{$field.value}" {if $field.required == TRUE} required {/if} {if $field.autofocus == TRUE} autofocus {/if}>
        {/if}
      {/foreach}
    </form>
  </div>
</div>


