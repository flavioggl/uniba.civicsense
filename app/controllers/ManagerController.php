<?php

namespace App\Controller;

use App\Model\Issue;
use App\Model\Team;
use Faker\Factory;

class ManagerController extends AbstractController {

    private static $institution;

    private static function institution() {
        if (!self::$institution) {
            self::$institution = self::auth()->loggedRow->institution;
        }
        return self::$institution;
    }

    static function getHome() {
        $institution = self::institution();
        return self::view('institution/home', [
            "title"            => $institution->name,
            "institution"      => $institution,
            "count_waiting"    => $institution->issues("WAITING")->count(),
            "count_processing" => $institution->issues(Issue::STATUS_PROCESSING)->count(),
            "count_closed"     => $institution->issues("CLOSED")->count(),
        ]);
    }

    static function getIssues($type) {
        $params["issues"] = self::institution()->issues($type);
        switch ($type) {
            case "WAITING":
                $params["title"] = "Segnalazioni non Gestite";
                $params["teams"] = self::institution()->teams();
                $params["modals"][] = "issue/modal-manage";
                $params["js"][] = "issue-modal";
                break;
            case Issue::STATUS_PROCESSING:
                $params["title"] = "Segnalazioni in Gestione";
                break;
            case "CLOSED":
                $params["title"] = "Segnalazioni Chiuse";
                break;
            default:
                return self::errorPageNotFound();
        }
        return self::view('issue/list', $params);
    }

    static function getStats() {
        $faker = Factory::create();
        $labelSuccess = "Segnalazioni Risolte";
        $labelFailed = "Segnalazioni Non Risolte";
        $charts = [$labelSuccess => self::newChart(), $labelFailed => self::newChart()];
        foreach (self::institution()->teams() as $team) {
            /** @var $team Team */
            $teamString = implode(", ", $team->specialists()->toArray());
            $countSuccess = $team->issuesSolved()->count();
            $countFailed = $team->issuesFailed()->count();
            self::addDataToChart($charts[$labelSuccess], $teamString, $countSuccess, $faker->rgbCssColor);
            self::addDataToChart($charts[$labelFailed], $teamString, $countFailed, $faker->rgbCssColor);
            $charts[$teamString] = (object) [
                "labels"   => ["In Gestione", "Risolti", "Non Risolti"],
                "datasets" => [
                    (object) [
                        "data"            => [$team->issuesProcessing()->count(), $countSuccess, $countFailed],
                        "backgroundColor" => ["#ffc107", "#28a745", "#dc3545"]
                    ]
                ]
            ];
        }
        return self::view('institution/stats', [
            "title"  => "Statistiche di Periodo",
            "js"     => ["https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.min.js", "stats"],
            "charts" => $charts
        ]);
    }

    private static function newChart() {
        return (object) ["labels" => [], "datasets" => [(object) ["data" => [], "backgroundColor" => []]]];
    }

    private static function addDataToChart(\stdClass & $chart, $label, $num, $rgbColor) {
        $chart->labels[] = $label;
        $chart->datasets[0]->data[] = $num;
        $chart->datasets[0]->backgroundColor[] = $rgbColor;
    }

}