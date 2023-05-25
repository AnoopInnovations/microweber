<?php
namespace MicroweberPackages\Modules\Comments\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    public $fillable = [
        'rel_id',
        'rel_type',
        'reply_to_comment_id',
        'comment_name',
        'comment_body',
        'comment_email',
        'comment_website',
    ];

    public function getCommentNameAttribute()
    {
        if ($this->attributes['created_by'] > 0) {
            return user_name($this->attributes['created_by']);
        } else if (!empty($this->attributes['comment_name'])) {
            return $this->attributes['comment_name'];
        } else {
            return _e('Anonymous');
        }
    }

    public function getCommentBodyAttribute()
    {
        return app()->format->autolink($this->attributes['comment_body']);
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'reply_to_comment_id');
    }

    public function getLeveL()
    {
        $level = 0;
        $parent = $this->reply_to_comment_id;
        if ($parent) {
            while ($parent > 0) {
                $level++;
                $parent = Comment::select(['id', 'reply_to_comment_id'])
                    ->where('id', $parent)->value('reply_to_comment_id');
            }
        }
        return $level;
    }

    public function canIDeleteThisComment()
    {
        $user = user_id();
        if (is_admin() == true) {
         //   return true;
        }

        if ($user == $this->created_by) {
            return true;
        }

        if ($this->user_ip == user_ip()) {
                return true;
        }

        return false;
    }

    public function deleteWithReplies()
    {
        if(count($this->replies) > 0) {
            foreach ($this->replies as $reply) {
                $reply->deleteWithReplies();
            }
        }
        $this->delete();
    }
}