<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Exception;

class SocialiteController extends Controller
{
    // 1. ส่ง User ไปหน้า Login ของ Google
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // 2. รับข้อมูลกลับจาก Google
    // ไฟล์ SocialiteController.php

    public function handleGoogleCallback()
    {
        try {
        // 1. บอก VS Code ว่าตัวแปร $driver คือใคร (จะช่วยให้เส้นแดงหายไป)
            /** @var \Laravel\Socialite\Two\AbstractProvider $driver */
            $driver = Socialite::driver('google');

            // 2. เรียกใช้แบบ stateless เพื่อป้องกัน Error 400
            $googleUser = $driver->stateless()->user();

            // 3. นำข้อมูลไปบันทึกหรืออัปเดตใน Database
            $user = User::updateOrCreate([
                'email' => $googleUser->getEmail(),
            ], [
                'name' => $googleUser->getName(),
                'google_id' => $googleUser->getId(),
                'password' => bcrypt(str()->random(16)),
            ]);

            Auth::login($user);

            return redirect('/dashboard');
        } catch (Exception $e) {
            return dd("เกิดข้อผิดพลาด: " . $e->getMessage());
        }
    }
}
