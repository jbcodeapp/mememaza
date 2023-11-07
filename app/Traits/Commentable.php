<?php

namespace App\Traits;

trait Commentable
{
    public function comments()
    {
        return $this->hasMany(\App\Models\Comment::class, 'type_id', 'id')
            ->where('type', $this->getCommentableMorphType())
            ->withCount('likes'); // comment like count
    }

    public function comment($comment, $commentType)
    {
        return $this->comments()->create([
            'user_id' => auth()->user()->id,
            'type' => $this->getCommentableMorphType(),
            'comment' => $comment,
            'comment_type' => $commentType,
        ])->load('commenter');
    }

    public function getCommentableMorphType()
    {
        return class_basename($this);
    }
}