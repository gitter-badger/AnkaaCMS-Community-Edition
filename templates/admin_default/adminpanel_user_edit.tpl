<div class="col-sm-3">
	<select id="edituser" name="user" class="form-control">
		{foreach $user.edit.list as $users}
			<option value="{$users.id}">{$users.username}</option>
		{/foreach}
	</select>
	<input class="form-control" type="submit" value="OK" name="edituser"/>
</div>