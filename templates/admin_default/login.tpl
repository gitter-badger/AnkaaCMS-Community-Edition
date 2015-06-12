
<div class="container">
  <div class="page">
    {foreach $messages as $message}
      {if isset($message.text)}
        {if $message.type == 'error'}
          <div class="alert alert-danger fade in">
            <a href="#" class="close" data-dismiss="alert">&times;</a>
            <strong>Error!</strong>
          {elseif $message.type == 'warning'}
          <div class="alert alert-warning fade in">
            <a href="#" class="close" data-dismiss="alert">&times;</a>
            <strong>Warning!</strong>
          {elseif $message.type == 'success'}
          <div class="alert alert-success fade in">
            <a href="#" class="close" data-dismiss="alert">&times;</a>
            <strong>Success!</strong>
          {elseif $message.type == 'note'}
          <div class="alert alert-note fade in">
            <a href="#" class="close" data-dismiss="alert">&times;</a>
            <strong>Note!</strong>
        {/if}
            {$message.text}
          </div>
      {/if}
    {/foreach}
    {include './adminpanel_form.tpl'}
  </div>
</div>


