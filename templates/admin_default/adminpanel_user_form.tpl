{foreach $user.form as $form}
<form class="{$form.class}" method="{$form.method}" name="{$form.name}" action="{$form.action}">
  {foreach $form.fields as $field}
    {if $field.type == 'checkbox'}
    <div class="checkbox">
      <label>
        <{$field.tag} type="{$field.type}" id="{$field.tag_id}" class="{$field.css_class}" name="{$field.name}" value="{$field.value}" {if $field.required == TRUE} required {/if} {if $field.autofocus == TRUE} autofocus {/if}> {$field.label}
      </label>
    </div>
    {elseif $field.type == 'submit'}
      <br />
      <{$field.tag} class="{$field.css_class}" type="{$field.type}" value="{$field.value}"</{$field.tag}>
    {elseif $field.tag == 'h2' OR $field.tag == 'hr'}
      <{$field.tag} class="{$field.css_class}">{$field.value}</{$field.tag}>
    {elseif $field.tag == 'select'}
    <div class="form-group">
      <label for="{$field.tag_id}" class="{$field.css_labelClass}">{$field.label}</label>
        <div class="{$field.css_class}">
          <{$field.tag} name="{$field.name}" type="{$field.type}" id="{$field.tag_id}" class="form-control" placeholder="{$field.placeholder}" {if $field.required == TRUE} required {/if} {if $field.autofocus == TRUE} autofocus {/if}>
            {foreach $field.value as $value=>$name}
            <option value="{$value}">{$name}</option>
            {/foreach}
          </{$field.tag}>
        </div>
    </div>
    {else}
      <div class="form-group">
        <label for="{$field.tag_id}" class="{$field.css_labelClass}">{$field.label}</label>
        {if $field.css_class != 'form-control'}
        <div class="{$field.css_class}">
          <{$field.tag} name="{$field.name}" type="{$field.type}" id="{$field.tag_id}" class="form-control" placeholder="{$field.placeholder}" value="{$field.value}" {if $field.required == TRUE} required {/if} {if $field.autofocus == TRUE} autofocus {/if}>
        </div>
        {else}
        <{$field.tag} name="{$field.name}" type="{$field.type}" id="{$field.tag_id}" class="{$field.css_class}" placeholder="{$field.placeholder}" value="{$field.value}" {if $field.required == TRUE} required {/if} {if $field.autofocus == TRUE} autofocus {/if}><br />
        {/if}
      </div>
    {/if}
  {/foreach}
</form>
{/foreach}