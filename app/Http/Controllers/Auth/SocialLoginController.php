<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialLoginController extends Controller
{

    public function __construct()
    {
        $this->middleware(['social', 'guest']);
    }

    /**
     *  Redirect the user to the social service authentication page.
     * @param $service
     * @param Request $request
     */
    public function redirect($service, Request $request)
    {
        return Socialite::driver($service)->redirect();
    }

    /**
     * Obtain the user information from the social service.
     * @param $service
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function callback($service, Request $request)
    {

        $socialUser = Socialite::driver($service)->user();

        $user = $this->getExistingUser($socialUser, $service);

       if(!$user){
           $serData= [
               'name'=>$socialUser->getName(),
               'email'=>$socialUser->getEmail(),
           ];
           $user = User::create($serData);
       }

       if(!$user->hasSocialServiceLinked($service)){
            $user->userSocialServices()->create([
                'service'=>$service,
                'service_id'=>$socialUser->getId()
            ]);
       }

       Auth::login($user);

       return redirect()->intended();
    }

    /**
     * Check if the social user is already registred
     * @param $socialUser
     * @param $service
     * @return \App\User|null
     */
    protected function getExistingUser($socialUser, $service)
    {
        return User::where('email', $socialUser->getEmail())
            ->orWhereHas('userSocialServices', function($query) use($socialUser, $service){
                $query->where('service', $service)
                ->where('service_id', $socialUser->getId());
            })->first();
    }


}
