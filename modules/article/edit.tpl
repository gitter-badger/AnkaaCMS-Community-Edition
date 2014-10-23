<center><a href="{$lang}/Admin/Article" >{$back}</a></center>
{literal}
<script src="//tinymce.cachefly.net/4.1/tinymce.min.js"></script>
<script type="text/javascript">
tinymce.init({
    selector: "h2.editable",
    inline: true,
    toolbar: "undo redo",
    menubar: false
});
tinymce.init({
    selector: "h3.editable",
    inline: true,
    toolbar: "undo redo",
    menubar: false
});
tinymce.init({
    selector: "div.editable",
    inline: true,
    plugins: [
        "advlist autolink lists link image charmap print preview anchor",
        "searchreplace visualblocks code fullscreen",
        "insertdatetime media table contextmenu paste"
    ],
    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
});
</script>
{/literal}
{foreach $page as $content}
    {foreach $content.Article.edit as $Article}
    <form method="POST" action="" name="edit">
     <h2 class="editable">{$Article.title}</h2>
     <h3 class="editable">{$Article.subtitle}</h3>
     <div class="editable" style="width:100%; min-height:200px">{$Article.content}</div>
     <input type="submit" value="{$save}"/>
    </form>
    {/foreach}
{/foreach}