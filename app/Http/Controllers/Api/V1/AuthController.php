<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\LoginRequest;
use App\Http\Requests\Api\V1\RegisterRequest;
use App\Models\User;
use App\Support\FrontendUrlResolver;
use App\Services\EmailTemplateService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Two\GoogleProvider;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $email = $request->filled('email') ? Str::lower(trim((string) $request->input('email'))) : null;
        $phone = $request->filled('phone') ? $this->normalizePhone((string) $request->input('phone')) : null;

        if (! $email && $phone) {
            $email = $this->phoneOnlyEmail($phone);
        }

        $user = User::create([
            'name' => $request->string('name'),
            'email' => $email,
            'phone' => $phone,
            'password' => $request->string('password'),
            'role' => 'user',
            'is_active' => true,
            'auth_provider' => 'password',
        ]);

        $user->assignRole('user');
        app(EmailTemplateService::class)->sendWelcome($user);

        return response()->json([
            'data' => [
                'user' => $this->userPayload($user),
                'token' => $user->createToken($request->input('device_name', 'next-web'))->plainTextToken,
            ],
        ], 201);
    }

    public function login(LoginRequest $request)
    {
        $login = trim((string) ($request->input('login') ?: $request->input('email') ?: $request->input('phone')));
        $normalizedPhone = $this->normalizePhone($login);

        $user = User::query()
            ->whereRaw('LOWER(email) = ?', [Str::lower($login)])
            ->orWhere('phone', $normalizedPhone)
            ->orWhere('phone', $login)
            ->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'login' => ['The provided credentials are incorrect.'],
            ]);
        }

        if (! $user->is_active) {
            throw ValidationException::withMessages([
                'email' => ['This account is inactive. Please contact support.'],
            ]);
        }

        $user->forceFill(['last_login_at' => now()])->save();

        return response()->json([
            'data' => [
                'user' => $this->userPayload($user),
                'token' => $user->createToken($request->input('device_name', 'next-web'))->plainTextToken,
            ],
        ]);
    }

    public function me(Request $request)
    {
        return response()->json(['data' => $this->userPayload($request->user())]);
    }

    public function logout(Request $request)
    {
        $request->user()?->currentAccessToken()?->delete();

        return response()->json(['data' => ['message' => 'Logged out']]);
    }

    public function googleRedirect(Request $request)
    {
        $frontend = FrontendUrlResolver::resolveFromRequest($request);
        $state = $this->encodeState([
            'next' => $this->safeNextPath((string) $request->query('next', '/dashboard')),
            'frontend' => $frontend,
        ]);

        $url = $this->googleDriver()
            ->stateless()
            ->with(['state' => $state, 'prompt' => 'select_account'])
            ->redirect()
            ->getTargetUrl();

        return response()->json(['data' => ['url' => $url]]);
    }

    public function googleCallback(Request $request): RedirectResponse
    {
        $state = $this->decodeState((string) $request->query('state'));
        $frontend = FrontendUrlResolver::isAllowed((string) data_get($state, 'frontend'))
            ? (string) data_get($state, 'frontend')
            : FrontendUrlResolver::fallbackFrontend();
        $next = $this->safeNextPath((string) data_get($state, 'next', '/dashboard'));

        try {
            $googleUser = $this->googleDriver()->stateless()->user();
        } catch (Throwable) {
            return redirect()->away($frontend . '/auth/callback#error=google_signin_failed');
        }

        $email = $googleUser->getEmail();

        if (! $email) {
            return redirect()->away($frontend . '/auth/callback#error=google_email_missing');
        }

        $user = User::where('google_id', $googleUser->getId())
            ->orWhereRaw('LOWER(email) = ?', [Str::lower($email)])
            ->first();

        if ($user) {
            $user->forceFill([
                'google_id' => $user->google_id ?: $googleUser->getId(),
                'auth_provider' => $user->auth_provider ?: 'google',
                'avatar' => $user->avatar ?: $googleUser->getAvatar(),
                'email_verified_at' => $user->email_verified_at ?: now(),
                'is_active' => true,
                'last_login_at' => now(),
            ])->save();
        } else {
            $user = User::create([
                'name' => $googleUser->getName() ?: Str::before($email, '@'),
                'email' => Str::lower($email),
                'google_id' => $googleUser->getId(),
                'auth_provider' => 'google',
                'avatar' => $googleUser->getAvatar(),
                'email_verified_at' => now(),
                'password' => Str::password(40),
                'role' => 'user',
                'is_active' => true,
                'last_login_at' => now(),
            ]);

            $user->assignRole('user');
            app(EmailTemplateService::class)->sendWelcome($user);
        }

        $token = $user->createToken('next-web-google')->plainTextToken;
        $payload = http_build_query([
            'token' => $token,
            'next' => $next,
        ]);

        return redirect()->away($frontend . '/auth/callback#' . $payload);
    }

    private function userPayload(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'role' => $user->role,
            'roles' => $user->getRoleNames(),
            'can_access_admin' => $user->canAccessFilament(),
            'avatar_url' => $user->avatar_url,
        ];
    }

    private function normalizePhone(string $value): ?string
    {
        $digits = preg_replace('/\D+/', '', $value);

        if (! $digits) {
            return null;
        }

        if (str_starts_with($digits, '0') && strlen($digits) === 10) {
            return '256' . substr($digits, 1);
        }

        if (str_starts_with($digits, '256')) {
            return $digits;
        }

        return $digits;
    }

    private function phoneOnlyEmail(string $phone): string
    {
        $base = 'phone_' . preg_replace('/\D+/', '', $phone) . '@narapromotionz.local';
        $email = $base;
        $counter = 1;

        while (User::where('email', $email)->exists()) {
            $email = str_replace('@', '_' . $counter . '@', $base);
            $counter++;
        }

        return $email;
    }

    private function encodeState(array $payload): string
    {
        $json = json_encode($payload, JSON_THROW_ON_ERROR);
        $encoded = rtrim(strtr(base64_encode($json), '+/', '-_'), '=');
        $signature = hash_hmac('sha256', $encoded, config('app.key'));

        return $encoded . '.' . $signature;
    }

    private function decodeState(string $state): array
    {
        [$encoded, $signature] = array_pad(explode('.', $state, 2), 2, null);

        if (! $encoded || ! $signature || ! hash_equals(hash_hmac('sha256', $encoded, config('app.key')), $signature)) {
            return [];
        }

        $json = base64_decode(strtr($encoded, '-_', '+/'), true);

        return $json ? (json_decode($json, true) ?: []) : [];
    }

    private function safeNextPath(string $path): string
    {
        if (! str_starts_with($path, '/') || str_starts_with($path, '//')) {
            return '/dashboard';
        }

        return $path;
    }

    private function googleDriver(): GoogleProvider
    {
        /** @var GoogleProvider $driver */
        $driver = Socialite::driver('google');

        return $driver;
    }
}
