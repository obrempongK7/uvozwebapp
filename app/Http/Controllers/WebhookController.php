<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller {
    public function stripe(Request $request) {
        $payload = $request->getContent();
        $sig = $request->header('Stripe-Signature');
        try {
            $event = \Stripe\Webhook::constructEvent($payload, $sig, config('services.stripe.webhook_secret'));
            Log::info('Stripe webhook: '.$event->type, ['data'=>$event->data->object]);
            // Handle subscription events
            if ($event->type === 'customer.subscription.deleted') {
                $subscriptionId = $event->data->object->id;
                \App\Models\UserSubscription::where('gateway_subscription_id',$subscriptionId)->update(['status'=>'cancelled','cancelled_at'=>now()]);
            }
        } catch (\Exception $e) { return response()->json(['error'=>$e->getMessage()],400); }
        return response()->json(['success'=>true]);
    }
    public function paystack(Request $request) {
        Log::info('Paystack webhook', $request->all());
        return response()->json(['success'=>true]);
    }
}
