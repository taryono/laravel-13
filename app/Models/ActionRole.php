<?php

namespace Models;

class ActionRole extends BasedModel {
    public function role() {
        return $this->belongsTo(Role::class);
    }
 
    public function action() {
        return $this->belongsTo(Action::class);
    }
 
}
