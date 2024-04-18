<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use function PHPUnit\Framework\isNull;

class PrepareValidateData
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $data = $this->decodeData(array_keys($request->all()));

        //正しいフォーマットの場合はそのまま
        if (isNull($data)) {
            return $next($request);
        }

        $formattedData = $this->dataFormat($data);

        //正しいフォーマットのデータに置き換える
        $request->replace($formattedData);

        return $next($request);
    }
    
    //jsonデータをデコード
    function decodeData(array $responceData)
    {
        $jsonData = $responceData[0];

        $result = json_decode($jsonData);

        return $result;        
    }

    //データを整形する
    function dataFormat($data) 
    {
        $result = array_map(function($value) {
            return $value;
        }, $data);

        return $result;
    }
}
