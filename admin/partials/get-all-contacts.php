<table class="table table-striped text-center table-for-get-contacts-dcf">
    <thead>
        <tr>
            <th>Имя</th>
            <th>Email</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ( $data['records'] as $contact) : ?>
            <tr>
                <td><?php echo $contact->name ?></td>
                <td><?php echo $contact->email ?></td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>
<div><?php echo $data['page_links'] ?></div>
