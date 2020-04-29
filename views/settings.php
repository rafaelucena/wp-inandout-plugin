<div class="wrap">
    <h2>In/Out Settings</h2>
    <?php if (empty($message) === false): ?><div class="updated"><p><?php echo $message; ?></p></div><?php endif; ?>
    <?php if (empty($error) === false): ?><div class="error"><p><?php echo $error; ?></p></div><?php endif; ?>
    <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
    <table class="form-table" role="presentation">
        <tr>
            <th scope="row"><label for="path">Log path</label></th>
            <td><input type="text" name="path" value="<?php echo $config->path; ?>"/></td>
        </tr>
        <tr>
            <th scope="row"><label for="filename">Filename</label></th>
            <td><input type="text" name="filename" value="<?php echo $config->filename; ?>"/></td>
        </tr>
    </table>
    <input type='submit' name="insert" value='Save' class='button'>
    </form>
</div>