<div id="newsletter" class="col-sm-offset-2 col-md-7">
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
    <div class="panel panel-default">
        <div class="panel-heading">Options</div>
        <div class="panel-body text-center">
            <input type="text" name="newsletter-name" id="subject" class="form-control" placeholder="Subject" />
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="newsletter-status" />
                    Send immediately
                </label>
            </div>
            
            <button class="btn btn-default btn-sm btn-block" onClick="removeall();">
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                Remove all
            </button>
            <button id="respons" class="btn btn-default btn-sm btn-block" onClick="savemail();">
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                Save Mail
            </button>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">Extra</div>
        <div class="panel-body text-center">
            <input type="email" name="newsletter-testemail" id="testmail" class="form-control" placeholder="Test E-mail" />
            <button class="btn btn-default btn-sm btn-block" onClick="sendtest();">
                <span class="glyphicon glyphicon-send" aria-hidden="true"></span>
                Send
            </button>
        </div>
    </div>
    </p>
</div>
<script>
    var num = 0;
    var editor = [];
    CKEDITOR.disableAutoInline = true;

    function removeall(){
        document.getElementById('newsletter-content').innerHTML = '';
    }

    function sendtest(){
        var mail = document.getElementById('newsletter-content');
        var l = mail.children.length;
        for(i=0;i<l;i++){
            if(mail.children[i].attributes.contenteditable){
                mail.children[i].attributes.removeNamedItem('contenteditable');
            }
            if(mail.children[i].attributes.id){
                mail.children[i].attributes.removeNamedItem('id');
            }
            if(mail.children[i].attributes.class){
                mail.children[i].attributes.removeNamedItem('class');
            }
            if(mail.children[i].attributes.spellcheck){
                mail.children[i].attributes.removeNamedItem('spellcheck');
            }
            if(mail.children[i].attributes.role){
                mail.children[i].attributes.removeNamedItem('role');
            }
            if(mail.children[i].attributes.tabindex){
                mail.children[i].attributes.removeNamedItem('tabindex');
            }
            if(mail.children[i].attributes.title){
                mail.children[i].attributes.removeNamedItem('title');
            }
        }
        var content  = mail.innerHTML;
        var subject  = document.getElementById('subject').value;
        var testmail = document.getElementById('testmail').value;
        var data = { 'content' : content, 'subject' : subject, 'testmail' : testmail, 'test' : true }
        var req = $.post('/admin/newsletter/createmail/savemail', data, function( ){
        });
    }

    function savemail(){
        var mail = document.getElementById('newsletter-content');
        var l = mail.children.length;
        for(i=0;i<l;i++){
            if(mail.children[i].attributes.contenteditable){
                mail.children[i].attributes.removeNamedItem('contenteditable');
            }
            if(mail.children[i].attributes.id){
                mail.children[i].attributes.removeNamedItem('id');
            }
            if(mail.children[i].attributes.class){
                mail.children[i].attributes.removeNamedItem('class');
            }
            if(mail.children[i].attributes.spellcheck){
                mail.children[i].attributes.removeNamedItem('spellcheck');
            }
            if(mail.children[i].attributes.role){
                mail.children[i].attributes.removeNamedItem('role');
            }
            if(mail.children[i].attributes.tabindex){
                mail.children[i].attributes.removeNamedItem('tabindex');
            }
            if(mail.children[i].attributes.title){
                mail.children[i].attributes.removeNamedItem('title');
            }
        }
        var content = mail.innerHTML;
        var data = { 'content' : content }
        var req = $.post('/admin/newsletter/createmail/savemail', data, function( ){
        document.getElementById('respons').innerHTML = '<span class="glyphicon glyphicon-ok" aria-hidden="true"></span> ' + req.statusText ;
        document.getElementById('respons').attributes.removeNamedItem('onClick');
        })
    }

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
        var newBlock = '<h3 width="600"'+
                        ' style="display: block; min-height: 25px; border: 1px solid grey;"'+
                        ' id="editor'+num+'" contenteditable="true">'+
                        'EDIT'+
                        '</h3>';
        document.getElementById('newsletter-content').innerHTML = cur + newBlock;
        CKEDITOR.inlineAll();
    }
</script>
{include './adminpanel_form.tpl'}