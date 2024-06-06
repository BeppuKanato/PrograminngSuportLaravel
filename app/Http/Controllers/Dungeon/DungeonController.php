<?php

namespace App\Http\Controllers\Dungeon;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dungeons\Dungeon;
use App\Http\Middleware\PrepareValidateData;

class DungeonController extends Controller
{
    function __construct() 
    {
        $this->middleware(PrepareValidateData::class)->only('getDungeonData');
    }

    public function getDungeonData(Request $request) 
    {
        $dungeonId = $request->id;
        $response = [];
        // die;
        $dbData = Dungeon::
                        //ダンジョンとダンジョンが持つ問題
                        join('dungeon_questions', 'dungeons.id', '=', 'dungeon_questions.dungeon_id')
                        //ダンジョンが持つ問題と問題の中身
                        ->join('questions', 'dungeon_questions.question_id', '=', 'questions.id')
                        //問題の中身と選択問題の選択肢
                        ->leftJoin('select_branchs', 'questions.id', '=', 'select_branchs.question_id')
                        //問題の中身と正解
                        ->join('answers', 'questions.id', '=', 'answers.question_id')
                        //正解と穴埋め問題の時の番号
                        ->leftJoin('fill_correct_infos', 'answers.id', '=', 'fill_correct_infos.answer_id')
                        ->select(
                            'dungeons.id as dungeon_id',
                            'dungeons.name as dungeon_name',
                            'dungeons.description as dungeon_description',
                            'questions.id as question_id',
                            'questions.question_type_id as question_type',
                            'questions.description as question_description',
                            'questions.content as question_content',
                            'select_branchs.content as branch_content',
                            'answers.id as answers_id',
                            'answers.content as answer_content',
                            'fill_correct_infos.blank_number', 
                            )
                        ->where('dungeons.id', '=', $dungeonId)
                        ->get();

        $response = $this->formattingDbData($dbData);
        return response()->json(['root_key' => $response]);
        // return response()->json([['key_1' => 'myValue', 'key_2' => 'myValue_2'], ['secondKey' => 'tet']]);
    }

    //データを整形し、レスポンスデータを返す
    private function formattingDbData($dbData) {
        $result = [];
        //選択式問題をフィルタリング
        $filteredSelects = $this->filteringByQuestionType($dbData, '1');
        //入力式問題をフィルタリング
        $filteredInputs = $this->filteringByQuestionType($dbData, '2');

        //穴埋め問題をフィルタリング
        $filteredFills = $this->filteringByQuestionType($dbData, '3');

        //各形式の問題がある場合はデータ整形メソッドを通す
        if (count($filteredSelects) > 0) {
            $result = array_merge($result, $this->formattingSelectType($filteredSelects));
        }

        if (count($filteredInputs) > 0) {
            $result = array_merge($result, $this->formattingInputType($filteredInputs));
        }

        if (count($filteredFills) > 0) {
            $result = array_merge($result, $this->formattingFillType($filteredFills));
        }

        return $result;
    }

    //指定されたquestion_typeでフィルタリング
    private function filteringByQuestionType($dbData, $typeId) {
        return $dbData->filter(function($value) use($typeId) {
            return $value->question_type == $typeId;
        });
    }

    //select形式の問題のデータを整形する
    private function formattingSelectType($filteredSelects) {
        $result = []; 
        //question_id毎に分ける
        $group = $filteredSelects->groupBy('question_id');

        foreach($group as $items) {
            // 選択肢の中身を取得
            $contentByQuestions = $items->map(function($item) {
                return $item->branch_content;
            });

            //先頭の要素を戻り値に設定
            $result[] = $items[0];
            end($result)->branch_content = $contentByQuestions;
            end($result)->answer_content = [end($result)->answer_content];
        }

        return $result;
    }

    //input形式のデータを整形する
    //現状Input形式は整形の必要なし
    private function formattingInputType($filteredInputs) {
        $result = [];
        $group = $filteredInputs->groupBy('question_id');

        foreach($group as $items) {
            $result[] = $items[0];

            end($result)->answer_content = [end($result)->answer_content];
        }

        return $result;
    }

    //Fill形式のデータを整形する
    private function formattingFillType($filteredFills) {
        $result = [];

        $group = $filteredFills->groupBy('question_id');

        foreach($group as $items) {
            // question_idの空欄番号を全て取得
            $blankNumbers = $items->map(function($item) {
                return $item->blank_number;
            });

           //question_idの正解を全て取得
            $answerContents = $items->map(function($item) {
                return $item->answer_content;
            });

            $result[] = $items[0];
            end($result)->blank_number = $blankNumbers;
            end($result)->answer_content = $answerContents;
        }

        return $result;
    }
}
