<?php

require_once dirname(__DIR__) . '/ticket/functions.php';

return function ($obj) {
    $status = NULL; ?>
    <div class="modal modalIssue fade" id="modalIssueClose" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="/issue/close" method="post" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Chiudi Segnalazione</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <div class="form-group row">
                        <?php foreach ([\App\Model\Issue::STATUS_SOLVED, \App\Model\Issue::STATUS_FAILED] as $status): ?>
                            <div class="col">
                                <div class="custom-control custom-control-vertical custom-radio">
                                    <input type="radio" name="status" id="status_<?= strtolower($status) ?>"
                                           value="<?= $status ?>" class="custom-control-input" required>
                                    <label class="custom-control-label" for="status_<?= strtolower($status) ?>">
                                        <?php printTicketStatus((object) ["status" => $status]) ?>
                                    </label>
                                </div>
                            </div>
                        <?php endforeach ?>
                    </div>
                    <div class="form-group">
                        <textarea class="form-control" id="closing_detail" name="closing_detail" maxlength="128"
                                  placeholder="Dettagli aggiuntivi sulla risoluzione della segnalazione"
                                  required></textarea>
                    </div>
                    <input type="text" name="id" value="" hidden>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary"><i class="fa fa-lock"></i> Chiudi Ticket</button>
                </div>
            </form>
        </div>
    </div>
    <?php
};