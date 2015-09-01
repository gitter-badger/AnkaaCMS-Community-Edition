 <div class="col-sm-9 col-sm-offset-2 col-md-10 col-md-offset-2 main">
    <h1>{$adminpanel.current} - {$newsletter.current_function}</h1>
    <p class="lead">
      <table class="table table-striped">
        <tbody>
          <tr>
            {foreach $newsletter.list.columns as $column}
            <th>{$column}</th>
            {/foreach}
          </tr>
          {foreach $newsletter.list.rows as $row}
          <tr>
            {foreach $row as $key=>$field}
                <td>{$field}</td>
            {/foreach}
          </tr>
          {/foreach}
        </tbody>
      </table>
    </p>
</div>