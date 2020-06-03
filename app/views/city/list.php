<?php return function ($object) {
    $city = NULL; /** @var $city \App\Model\City */ ?>
    <div class="card">
        <div class="card-header">
            <a class="btn btn-info float-right" href="/city/create">
                Nuovo
                <i class="fas fa-plus"></i>
            </a>
        </div>
        <table class="card-body table table-hover">
            <thead>
            <tr>
                <th>CAP</th>
                <th>Comune</th>
                <th>eMail</th>
                <th>Titolare Account</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($object->cities as $city): ?>
                <tr>
                    <td><?= $city->cap ?></td>
                    <td><?= $city->city_name ?></td>
                    <td><?= $city->email ?></td>
                    <td><?= $city->surname, ' ', $city->name ?></td>
                    <td>
                        <div class="btn-group">
                            <i class="fas fa-ellipsis-v px-2" data-toggle="dropdown" aria-haspopup="true"
                               aria-expanded="false"></i>
                            <div class="dropdown-menu dropdown-menu-right text-right">
                                <a href="/city/<?= $city->id ?>/update" class="dropdown-item">
                                    Modifica
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="/city/<?= $city->id ?>/delete" class="dropdown-item">
                                    Elimina
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php endforeach ?>
            </tbody>
        </table>
    </div>
    <?php
};