<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

Route::get('/patient', function (Request $request) {
     return User::query()
            ->select('id', 'name', 'email')
            ->when(
                $request->search,
                fn (Builder $query) => $query
                    ->where('name', 'like', "%{$request->search}%")
                    ->orWhere('email', 'like', "%{$request->search}%")
            )
            ->when(
                $request->exists('selected'),
                // fn (Builder $query) => $query->whereIn('id', $request->input('selected', [])),
                fn (Builder $query) => $query->whereHas('patient', fn (Builder $query) => $query->whereIn('id', $request->input('selected', []))),
                fn (Builder $query) => $query->limit(10)
            )
            ->whereHas('patient')
            ->with('patient')
            ->orderBy('name')
            ->get()
            ->map(function (User $user) {
                return [
                    'id' => $user->patient->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'patient' => $user->patient,
                ];
            });
})->name('api.patient');


