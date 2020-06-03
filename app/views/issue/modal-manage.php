<?php return function ($obj) {
    $team = NULL; /** @var $team \App\Model\Team */ ?>
    <div class="modal modalIssue fade" id="modalIssueManage" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="/issue/manage" method="post" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Assegna la segnalazione ad un Gruppo di Risoluzione</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <label for="team_id">Seleziona il Team</label>
                    <select name="team_id" id="team_id" class="form-control">
                        <?php foreach ($obj->teams as $team): ?>
                            <option value="<?= $team->id ?>"><?= implode(", ", $team->specialists()->toArray()) ?></option>
                        <?php endforeach ?>
                    </select>
                    <input type="text" name="id" value="" hidden>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary">Conferma</button>
                </div>
            </form>
        </div>
    </div>
    <?php
};