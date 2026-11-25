<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Models\Employee;
use Models\Room;
use Models\RoomSupervisor;

class RoomSupervisorSeeder extends Seeder
{
    public function run(): void
    {
        $rooms = Room::where('code', 'like', 'K-%')->orderBy('id')->take(6)->get();
        $employees = Employee::orderBy('id')->take(4)->get();

        if ($rooms->isEmpty() || $employees->count() < 2) {
            $this->command?->warn('RoomSupervisorSeeder dilewati: butuh minimal 1 kamar dan 2 employees.');
            return;
        }

        foreach ($rooms as $i => $room) {
            $head = $employees[$i % $employees->count()];
            $assistant = $employees[($i + 1) % $employees->count()];

            RoomSupervisor::updateOrCreate(
                ['room_id' => $room->id, 'employee_id' => $head->id],
                ['role' => 'head', 'start_date' => now()->startOfYear()->toDateString(), 'is_active' => true]
            );

            RoomSupervisor::updateOrCreate(
                ['room_id' => $room->id, 'employee_id' => $assistant->id],
                ['role' => 'assistant', 'start_date' => now()->startOfYear()->toDateString(), 'is_active' => true]
            );
        }
    }
}