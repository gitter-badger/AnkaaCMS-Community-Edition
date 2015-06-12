<table class="table table-striped">
	<tbody>
		<tr>
			{foreach $user.admin.userlist.columns as $column}
			<th>{$column}</th>
			{/foreach}
		</tr>
		{foreach $user.admin.userlist.rows as $row}
		<tr>
			{foreach $row as $field}
			<td>{$field}</td>
			{/foreach}
		</tr>
		{/foreach}
	</tbody>
</table>