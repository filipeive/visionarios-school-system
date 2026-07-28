<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProcessWebhookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->validateWebhook();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $provider = $this->route('provider');

        return match ($provider) {
            'mpesa' => $this->mpesaRules(),
            'emola' => $this->emolaRules(),
            'multicaixa' => $this->multicaixaRules(),
            default => [],
        };
    }

    /**
     * Get M-Pesa webhook validation rules.
     */
    private function mpesaRules(): array
    {
        return [
            'output_ThirdPartyReference' => ['required', 'string'],
            'output_TransactionID' => ['required', 'string'],
            'output_ResponseCode' => ['required', 'string'],
            'output_ResponseDesc' => ['nullable', 'string'],
        ];
    }

    /**
     * Get eMola webhook validation rules.
     */
    private function emolaRules(): array
    {
        return [
            'reference' => ['required', 'string'],
            'txn_id' => ['required', 'string'],
            'status' => ['required', 'string'],
            'amount' => ['nullable', 'numeric'],
        ];
    }

    /**
     * Get Multicaixa webhook validation rules.
     */
    private function multicaixaRules(): array
    {
        return [
            'reference' => ['required', 'string'],
            'transaction_id' => ['required', 'string'],
            'amount' => ['nullable', 'numeric'],
        ];
    }

    /**
     * Validate webhook signature/timestamp.
     */
    private function validateWebhook(): bool
    {
        $provider = $this->route('provider');
        $signature = $this->header('X-Webhook-Signature');
        $timestamp = $this->header('X-Webhook-Timestamp');

        // Validate timestamp is recent (within 5 minutes)
        if ($timestamp) {
            $webhookTime = now()->createFromFormat('U', $timestamp);
            if (! $webhookTime || $webhookTime->diffInMinutes(now()) > 5) {
                return false;
            }
        }

        // If signature header is present, validate it
        if ($signature) {
            $expectedSignature = $this->generateSignature($provider, $timestamp);

            return hash_equals($expectedSignature, $signature);
        }

        // If no signature, check IP whitelist if configured
        $whitelist = config('payment.webhook.whitelist_'.$provider);
        if ($whitelist) {
            $clientIp = $this->ip();

            return in_array($clientIp, $whitelist);
        }

        // Allow if no security configured (for development)
        return app()->isLocal();
    }

    /**
     * Generate webhook signature.
     */
    private function generateSignature(string $provider, ?string $timestamp): string
    {
        $secret = config('payment.webhook.secret_'.$provider, config('payment.webhook.default_secret'));
        $payload = $this->all();
        ksort($payload);

        return hash_hmac('sha256', json_encode($payload).$timestamp, $secret);
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'output_ThirdPartyReference.required' => 'Missing M-Pesa transaction reference.',
            'output_TransactionID.required' => 'Missing M-Pesa transaction ID.',
            'output_ResponseCode.required' => 'Missing M-Pesa response code.',
        ];
    }
}
