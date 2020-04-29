<div class="wrap">
    <h2>In/Out List</h2>
    <table class='wp-list-table widefat fixed striped posts'>
        <tr>
            <th class="manage-column ss-list-width">Action</th>
            <th class="manage-column ss-list-width">Login</th>
            <th class="manage-column ss-list-width">Roles</th>
            <th class="manage-column ss-list-width">IP</th>
            <th class="manage-column ss-list-width">Date</th>
            <th class="manage-column ss-list-width">Time</th>
        </tr>
        <?php foreach ($events as $event): ?>
            <tr>
            <?php foreach ($event as $value): ?>
                <td class="manage-column ss-list-width"><?php echo $value; ?></td>
            <?php endforeach ?>
            </tr>
        <?php endforeach ?>
    </table>
</div>