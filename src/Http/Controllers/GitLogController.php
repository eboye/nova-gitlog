<?php

namespace Eboye\Gitlog\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Carbon;

class GitLogController extends Controller
{
    /**
     * @param $log
     *
     * @return array
     */
    function parseLog($log)
    {
        $history   = [];
        $dateStats = [];
        foreach ($log as $key => $line) {
            if (strpos($line, 'commit') === 0 || (!is_string($line) && $key + 1 == count($line))) {
                $commit['hash'] = substr($line, strlen('commit') + 1);
            } else if (strpos($line, 'Author') === 0) {
                $commit['author'] = substr($line, strlen('Author:') + 1);
                preg_match_all('/[._a-zA-Z0-9-]+@[._a-zA-Z0-9-]+/i', $commit['author'], $email);
                $commit['email'] = $email[0][0];
            } else if (strpos($line, 'Date') === 0) {
                $date           = substr($line, strlen('Date:') + 3);
                $commit['date'] = Carbon::parse($date)->format('d. M. Y @ H:i');
                $onlyDate       = Carbon::parse($date)->format('Y-m'); //for day values Y-m-d
                if (isset($dateStats[$onlyDate])) {
                    $dateStats[$onlyDate] += 1;
                } else {
                    $dateStats[$onlyDate] = 1;
                }
            } elseif (strpos($line, 'Merge') === 0) {
                $commit['merge'] = substr($line, strlen('Merge:') + 1);
                $commit['merge'] = explode(' ', $commit['merge']);
            } else if (!empty($line)) {
                $commit['message'] = substr($line, 4);
                array_push($history, $commit);
                unset($commit);
            }
        }
        $datesArray = [];
        foreach ($dateStats as $date => $count) {
            array_push($datesArray, [
                'date'  => $date,
                'count' => $count
            ]);
        }
        unset($dateStats);
        $sortkey = 'date';
        usort($datesArray, function ($b, $a) use ($sortkey) {
            if ($a == $b) {
                return 0;
            }

            return ($a[$sortkey] < $b[$sortkey]) ? -1 : 1;
        });

        return ['history' => $history, 'dates' => $datesArray];
    }

    /**
     * @return array
     */
    function changelog()
    {
        $dir    = base_path();
        $output = [];
        chdir($dir);
        exec('git log', $output);
        $log = $this->parseLog($output);
        return [
            'history' => $log['history'],
            'dates'   => $log['dates'],
        ];
    }
}
