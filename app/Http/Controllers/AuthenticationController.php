<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Repository\AuthenticationRepositoryInterface;
use Exception;

class AuthenticationController extends Controller
{
    private AuthenticationRepositoryInterface $authenticationRepositoryInterface;

    public function __construct(AuthenticationRepositoryInterface $authenticationRepositoryInterface)
    {
        $this->authenticationRepositoryInterface = $authenticationRepositoryInterface;
    }

    public function index()
    {
        try {
            if (session('signedIn', false)) {
                return redirect()->route('dashboard.index');
            } else {
                $type = session('type', null);
                $msg = session('message', null);

                return Inertia::render('Authentication/Index', [
                    'type' => $type,
                    'message' => $msg
                ]);
            }
        } catch (Exception $e) {
            return redirect()->back()->with(['type' => 'error', 'message' => $e]);
        }
    }
}
