<?php

namespace App\Http\Controllers\Dungeon;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Questions\Answer;
use App\Models\Questions\FillCorrectInfo;
use App\Models\Questions\Question;
use App\Models\Questions\SelectBranch;
use App\Models\Dungeons\Dungeon_Question;
use App\Models\Dungeons\Dungeon;

class DungeonController extends Controller
{
    public function getDungeonData(Request $request) 
    {
        // die;
        $result = Dungeon::first();

        var_dump('dungeon_result = ');
        var_dump($request);
        die;
    }
}
