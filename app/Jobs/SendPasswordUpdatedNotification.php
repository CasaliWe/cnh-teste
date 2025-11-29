<?php

namespace App\Jobs;

use App\Mail\PasswordUpdatedNotification;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendPasswordUpdatedNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 3; // Total de 3 tentativas (1 inicial + 2 retry)

    /**
     * The number of seconds to wait before retrying the job.
     */
    public int $backoff = 5;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public User $user
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Mail::to($this->user->email)->send(new PasswordUpdatedNotification($this->user));
            
            Log::info('Email de notificação de senha atualizada enviado com sucesso', [
                'user_id' => $this->user->id,
                'email' => $this->user->email,
                'tentativa' => $this->attempts()
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao enviar email de notificação de senha atualizada', [
                'user_id' => $this->user->id,
                'email' => $this->user->email,
                'erro' => $e->getMessage(),
                'tentativa' => $this->attempts(),
                'max_tentativas' => $this->tries
            ]);
            
            // Re-throw the exception para que o job seja recolocado na fila se configurado
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Job de notificação de senha falhou definitivamente após todas as tentativas', [
            'user_id' => $this->user->id,
            'email' => $this->user->email,
            'erro_final' => $exception->getMessage(),
            'total_tentativas' => $this->tries
        ]);
    }
}