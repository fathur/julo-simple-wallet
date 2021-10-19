<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Transformers\WalletTransformer;

class WalletController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.api');
    }

    /**
     * Create or enable wallet.
     *
     * @return void
     */
    public function store()
    {
        $user = auth()->user();

        $wallet = $user->wallet;

        if ($wallet) {
            if ($wallet->isEnabled()) {
                throw new \Exception('Wallet already enabled');
            }

            $wallet->enabled_at = Carbon::now();
            $wallet->save();
        } else {
            $wallet = $user->wallet()->create([
                'enabled_at' => Carbon::now()
            ]);
        }

        return fractal($wallet, new WalletTransformer())->respond(201);
    }

    /**
     * Show the wallet information.
     *
     * @return void
     */
    public function show()
    {
        $user = auth()->user();

        $wallet = $user->wallet;

        if ($wallet->isDisabled()) {
            throw new \Exception('This customer does not has active wallet');
        }

        return fractal($wallet, new WalletTransformer())->respond();
    }

    /**
     * Update the wallet information.
     *
     * Make sure to send using --data-urlencode, as Laravel doesn't recognize patch in form-data
     *
     * curl --location --request PATCH 'localhost:8000/api/wallet' \
     * --header 'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3Q6ODAwMFwvYXBpXC9pbml0IiwiaWF0IjoxNjM0NjEyOTU4LCJleHAiOjE2MzQ2MTY1NTgsIm5iZiI6MTYzNDYxMjk1OCwianRpIjoiTGFhU0hOZ0xHUmViTjdjOSIsInN1YiI6ImI1OWM4ZWM0LWY5YjUtNGY1YS1iZjc3LTNkNzRkMDNiM2I5OSIsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.Y1dJTPNz8aLxCAwPa2sKWa2vXniQPmvWKrN901H2D4g' \
     * --header 'Content-Type: application/x-www-form-urlencoded' \
     * --data-urlencode 'is_disabled=true'
     *
     * @param Request $request
     * @return void
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $wallet = $user->wallet;

        if ($wallet->isDisabled()) {
            throw new \Exception('Wallet already disabled');
        }

        // Force to use as boolean
        $isDisabled = filter_var($request->get('is_disabled'), FILTER_VALIDATE_BOOLEAN);

        if ($isDisabled) {
            $wallet->disabled_at = Carbon::now();
            $wallet->save();
        }

        return fractal($wallet, new WalletTransformer())->respond();
    }
}
