<?php

namespace App\Http\Controllers;


use App\Models\User;
use App\Models\UserOtp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            Log::info('Registration attempt', ['email' => $request->email]);
            // Validate the incoming request data
            $validatedData = $request->validate([
                'schoolID' => 'required|string|max:255',
                'fname' => 'required|string|max:255',
                'lname' => 'required|string|max:255',
                'email' => 'required|string|email|max:255',
                'password' => 'required|string|min:8',
                'birthdate' => 'required|date',
                'sex' => 'required|string|max:50',
                'contact_number' => 'nullable|string|max:255',
            ]);
            
            
            // Check if user exists and email is not verified
            $existingUser = User::where('email', $validatedData['email'])->where('school_id', $validatedData['schoolID'])->first();
            if ($existingUser && !$existingUser->hasVerifiedEmail()) {
                // Resend the verification email
                $existingUser->sendEmailVerificationNotification();
                // Generate a JWT token with custom claims for the existing user
                $customClaims = ['email' => $existingUser->email, 'exp' => Carbon::now()->addMinutes(10)->timestamp];
                $token = JWTAuth::claims($customClaims)->fromUser($existingUser);
                // Inform the frontend about the existing but unverified user
                return response()->json([
                    'message' => 'Account already exists but is not verified. Verification email resent.',
                    'token' => $token,
                    'needsVerification' => true
                ], 202); // 202 Accepted might be a suitable status code
            }
            
            
            // Proceed with creating a new user if they don't exist or their email is already verified
            $user = User::create([
                'school_id' => $validatedData['schoolID'],
                'fname' => $validatedData['fname'],
                'lname' => $validatedData['lname'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'birthdate' => $validatedData['birthdate'],
                'sex' => $validatedData['sex'],
                'contact_number' => $validatedData['contact_number'],
            ]);
            
            // Trigger email verification
            $user->sendEmailVerificationNotification();
            
            // Generate a JWT token for the user with custom claims
            $customClaims = ['email' => $user->email, 'exp' => Carbon::now()->addMinutes(10)->timestamp];
            $token = JWTAuth::claims($customClaims)->fromUser($user);
            
            if ($user->wasRecentlyCreated) {
                Log::info('Registration successful', ['email' => $user->email]);
            } else {
                Log::info('Registration attempt for existing user', ['email' => $user->email]);
            }

            return response()->json(['token' => $token], 201);
        } catch (\Exception $e) {
            Log::error('Registration failed', ['email' => $request->email, 'error' => $e->getMessage()]);
            return response()->json(['error' => 'Registration failed.', 'details' => $e->getMessage()], 500);
        }
    }
    
    public function resendOtp(Request $request)
{
    try {
    $request->validate(['email' => 'required|email|exists:users,email']);

    $user = User::where('email', $request->email)->first();

    if (!$user) {
        return response()->json(['message' => 'User not found.'], 404);
    }

    // Generate new OTP
    $otp = random_int(100000, 999999);

    // Optionally, remove or invalidate the old OTP
    $user->otps()->delete();

    // Store the new OTP
    $userOtp = UserOtp::create([
        'user_id' => $user->id,
        'otp' => Hash::make($otp), // Consider hashing the OTP
        'expires_at' => now()->addMinutes(10), // Set OTP to expire in 10 minutes
    ]);

    // Resend the email verification with the new OTP
    $user->sendEmailVerificationNotification();
    Log::info('OTP resend requested', ['email' => $request->email]); 
       return response()->json(['message' => 'OTP resent successfully.']);
    } catch (\Exception $e) {
        Log::error('OTP resend failed', ['email' => $request->email, 'error' => $e->getMessage()]);
        // Error response...
    }
}

    public function verify(Request $request)
    {
        $frontendUrl = config('app.redirect_url');

        $user = User::find($request->route('id'));
    
        if (!$user || !$request->hasValidSignature()) {
            // Redirect with an error message if the link is invalid or expired
            return redirect()->to($frontendUrl . 'login?message=Invalid or expired link&status=error');
        }
    
        if ($user->hasVerifiedEmail()) {
            // Redirect with a message if the email is already verified
            return redirect()->to($frontendUrl . 'login?message=Email already verified.&status=success');
        }
    
        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
            // Redirect with a success message
            return redirect()->to($frontendUrl . 'login?message=Email verified successfully.&status=success');
        }
    
        // Fallback redirect in case of unexpected behavior
        return redirect()->to($frontendUrl . 'login?message=Verification failed. Please try again.&status=error');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|numeric',
        ]);
    
        $user = User::where('email', $request->email)->first();
    
        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }
    
        // Check if the email is already verified
        if ($user->hasVerifiedEmail()) {
            Log::info('Email already verified', ['user_id' => $user->id]);
            return response()->json(['message' => 'Email already verified. Redirecting to login.', 'alreadyVerified' => true], 200);
        }
    
        $userOtp = $user->otps()->where('expires_at', '>', now())->latest()->first();
    
        if (!$userOtp || !Hash::check($request->otp, $userOtp->otp)) {
            return response()->json(['message' => 'Invalid or expired OTP.'], 401);
        }
    
        $user->markEmailAsVerified();
        event(new Verified($user));
    
        // Invalidate the OTP after successful verification
        $userOtp->delete();
        Log::info('OTP verified successfully', ['user_id' => $user->id]);
    
        return response()->json(['message' => 'OTP verified successfully.'], 200);
    }
    

    


    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
        $user = User::where('email', $request->email)->first();
    
        $rateLimitKey = 'login:' . $request->ip() . ':' . $request->email;
        $maxAttempts = 11;
        $lockoutTime = 15; // in minutes
    
        if (RateLimiter::tooManyAttempts($rateLimitKey, $maxAttempts)) {
            return response()->json(['error' => 'Account is temporarily locked due to too many failed login attempts. Please try again later.'], 429);
        }
    
        if (!$user) {
            return response()->json(['error' => 'Invalid credentials.'], 401);
        }
    
        // Ensure the user's email is verified
        if (!$user->hasVerifiedEmail()) {
            return response()->json(['error' => 'You need to verify your email before logging in.'], 403);
        }
    
        if (Hash::check($request->password, $user->password)) {
            RateLimiter::clear($rateLimitKey);
    
            $customClaims = [
                'uid' => $user->id, // User ID
                'role' => $user->role, // User role
                'school_id' => $user->school_id, // School ID
                // Additional claims if necessary
            ];
    
            $token = JWTAuth::claims($customClaims)->fromUser($user);
    
            return response()->json(['token' => $token], 200);
        } else {
            RateLimiter::hit($rateLimitKey, $lockoutTime);
            return response()->json(['error' => 'Invalid credentials.'], 401);
        }
    }
    public function fetchUserDetails(Request $request)
    {
        // The `auth()->user()` method returns the authenticated user based on the provided token
        $user = auth()->user();
    
        // Dynamically hide attributes for this specific response
        $user->makeHidden([
            'password', // Already hidden by default, but reiterated here for clarity
            'remember_token',
            "id", // Another example of a field that might be sensitive
            'school_id',
            'email',
            'role',
            'fcm_token',
            'email_verified_at'
        ]);
    
        // Return the user details
        return response()->json($user);
    }
    

}
