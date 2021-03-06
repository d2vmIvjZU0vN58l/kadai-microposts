<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];
    
    public function microposts()
    {
        return $this->hasMany(Micropost::class);
    }
    
    public function followings()
    {
        //得られるModelクラス, table名, 自分, 関係先
        return $this->belongsToMany(User::class, 'user_follow', 'user_id', 'follow_id')->withTimestamps();
    }
    
    public function followers()
    {
        return $this->belongsToMany(User::class, 'user_follow', 'follow_id', 'user_id')->withTimestamps();
    }
    
    //すでにフォローしているかの確認
    public function follow($userId) {
        // すでにフォローしているかの確認
        $exist = $this->is_following($userId);
        
        //自分自身ではないかの確認
        $its_me = $this->id == $userId;
        
        if ($exist || $its_me) {
            //すでにフォローして入れば何もしない
            
            return false;
        } else {
            //未フォローであればフォローする
            
            $this->followings()->attach($userId);
            
            return true;
        }
    }
    
    public function unfollow($userId)
    {
        // すでにフォローしているかの確認
        
        $exist = $this->is_following($userId);
        
        //自分自身ではないかの確認
        $its_me = $this->id == $userId;
        
        if ($exist && !$its_me) {
            //すでにフォローして入ればフォローを外す
            $this->followings()->detach($userId);
            return true;
        } else {
            //未フォローであれば何もしない
            return false;
        }
    }
    
    public function is_following($userId) {
        return $this->followings()->where('follow_id', $userId)->exists();
    }
    
    public function feed_microposts()
    {
        $follow_user_ids = $this->followings()->lists('users.id')->toArray();
        $follow_user_ids[] = $this->id;
        return Micropost::whereIn('user_id', $follow_user_ids);
    }
    
    public function favorites()
    {
        return $this->belongsToMany(Micropost::class, 'favorite_micropost', 'user_id', 'micropost_id')->withTimestamps();
    }
    
    public function favorite($micropostId) { 
        
        // 既にお気に入りに追加しているかの確認 
        $exist = $this->is_favorite($micropostId);
    
        if ($exist) {
    
            // 既にお気に入りに入れていれば何もしない
            return false;
    
        } else {
    
            // お気に入りになければお気に入りに追加する
    
            $this->favorites()->attach($micropostId);
            return true;
    
        }
    }

    public function unfavorite($micropostId)
    {
        // 既にお気に入りに追加しているかの確認 
        $exist = $this->is_favorite($micropostId);
    
        if ($exist) {
            // 既にお気に入りに入れていればお気に入りではなくする
            $this->favorites()->detach($micropostId);
            return true;
        } else {
            // お気に入りになければ何もしない
            return false;
        }
    }
    
    public function is_favorite($micropostId) {
        return $this->favorites()->where('micropost_id', $micropostId)->exists();
    }
}
