<?php

namespace App\Controller;

use App\Model\Issue;
use Core\Request;

class SpecialistController extends AbstractController {

    /**
     * @return \App\Model\Team
     */
    private static function team() {
        return self::auth()->loggedRow->team;
    }

    static function getHome() {
        $team = self::team();
        return self::view('specialist/home', [
            'title'            => 'Gestione Gruppo di Risoluzione',
            'team'             => $team,
            'count_processing' => $team->issuesProcessing()->count(),
            'count_closed'     => $team->issuesClosed()->count()
        ]);
    }

    static function getIssues($type) {
        $team = self::auth()->loggedRow->team;
        switch ($type) {
            case Issue::STATUS_PROCESSING:
                $params["title"] = "Segnalazioni in Gestione";
                $params["issues"] = $team->issuesProcessing()->orderBy("creation_datetime");
                $params["modals"][] = "issue/modal-close";
                $params["js"][] = "issue-modal";
                break;
            case "CLOSED":
                $params["title"] = "Segnalazioni Chiuse";
                $params["issues"] = $team->issuesClosed()->orderBy("closing_datetime", "desc");
                break;
            default:
                return self::errorUnauthorized();
        }
        return self::view('issue/list', $params);
    }

    static function postClose() {
        if (!$request = Request::filter(INPUT_POST, [
            "id"             => ["required", "int", "values" => self::auth()->loggedRow->team
                ->issuesProcessing()->columns(["id"])->getQuery()->toArray()],
            "status"         => ["required", "values" => [Issue::STATUS_SOLVED, Issue::STATUS_FAILED]],
            "closing_detail" => ["required", "max" => 128]
        ])) {
            return self::errorServer();
        }
        Issue::get($request["id"])->close($request["status"], $request["closing_detail"]);
        return redirect('/');
    }

}