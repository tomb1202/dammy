<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserService extends BaseService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        parent::__construct($userRepository);
        $this->userRepository = $userRepository;
    }

    public function storeOrUpdate($data)
    {
        if (isset($data['id'])) {
            return $this->updateData($data['id'], $data);
        } else {
            return $this->create($data);
        }
    }

    public function create(array $data)
    {
        if (request()->hasFile('avatar')) {
            $data['avatar'] = $this->storeAvatarFile(request()->file('avatar'));
        }

        if (!empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        return User::create($data);
    }

    public function updateData(int $id, array $data)
    {
        $user = User::findOrFail($id);

        if (request()->hasFile('avatar')) {
            // Xóa ảnh cũ nếu có
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $data['avatar'] = $this->storeAvatarFile(request()->file('avatar'));
        }

        if (!empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return $user;
    }

    protected function storeAvatarFile($file)
    {
        $filename = Str::random(6) . '.' . $file->getClientOriginalExtension();
        return $file->storeAs('avatar', $filename, 'public');
    }
}
