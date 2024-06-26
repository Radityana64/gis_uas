<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use GuzzleHttp\Client; 

class AuthController extends Controller
{
    public function register()
    {
        return view('auth.register');
    }

    public function registerSave(Request $request)
    {
        
        $data = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required'
        ])->validate();
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'level' => 'Admin'
        ]);
        try {
            $response = Http::post('https://gisapis.manpits.xyz/api/register', $data);
            if ($response->successful()) {
                $responseData = $response->json();
                if ($responseData['meta']['code'] == 200) {

                    return redirect()->route('login')->with('success', $responseData['meta']['message']);
                } else {
                    return back()->withErrors(['message' => 'Registration failed. Please try again.']);
                }
            } else {
                return back()->withErrors(['message' => 'Registration failed. Please try again.']);
            }
        } catch (\Exception $e) {
            return back()->withErrors(['message' => 'An error occurred: ' . $e->getMessage()]);
        }

        return redirect()->route('login');
    }

    public function login()
    {
        return view('auth.login');
    }

    public function loginAction(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8'
        ]);

        try {
            $response = Http::post('https://gisapis.manpits.xyz/api/login',[
                'email' => $request->email,
                'password' => $request->password,
            ]);

            $responseData = $response->json();

            if ($response->successful() && isset($responseData['meta']['token'])) {
                session(['token' => $responseData['meta']['token']]);
                return redirect()->route('dashboard')->with('status', 'Login successful! Welcome.');
            } else {
                throw new \Exception('Invalid credentials or failed to get token from API response.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['login' => 'Login failed: ' . $e->getMessage()]);
        }
    }


    public function logout(Request $request)
    {
        {
            $token = session('token');
            $client = new Client();
            $response = $client->request('POST', 'https://gisapis.manpits.xyz/api/logout', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Accept' => 'application/json',
                ],
            ]);
    
            if ($response->getStatusCode() == 200) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();    
                
                return redirect()->route('login.action')->with('success', 'anda telah keluar');
            } else {
                return redirect()->back()->with('error', 'keluar');
            }
        }
    }
}


