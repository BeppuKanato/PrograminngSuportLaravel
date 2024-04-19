<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

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
        $decodeData = $this->decodeData($request->getContent());

        //正しいフォーマットのデータに置き換える
        $request->replace($decodeData);

        return $next($request);
    }
    
    //jsonデータをデコード
    function decodeData(string $jsonString)
    {
        $result = json_decode($jsonString, true, 512, JSON_THROW_ON_ERROR);

        return $result;        
    }
}
