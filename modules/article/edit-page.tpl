<center><a href="{$referer}" >{$back}</a></center>
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
    <form method="POST" action="{$lang}/Admin/Article/edit/{$pages.string}" name="goedit">
     <h2>{$content.Article['edit'][{$pages.string}].title}</h2>
     <h3>{$content.Article['edit'][{$pages.string}].subtitle}</h3>
     <div style="width:100%; min-height:200px">{$content.Article['edit'][{$pages.string}].content}</div>
     <input type="submit" value="{$edit}"/>
    </form>