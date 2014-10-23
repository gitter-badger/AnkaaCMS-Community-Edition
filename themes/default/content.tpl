    {foreach $mods as $mod}
			<div class="post" id="post-1">
				<h2><a href="http://themes.sarkis-design.com/archives/1" title="Permanent Link to Hello world!">{$mod.$title}</a></h2>
				<small>{$mod.$subtitle}</small>
				<div class="entry">
					{$mod.$content}
				</div>
				<p class="postmetadata">Posted in <a href="#" title="View all posts in Uncategorized">Uncategorized</a> |   <a href="#" title="Comment on Hello world!">1 Comment &#187;</a></p>
			</div>
            {/foreach}
		