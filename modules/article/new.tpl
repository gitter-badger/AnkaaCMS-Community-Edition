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
function checkedUsePage(){
    if(document.getElementById('usePage').checked == true){
        document.getElementById('useSelPage').style.display = "inline";
    } else {
        document.getElementById('useSelPage').style.display = "none";
    }
}
</script>
{/literal}
{foreach $page as $content}
    {foreach $content.Article.new as $Article}
        <form method="POST" action="" name="edit">
         <h2 class="editable">{$Article.title}</h2>
         <h3 class="editable">{$Article.subtitle}</h3>
         <div class="editable" style="width:100%; min-height:200px">{$Article.content}</div>
         <input type="checkbox" value="1" id="makePage" name="makePage" /><label for="makePage">{$Article.makePage}</label><br />
         <input type="checkbox" onClick="checkedUsePage()" value="1" name="usePage" id="usePage" /><label for="makePage">{$Article.usePage}</label>
         <select style="display: none;" id="useSelPage" name="useSelPage">
            {foreach $content.Article.pages as $page}
                <option value="NOT">&nbsp;</option>
                <option value="{$page.id}">{$page.slug}</option>
            {/foreach}
         </select><br /><br />     
         <input type="submit" value="{$save}"/>
        </form>
    {/foreach}
{/foreach}