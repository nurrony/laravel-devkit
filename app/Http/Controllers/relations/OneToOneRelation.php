<?php

declare(strict_types=1);

namespace App\Http\Controllers\Relations;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\JsonResponse;

final class OneToOneRelation extends Controller
{
    // Using factory create method
    public function byFactory(): JsonResponse
    {
        $user = User::query()->create(['name' => fake()->name(), 'email' => fake()->email(), 'password' => '123456']);

        Profile::query()->create([
            'first_name' => explode(' ', (string) $user->name)[0],
            'last_name' => explode(' ', (string) $user->name)[1],
            'birthday' => fake()->date(),
            'user_id' => $user->id,
        ]);

        // User::with('profile')->latest('id')->first();
        return response()->json(User::with('profile')->findOrFail($user->id));
    }

    // using user->profile()->create(array)
    public function byOwnerRelation()
    {

        $user = User::query()->create(['name' => fake()->name(), 'email' => fake()->email(), 'password' => '123456']);

        $user->profile()->create([
            'first_name' => explode(' ', (string) $user->name)[0],
            'last_name' => explode(' ', (string) $user->name)[1],
            'birthday' => fake()->date(),
        ]);

        return response()->json(User::with('profile')->findOrFail($user->id));
    }

    //
    public function usingOrm()
    {
        $user = User::query()->create(['name' => fake()->name(), 'email' => fake()->email(), 'password' => '123456']);
        $user->profile()->create([
            'birthday' => fake()->date(),
            'last_name' => explode(' ', (string) $user->name)[1],
            'first_name' => explode(' ', (string) $user->name)[0],
        ]);

        return response()->json(User::with('profile')->findOrFail($user->id));
    }
}
