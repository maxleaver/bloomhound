<?php

namespace App\Http\Controllers\Auth;

use App\Account;
use App\User;
use App\Events\AccountRegistered;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Validator;

class RegisterController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    protected function register(Request $request)
    {
        $data = $this->validate(request(), [
            'name' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6'
        ]);

        // Create the account
        $account = Account::create([
            'name' => $data['company']
        ]);

        // Add new user to account
        $user = $account->users()->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        try {
            if (! $token = JWTAuth::fromUser($user)) {
                return response()->json(['user_not_found'], 404);
            }
        } catch (TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (JWTException $e) {
            return response()->json(['token_absent' => $e->getMessage()], $e->getStatusCode());
        }

        // Trigger account registered event
        event(new AccountRegistered($account));

        // Return auth token
        return json_encode(compact('token'));
    }
}
