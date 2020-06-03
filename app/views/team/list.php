<?php return function ($object) {
    /** @var $team \App\Model\Team */
    $team = NULL; ?>
    <table class="table table-hover">
        <thead class="thead-dark">
        <tr>
            <th>Componenti del gruppo</th>
            <th>IN CORSO</th>
            <th>RISOLTI</th>
            <th>NON RISOLTI</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($object->list as $team): ?>
            <tr>
                <td><?= implode(", ", $team->specialists()->toArray()) ?></td>
                <td><?= $team->issuesProcessing()->count() ?></td>
                <td><?= $team->issuesSolved()->count() ?></td>
                <td><?= $team->issuesFailed()->count() ?></td>
                <td>
                    <a class="btn btn-info mr-3" href="/team/<?= $team->id ?>/update">Modifica</a>
                    <a class="btn btn-danger" href="/team/<?= $team->id ?>/delete">Elimina</a>
                </td>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>
    <?php
};