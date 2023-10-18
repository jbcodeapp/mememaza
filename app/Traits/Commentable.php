<?php

  namespace App\Traits;

  trait Commentable
  {
    public function comments()
    {
        return $this->hasMany(\App\Models\Comment::class, 'type_id', 'id')
            ->where('type', $this->getCommentableMorphType());
    }

    public function comment($comment)
    {
        return $this->likes()->create([
            'user_id' => auth()->user()->id,
            'comment' => $comment
        ]);
    }
    
    public function getCommentableMorphType()
    {
        return class_basename($this);
    }
  }