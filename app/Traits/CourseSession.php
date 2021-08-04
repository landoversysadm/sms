<?php

namespace App\Traits;

use App\Session;

trait CourseSession
{
    public function newSession($course)
    {
        $session = $course->sessions()->create([
            'start_date' => $course->start_date,
            'end_date' => $course->end_date
        ]);
        return $session;
    }

    public function currentSession($course)
    {
        $session = $course->sessions()->latest()->first();
        return $session;
    }

    public function updateSession($course)
    {
        $session = $this->currentSession($course);
        $session->start_date = $course->start_date;
        $session->end_date = $course->end_date;
        $session->save();
        return $session;
    }

}
