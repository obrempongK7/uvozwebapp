<?php
namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use App\Models\KycVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KycController extends Controller {
    public function index() {
        $kyc = KycVerification::where('user_id',Auth::id())->latest()->first();
        return view('user.kyc', compact('kyc'));
    }
    public function submit(Request $request) {
        $request->validate(['document_type'=>['required','in:national_id,passport,drivers_license'],'document_front'=>['required','image','max:5120'],'selfie_image'=>['required','image','max:5120']]);
        $front = $request->file('document_front')->store('kyc','public');
        $back  = $request->hasFile('document_back') ? $request->file('document_back')->store('kyc','public') : null;
        $selfie= $request->file('selfie_image')->store('kyc','public');
        KycVerification::create(['user_id'=>Auth::id(),'document_type'=>$request->document_type,'document_front'=>$front,'document_back'=>$back,'selfie_image'=>$selfie,'document_number'=>$request->document_number,'document_expiry'=>$request->document_expiry,'status'=>'pending']);
        return redirect()->route('kyc.index')->with('success','KYC documents submitted for review.');
    }
}
