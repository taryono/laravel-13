<?php
namespace Models; 

class WorkCalendarHoliday extends BasedModel
{
    // Relasi
        public function workCalendar()
    {
        return $this->belongsTo(WorkCalendar::class, 'work_calendar_id');
    }

    public function holiday()
    {
        return $this->belongsTo(Holiday::class, 'holiday_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}