<?php

namespace App\Notifications;

use App\Models\UserOtp;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Auth\Notifications\VerifyEmail as BaseVerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Hash;


class VerifyEmail extends BaseVerifyEmail
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    // public function via(object $notifiable): array
    // {
    //     return ['mail'];
    // }

    /**
     * Get the mail representation of the notification.
     */ public function toMail($notifiable)
    {
        $verificationUrl = $this->verificationUrl($notifiable);
        $otp = random_int(100000, 999999); // Simple OTP generation, consider a more secure approach

// Example of storing OTP
$userOtp = UserOtp::updateOrCreate(
    ['user_id' => $notifiable->id],
    [
        'otp' => Hash::make($otp), // Consider hashing the OTP
        'expires_at' => now()->addMinutes(10), // Set OTP to expire in 10 minutes
    ]
);

        return (new MailMessage)
            ->subject(Lang::get('Verify Email Address'))
            ->line(Lang::get('Please click the button below to verify your email address.'))
            ->action(Lang::get('Verify Email Address'), $verificationUrl)
            ->line(Lang::get('Alternatively, you can enter the following OTP on the verification page:'))
            ->line($otp)
            ->line(Lang::get('The OTP is valid for 10 minutes.')); // Customize as needed
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
