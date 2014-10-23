<h2>{$login}</h2>
<form id="login" name="login" method="post" action="{$lang}/Admin/dashboard" >
	<table>
		<tr>
			<td>
				<label id="username">{$username}</label>
			</td>
		</tr>
		<tr>
			<td>
				<input id="username" name="AuthUsername" type="text" />
			</td>
		</tr>
		<tr>
			<td>
				<label id="password">{$password}</label>
			</td>
		</tr>
		<tr>
			<td>
				<input id="password" name="AuthPassword" type="password" />
			</td>
		</tr>
		<tr>
			<td>
				<input name="AuthSubmit" type="submit" value="{$login}"/>
			</td>
		</tr>
		<tr>
			<td>
				<a href="">{$forgotpassword}</a>
			</td>
		</tr>
	</table>
</form>