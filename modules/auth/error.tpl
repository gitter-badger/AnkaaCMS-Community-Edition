{foreach $page as $content}
		{foreach $content.errors as $error}
			<h3>{$error.message}</h3>
		{/foreach}
{/foreach}

<a onClick="history.go(-1)">{$back}</a>