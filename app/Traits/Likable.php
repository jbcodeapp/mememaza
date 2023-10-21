<?php
  namespace App\Traits;

  trait Likable
  {
    public function likes()
    {
        return $this->hasMany(\App\Models\Like::class, 'type_id', 'id')
            ->where('type', $this->getLikableMorphType());
    }
    
    public function like()
    {
        return $this->likes()->create([
            'user_id' => auth()->user()->id,
            'type' => $this->getLikableMorphType(),
        ]);
    }

    public function getLikableMorphType()
    {
        return class_basename($this);
    }
  }