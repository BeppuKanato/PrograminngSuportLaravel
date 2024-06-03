<?php

namespace App\Http\Controllers\Question;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use App\Models\Questions\;
// use App\Models\Questions\;
// use App\Models\Questions\;

class QuestionController extends Controller
{
    public function qustionController(Request $request) {
        $result = FillQuestionCorrect::select('correct')
                                        ->get(); 

        $array_1 = json_decode($result);

        $index_1 = $array_1[1];

        $index_1_json = $index_1->correct;

        $index_1_array = json_decode($index_1_json);

        var_dump($index_1_array->{2});
    }
}
