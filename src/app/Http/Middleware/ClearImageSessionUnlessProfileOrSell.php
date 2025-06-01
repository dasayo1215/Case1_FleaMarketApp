<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ClearImageSessionUnlessProfileOrSell
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // 残しておきたいルート（URIベースでOK）
        $keepRoutesForSell = [
            'sell',
            'sell/image',
        ];

        $keepRoutesForProfile = [
            'mypage/profile',
            'mypage/profile/image',
        ];

        $path = $request->path();

        // sell 関係のルートでなければ sell のセッションをクリア
        if (!in_array($path, $keepRoutesForSell)) {
            session()->forget('sell_uploaded_image_path');
        }

        // profile 関係のルートでなければ profile のセッションをクリア
        if (!in_array($path, $keepRoutesForProfile)) {
            session()->forget('profile_uploaded_image_path');
        }

        return $next($request);
    }
}
