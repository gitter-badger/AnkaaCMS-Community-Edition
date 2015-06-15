<div class="col-sm-offset-2 col-md-7">
    	{include $newsletter.email_template}
</div>
<div class="side-toolbar">
    <h3>{$adminpanel.current} - {$newsletter.current_function}</h1>
    <div class="panel panel-default">
	  <div class="panel-heading">Content Blocks</div>
	  	<div class="panel-body text-center">
    		<button class="btn btn-default btn-sm btn-block" onClick="addTextBlock();">
    			<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
    			Add Textblock
    		</button>
    		<button class="btn btn-default btn-sm btn-block" onClick="addTitleBlock();">
    			<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
    			Add Title
    		</button>
    	</div>
    </div>
    </p>
</div>
<script>
    var num = 0;
    var editor = [];
    CKEDITOR.disableAutoInline = true;

    function addTextBlock(){
        var cur = document.getElementById('newsletter-content').innerHTML;
        num = num + 1;
        var newBlock = '<p width="600"'+
                        ' style="display: block; min-height: 25px; border: 1px solid grey;"'+
                        ' id="editor'+num+'" contenteditable="true">'+
                        'EDIT'+
                        '</p>';
        document.getElementById('newsletter-content').innerHTML = cur + newBlock;
        CKEDITOR.inlineAll();
    }

    function addTitleBlock(){
        var cur = document.getElementById('newsletter-content').innerHTML;
        num = num + 1;
        var newBlock = '<h1 width="600"'+
                        ' style="display: block; min-height: 25px; border: 1px solid grey;"'+
                        ' id="editor'+num+'" contenteditable="true">'+
                        'EDIT'+
                        '</h1>';
        document.getElementById('newsletter-content').innerHTML = cur + newBlock;
        CKEDITOR.inlineAll();
    }
</script>
{include './adminpanel_form.tpl'}