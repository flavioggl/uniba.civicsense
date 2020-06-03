<?php
use App\Model\Issue;

require_once __DIR__ . '/functions.php';

return function ($object) {
    $first = TRUE;
    /** @var $issue Issue */ ?>
    <div class="accordion" id="accordionIssues">
        <?php foreach ($object->issues as $issue) { ?>
            <div class="card bg-dark border-secondary">
                <div class="btn card-header" id="heading_<?= $issue->id ?>">
                    <div class="row">
                        <div class="col" data-toggle="collapse" data-target="#collapse_<?= $issue->id ?>">
                            <h4 class="mb-0 text-left text-light">
                                <i class="fas fa-chevron-circle-<?= $first ? 'up' : 'down' ?> mr-2"></i>
                                Creata <?= dateCarbon($issue->creation_datetime)->diffForHumans() ?>
                                <small class="text-secondary">(
                                    <?= $issue->tickets()->count() ?> tickets
                                    <?php if ($issue->closing_datetime): ?>
                                        <?php printIssueStatus($issue) ?>
                                        Chiusa <?= dateCarbon($issue->closing_datetime)->diffForHumans() ?>
                                    <?php endif ?>
                                    )
                                </small>
                            </h4>
                        </div>
                        <div class="col-auto">
                            <a href="/issue/<?= $issue->id ?>/map" class="btn btn-info">
                                <i class="fas fa-map-marked-alt"></i> Visualizza su Maps</a>
                            <?php if (\Core\Auth::isSpecialist() && ($issue->status === Issue::STATUS_PROCESSING)): ?>
                                <button type="button" class="btn btn-primary ml-2" data-toggle="modal"
                                        data-target="#modalIssueClose" data-issue="<?= $issue->id ?>">
                                    <i class="fas fa-lock"></i> Chiudi
                                </button>
                            <?php elseif (\Core\Auth::isManager() && !$issue->team_id): ?>
                                <button type="button" data-issue="<?= $issue->id ?>" class="btn btn-primary ml-2"
                                        data-toggle="modal" data-target="#modalIssueManage">
                                    <i class="fas fa-tools"></i> Gestisci
                                </button>
                            <?php endif ?>
                        </div>
                    </div>
                </div>

                <div id="collapse_<?= $issue->id ?>" class="collapse <?= $first ? 'show' : NULL ?>"
                     aria-labelledby="heading_<?= $issue->id ?>" data-parent="#accordionIssues">
                    <div class="card-body bg-light p-0">
                        <?php view('ticket/list', [
                            "tickets" => $issue->tickets(),
                            "hidden"  => ["closing_datetime", "team", "status"]
                        ]) ?>
                    </div>
                </div>
            </div>

            <?php
            $first = FALSE;
        }
        ?>
    </div>
    <?php
};