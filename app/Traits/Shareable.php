<?php

  namespace App\Traits;

  trait Shareable
  {
    public function shares()
    {
        return $this->hasMany(\App\Models\Share::class, 'type_id', 'id')
            ->where('type', $this->getShareableMorphType());
    }

    public function share()
    {
        return $this->shares()->create([
            'user_id' => auth()->user()->id,
        ]);
    }

    public function getShareableMorphType()
    {
        return class_basename($this);
    }
  }