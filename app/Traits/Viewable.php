<?php

  namespace App\Traits;

  trait Viewable
  {
    public function views()
    {
        return $this->hasMany(\App\Models\View::class, 'type_id', 'id')
            ->where('type', $this->getViewableMorphType());
    }

    public function view()
    {
        return $this->views()->create([
            'user_id' => auth()->user()->id,
        ]);
    }

    public function getViewableMorphType()
    {
        return class_basename($this);
    }
  }